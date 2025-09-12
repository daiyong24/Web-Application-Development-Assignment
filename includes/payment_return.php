<?php
// includes/payment_return.php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/../includes/db.php';

// 0) If user clicked Cancel or session is missing, bounce back.
$btnStatus = $_POST['Status'] ?? '0';
if ($btnStatus !== '1' || empty($_SESSION['pending_payment']) || empty($_SESSION['user_id'])) {
  // Check if it's a move service or regular service
  if (isset($_SESSION['pending_payment']['type']) && $_SESSION['pending_payment']['type'] === 'move') {
    header('Location: ../serviceDetails-moveInOut.php?step=4&error=cancelled');
  } else {
    header('Location: ../serviceDetails.php?step=4&error=cancelled');
  }
  exit;
}

// 1) Server-trusted data from the flow before the gateway
$pending = $_SESSION['pending_payment'];
$userId  = (int)$_SESSION['user_id'];

// Check if this is a move service
$isMoveService = isset($pending['type']) && $pending['type'] === 'move';

if ($isMoveService) {
  // Process move service payment
  $m = $pending['booking'] ?? [];
  $expectedAmount = (float)($pending['total_price'] ?? 0.0);
  $meta = $pending['service_meta'] ?? [];
  
  // Safety check
  if ($expectedAmount <= 0) {
    http_response_code(400);
    die('Invalid amount in session.');
  }
  
  // Build address text for move service
  function build_move_address_text(array $m): string {
    $from = trim(($m['from_line'] ?? '')
          . (!empty($m['from_postcode']) ? ' '.$m['from_postcode'] : '')
          . (!empty($m['from_city'])     ? ' '.$m['from_city']     : ''));

    $to   = trim(($m['to_line'] ?? '')
          . (!empty($m['to_postcode']) ? ' '.$m['to_postcode'] : '')
          . (!empty($m['to_city'])     ? ' '.$m['to_city']     : ''));

    return "From: {$from}  →  To: {$to}";
  }
  
  $address_text = build_move_address_text($m);
  
  try {
    $pdo->beginTransaction();

    // (a) Address
    $insAddr = $pdo->prepare("INSERT INTO addresses (user_id, address_text) VALUES (?, ?)");
    $insAddr->execute([$userId, $address_text]);
    $addressId = (int)$pdo->lastInsertId();

    // (b) Ensure service exists, get service_id
    $serviceId = ensure_service($pdo, $meta['name'], $meta['description'], $meta['price']);

    // (c) Booking
    $baseCols   = "user_id, address_id, service_id, schedule, date, payment_method, total_price";
    $basePlace  = "?, ?, ?, ?, ?, ?, ?";
    $baseParams = [
      $userId,
      $addressId,
      $serviceId,
      $pending['schedule'] ?? 'One-time (Move)',
      $m['date'] ?? date('Y-m-d'),
      'Online',
      $expectedAmount
    ];

    // Check if bookings table has status column
    $hasStatus = bookings_has_status_column($pdo);

    if ($hasStatus) {
      $sqlB   = "INSERT INTO bookings ($baseCols, status) VALUES ($basePlace, ?)";
      $params = array_merge($baseParams, ['Confirmed']);
    } else {
      $sqlB   = "INSERT INTO bookings ($baseCols) VALUES ($basePlace)";
      $params = $baseParams;
    }

    $insB = $pdo->prepare($sqlB);
    $insB->execute($params);

    $pdo->commit();

    // 6) Build the receipt payload for move service
    $_SESSION['move_receipt'] = [
      'service' => $meta['name'],
      'date'    => $m['date'] ?? '',
      'address' => $address_text,
      'name'    => $m['name'] ?? '',
      'email'   => $m['email'] ?? '',
      'number'  => $m['number'] ?? '',
      'payment' => 'Online',
      'total'   => $expectedAmount,
    ];

    // 7) Cleanup and redirect to the move service success screen
    unset($_SESSION['pending_payment'], $_SESSION['payment_ref']);

    header('Location: ../serviceDetails-moveInOut.php?success=1');
    exit;

  } catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log("Payment error (move service): " . $e->getMessage());
    header('Location: ../serviceDetails-moveInOut.php?step=4&error=' . urlencode($e->getMessage()));
    exit;
  }
  
} else {
  // Process regular service payment
  $b       = $pending['booking'] ?? [];
  $expectedAmount = (float)($pending['total_price'] ?? 0.0);

  // Safety check
  if ($expectedAmount <= 0) {
    http_response_code(400);
    die('Invalid amount in session.');
  }

  // 2) Build address_text
  $address_text = trim(
    ($b['address_line1'] ?? '') .
    ((isset($b['postcode']) && $b['postcode'] !== '') ? (', ' . $b['postcode']) : '') .
    ((isset($b['city'])     && $b['city']     !== '') ? (' ' . $b['city'])     : '')
  );

  // 3) Service metadata
  $serviceSlug = $b['service'] ?? '';
  $SERVICE_MAP = [
    'house-cleaning'  => ['name' => 'House Cleaning',  'description' => 'Standard Cleaning', 'price' => 140],
    'office-cleaning' => ['name' => 'Office Cleaning', 'description' => 'Standard Cleaning', 'price' => 250],
    'airbnb-cleaning' => ['name' => 'AirBnb Cleaning', 'description' => 'Turnover Cleaning', 'price' => 160],
  ];
  $meta = $SERVICE_MAP[$serviceSlug] ?? ['name'=>'Cleaning Service','description'=>'Standard','price'=>100];

  // 4) Helper: does `bookings` table have a `status` column?
  $hasStatus = bookings_has_status_column($pdo);

  // 5) Persist everything
  try {
    $pdo->beginTransaction();

    // (a) Address
    $insAddr = $pdo->prepare("INSERT INTO addresses (user_id, address_text) VALUES (?, ?)");
    $insAddr->execute([$userId, $address_text]);
    $addressId = (int)$pdo->lastInsertId();

    // (b) Ensure service exists, get service_id
    $selSvc = $pdo->prepare("SELECT id FROM services WHERE name=? LIMIT 1");
    $selSvc->execute([$meta['name']]);
    $serviceId = (int)($selSvc->fetchColumn() ?: 0);

    if (!$serviceId) {
      $insSvc = $pdo->prepare("INSERT INTO services (name, description, price) VALUES (?,?,?)");
      $insSvc->execute([$meta['name'], $meta['description'], (int)$meta['price']]);
      $serviceId = (int)$pdo->lastInsertId();
    }

    // (c) Booking
    $baseCols   = "user_id, address_id, service_id, schedule, date, payment_method, total_price";
    $basePlace  = "?, ?, ?, ?, ?, ?, ?";
    $baseParams = [
      $userId,
      $addressId,
      $serviceId,
      $b['schedule'] ?? 'One-time',
      $b['date']     ?? date('Y-m-d'),
      'Online',
      $expectedAmount
    ];

    if ($hasStatus) {
      $sqlB   = "INSERT INTO bookings ($baseCols, status) VALUES ($basePlace, ?)";
      $params = array_merge($baseParams, ['Confirmed']);
    } else {
      $sqlB   = "INSERT INTO bookings ($baseCols) VALUES ($basePlace)";
      $params = $baseParams;
    }

    $insB = $pdo->prepare($sqlB);
    $insB->execute($params);

    $pdo->commit();

    // 6) Build the receipt payload
    $_SESSION['receipt'] = [
      'service'        => $b['service']     ?? '',
      'location'       => $b['location']    ?? '',
      'address_text'   => $address_text,
      'date'           => $b['date']        ?? date('Y-m-d'),
      'schedule'       => $b['schedule']    ?? 'One-time',
      'payment_method' => 'Online',
      'total_price'    => $expectedAmount,
      'reference'      => $_SESSION['payment_ref'] ?? '',
    ];

    // 7) Cleanup and redirect to success screen
    $_SESSION['booking'] = [];
    unset($_SESSION['pending_payment'], $_SESSION['payment_ref']);

    header('Location: ../serviceDetails.php?success=1');
    exit;

  } catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log("Payment error (regular service): " . $e->getMessage());
    header('Location: ../serviceDetails.php?step=4&error=' . urlencode($e->getMessage()));
    exit;
  }
}

// Helper functions
function bookings_has_status_column(PDO $pdo): bool {
  $sql = "SELECT 1
          FROM INFORMATION_SCHEMA.COLUMNS
          WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME   = 'bookings'
            AND COLUMN_NAME  = 'status'
          LIMIT 1";
  return (bool)$pdo->query($sql)->fetchColumn();
}

function ensure_service(PDO $pdo, $name, $desc, $price) {
  $q = $pdo->prepare("SELECT id FROM services WHERE name=? LIMIT 1");
  $q->execute([$name]);
  $id = $q->fetchColumn();
  if ($id) return (int)$id;

  $ins = $pdo->prepare("INSERT INTO services (name, description, price) VALUES (?,?,?)");
  $ins->execute([$name, $desc, (int)$price]);
  return (int)$pdo->lastInsertId();
}