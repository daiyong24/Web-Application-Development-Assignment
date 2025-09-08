<?php
// includes/payment_return.php
// Handles the mock gateway "Proceed / Cancel" buttons for both flows:
//   1) Cleaning  -> serviceDetails.php
//   2) Move In/Out -> serviceDetails-moveInOut.php

if (session_status() === PHP_SESSION_NONE) session_start();

function redirect_default_with_error(string $msg) {
  header('Location: ../serviceDetails.php?step=4&error=' . urlencode($msg));
  exit;
}

if (empty($_SESSION['pending_payment'])) {
  redirect_default_with_error('No payment session.');
}

$pending = $_SESSION['pending_payment'];
$type    = $pending['type'] ?? 'cleaning';  // 'move' or 'cleaning'
$status  = $_POST['Status'] ?? '0';         // mock gateway: '1' success, '0' cancel

// ---------- COMMON DB helpers ----------
/** Create PDO. Adjust if you keep a central db.php instead. */
function db(): PDO {
  $DB_HOST = 'localhost';
  $DB_NAME = 'cleaning_db';
  $DB_USER = 'root';
  $DB_PASS = '';
  return new PDO(
    "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
    $DB_USER, $DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
}

/** Ensure a service row exists and return its id. */
function ensure_service(PDO $pdo, string $name, string $desc, int $price): int {
  $q = $pdo->prepare("SELECT id FROM services WHERE name = ? LIMIT 1");
  $q->execute([$name]);
  $id = $q->fetchColumn();
  if ($id) return (int)$id;

  $ins = $pdo->prepare("INSERT INTO services (name, description, price) VALUES (?,?,?)");
  $ins->execute([$name, $desc, $price]);
  return (int)$pdo->lastInsertId();
}

// ==========================================================
// SUCCESS
// ==========================================================
if ($status === '1') {

  if ($type === 'move') {
    // All the info we stored before redirecting to the gateway
    $b            = $pending['booking']      ?? [];
    $totalPrice   = (int)($pending['total_price'] ?? 0);
    $serviceMeta  = $pending['service_meta'] ?? ['name' => 'Move In / Move Out', 'description' => '', 'price' => $totalPrice];
    $addressText  = $pending['address_text'] ?? '';
    $schedule     = $pending['schedule']     ?? 'One-time (Move)';

    try {
      $pdo = db();
      $pdo->beginTransaction();

      // 1) user_id – prefer the logged-in user, else upsert by email
      $uid = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
      if (!$uid) {
        $email = trim($b['email'] ?? '');
        if ($email !== '') {
          $s = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
          $s->execute([$email]);
          $uid = (int)$s->fetchColumn();
        }
        if (!$uid) {
          $insU = $pdo->prepare("INSERT INTO users (name,email,number,password) VALUES (?,?,?,?)");
          $insU->execute([
            $b['name']   ?? '',
            $b['email']  ?? '',
            $b['number'] ?? '',
            password_hash('changeme', PASSWORD_BCRYPT)
          ]);
          $uid = (int)$pdo->lastInsertId();
        }
      }

      // 2) address
      $insA = $pdo->prepare("INSERT INTO addresses (user_id,address_text) VALUES (?,?)");
      $insA->execute([$uid, $addressText]);
      $addrId = (int)$pdo->lastInsertId();

      // 3) service
      $serviceId = ensure_service($pdo, $serviceMeta['name'], ($serviceMeta['description'] ?? ''), (int)($serviceMeta['price'] ?? $totalPrice));

      // 4) booking (payment_method = Online)
      $insB = $pdo->prepare("
        INSERT INTO bookings (user_id,address_id,service_id,schedule,date,payment_method,total_price)
        VALUES (?,?,?,?,?,?,?)
      ");
      $insB->execute([
        $uid,
        $addrId,
        $serviceId,
        $schedule,
        $b['date'] ?? date('Y-m-d'),
        'Online',
        $totalPrice
      ]);

      $pdo->commit();

      // Build receipt for success page
      $_SESSION['move_receipt'] = [
        'service' => $serviceMeta['name'],
        'date'    => $b['date'] ?? '',
        'address' => $addressText,
        'name'    => $b['name'] ?? '',
        'email'   => $b['email'] ?? '',
        'number'  => $b['number'] ?? '',
        'payment' => 'Online',
        'total'   => $totalPrice,
      ];

      // Clean temp
      unset($_SESSION['pending_payment'], $_SESSION['move']);

      header('Location: ../serviceDetails-moveInOut.php?success=1');
      exit;

    } catch (Exception $e) {
      if (isset($pdo)) $pdo->rollBack();
      unset($_SESSION['pending_payment']);
      header('Location: ../serviceDetails-moveInOut.php?step=4&error=' . urlencode('DB error: ' . $e->getMessage()));
      exit;
    }
  }

  // ----------------- Cleaning flow (default) -----------------
  // NOTE: Your current cleaning online path never wrote to DB.
  // To save it here, we need service meta and a one-line address.
  // Easiest fix: when you set $_SESSION['pending_payment'] in serviceDetails.php,
  // also include 'service_meta' and 'address_text' exactly like the Move flow.
  $b          = $pending['booking'] ?? [];
  $totalPrice = (int)($pending['total_price'] ?? 0);
  $service    = $pending['service_meta']['name']        ?? 'Cleaning Service';
  $desc       = $pending['service_meta']['description'] ?? 'Standard Cleaning';
  $basePrice  = (int)($pending['service_meta']['price'] ?? $totalPrice);
  $addressTxt = $pending['address_text']
    ?? trim(
        ($b['address_line1'] ?? '') .
        (!empty($b['postcode']) ? ', '.$b['postcode'] : '') .
        (!empty($b['city'])     ? ' '.$b['city']       : '')
       );
  $schedule   = $b['schedule'] ?? 'One-time';

  try {
    $pdo = db();
    $pdo->beginTransaction();

    // user
    $uid = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    if (!$uid) {
      $email = trim($b['email'] ?? '');
      if ($email !== '') {
        $s = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $s->execute([$email]);
        $uid = (int)$s->fetchColumn();
      }
      if (!$uid) {
        $insU = $pdo->prepare("INSERT INTO users (name,email,number,password) VALUES (?,?,?,?)");
        $insU->execute([
          $b['name']   ?? '',
          $b['email']  ?? '',
          $b['number'] ?? '',
          password_hash('changeme', PASSWORD_BCRYPT)
        ]);
        $uid = (int)$pdo->lastInsertId();
      }
    }

    // address
    $insA = $pdo->prepare("INSERT INTO addresses (user_id,address_text) VALUES (?,?)");
    $insA->execute([$uid, $addressTxt]);
    $addrId = (int)$pdo->lastInsertId();

    // service
    $serviceId = ensure_service($pdo, $service, $desc, $basePrice);

    // booking (Online)
    $insB = $pdo->prepare("
      INSERT INTO bookings (user_id,address_id,service_id,schedule,date,payment_method,total_price)
      VALUES (?,?,?,?,?,?,?)
    ");
    $insB->execute([
      $uid, $addrId, $serviceId,
      $schedule,
      $b['date'] ?? date('Y-m-d'),
      'Online',
      $totalPrice
    ]);

    $pdo->commit();

    $_SESSION['receipt'] = $b;
    $_SESSION['receipt']['total_price'] = $totalPrice;
    $_SESSION['receipt']['address_text'] = $addressTxt;

    $_SESSION['booking'] = [];
    unset($_SESSION['pending_payment']);

    header('Location: ../serviceDetails.php?success=1');
    exit;

  } catch (Exception $e) {
    if (isset($pdo)) $pdo->rollBack();
    unset($_SESSION['pending_payment']);
    header('Location: ../serviceDetails.php?step=4&error=' . urlencode('DB error: ' . $e->getMessage()));
    exit;
  }
}

// ==========================================================
// CANCELLED
// ==========================================================
unset($_SESSION['pending_payment']);

if ($type === 'move') {
  header('Location: ../serviceDetails-moveInOut.php?step=4&error=' . urlencode('Payment cancelled.'));
  exit;
}

// default cleaning
redirect_default_with_error('Payment cancelled.');
