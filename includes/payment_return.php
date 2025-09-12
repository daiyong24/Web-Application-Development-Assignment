<?php
// includes/payment_return.php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/../includes/db.php';

// 0) If user clicked Cancel or session is missing, bounce back.
$btnStatus = $_POST['Status'] ?? '0'; // "1" (Proceed) or "0" (Cancel)
if ($btnStatus !== '1' || empty($_SESSION['pending_payment']) || empty($_SESSION['user_id'])) {
  header('Location: ../serviceDetails.php?step=4&error=cancelled');
  exit;
}

// 1) Server-trusted data from the flow before the gateway
$pending = $_SESSION['pending_payment'];
$b       = $pending['booking'] ?? [];
$expectedAmount = (float)($pending['total_price'] ?? 0.0);
$userId  = (int)$_SESSION['user_id'];

// Safety check
if ($expectedAmount <= 0) {
  http_response_code(400);
  die('Invalid amount in session.');
}

// 2) Build address_text (matches your schema: addresses.address_text)
$address_text = trim(
  ($b['address_line1'] ?? '') .
  ((isset($b['postcode']) && $b['postcode'] !== '') ? (', ' . $b['postcode']) : '') .
  ((isset($b['city'])     && $b['city']     !== '') ? (' ' . $b['city'])     : '')
);

// 3) Service metadata (same slugs you use on serviceDetails.php)
$serviceSlug = $b['service'] ?? '';
$SERVICE_MAP = [
  'house-cleaning'  => ['name' => 'House Cleaning',  'description' => 'Standard Cleaning', 'price' => 140],
  'office-cleaning' => ['name' => 'Office Cleaning', 'description' => 'Standard Cleaning', 'price' => 250],
  'airbnb-cleaning' => ['name' => 'AirBnb Cleaning', 'description' => 'Turnover Cleaning', 'price' => 160],
];
$meta = $SERVICE_MAP[$serviceSlug] ?? ['name'=>'Cleaning Service','description'=>'Standard','price'=>100];

// 4) Helper: does `bookings` table have a `status` column?
function bookings_has_status_column(PDO $pdo): bool {
  $sql = "SELECT 1
          FROM INFORMATION_SCHEMA.COLUMNS
          WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME   = 'bookings'
            AND COLUMN_NAME  = 'status'
          LIMIT 1";
  return (bool)$pdo->query($sql)->fetchColumn();
}
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

  // 6) Build the same receipt payload your success page expects
  $_SESSION['receipt'] = [
    'service'        => $b['service']     ?? '',
    'location'       => $b['location']    ?? '',
    'address_text'   => $address_text,
    'date'           => $b['date']        ?? date('Y-m-d'),
    'schedule'       => $b['schedule']    ?? 'One-time',
    'payment_method' => 'Online',
    'total_price'    => $expectedAmount,
    // Optional: show reference if you kept it in the gateway
    'reference'      => $_SESSION['payment_ref'] ?? '',
  ];

  // 7) Cleanup and redirect to the SAME success screen as cash flow
  $_SESSION['booking'] = [];
  unset($_SESSION['pending_payment'], $_SESSION['payment_ref']);

  header('Location: ../serviceDetails.php?success=1');
  exit;

} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  // Show exact error while developing (hide in production)
  echo 'Error saving booking: ' . htmlspecialchars($e->getMessage());
}
