<?php

require_once __DIR__ . '/includes/auth.php'; 
require_once __DIR__ . '/includes/db.php';   

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
  $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
  header('Location: login.php'); exit;
}

$DB_HOST = 'localhost';
$DB_NAME = 'cleaning_db';
$DB_USER = 'root';
$DB_PASS = '';

/* --- Plans & pricing --- */
$PLAN_PRICES = [
  'A' => 450,   // Standard
  'B' => 550,   // Deluxe
  'C' => 850,   // Queen
  'D' => 1000,  // King
];
$currentUser = null;
try {
    $pdoUser = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $st = $pdoUser->prepare("SELECT id, name, email, number FROM users WHERE id = ?");
    $st->execute([$_SESSION['user_id']]);
    $currentUser = $st->fetch(PDO::FETCH_ASSOC) ?: null;
} catch (Exception $e) {
    $currentUser = null; // fail safe
}
function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

function ensure_service(PDO $pdo, $name, $desc, $price){
  $q = $pdo->prepare("SELECT id FROM services WHERE name=? LIMIT 1");
  $q->execute([$name]);
  $id = $q->fetchColumn();
  if ($id) return (int)$id;

  $ins = $pdo->prepare("INSERT INTO services (name, description, price) VALUES (?,?,?)");
  $ins->execute([$name, $desc, (int)$price]);
  return (int)$pdo->lastInsertId();
}

// Build the single-line route shown on the receipt / stored in DB
function build_move_address_text(array $m): string {
  $from = trim(($m['from_line'] ?? '')
        . (!empty($m['from_postcode']) ? ' '.$m['from_postcode'] : '')
        . (!empty($m['from_city'])     ? ' '.$m['from_city']     : ''));

  $to   = trim(($m['to_line'] ?? '')
        . (!empty($m['to_postcode']) ? ' '.$m['to_postcode'] : '')
        . (!empty($m['to_city'])     ? ' '.$m['to_city']     : ''));

  return "From: {$from}  →  To: {$to}";
}


/* --- Step control --- */
$step = isset($_GET['step']) ? max(1, min(4, (int)$_GET['step'])) : 1;

if (isset($_GET['service'])) $_SESSION['move']['service'] = $_GET['service']; // should be 'move-in-out'
if (isset($_GET['plan']))    $_SESSION['move']['plan']    = strtoupper($_GET['plan']);

/* --- Handle posts --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Step 1 -> 2 (nothing heavy here, just making sure plan is stored)
  if (isset($_POST['go_step2'])) {
    $_SESSION['move']['plan'] = strtoupper($_POST['plan'] ?? ($_SESSION['move']['plan'] ?? 'A'));
    header('Location: ?step=2'); exit;
  }

  // Step 2 -> 3 (addresses + date)
  if (isset($_POST['go_step3'])) {
    $_SESSION['move']['from_line']    = trim($_POST['from_line'] ?? '');
    $_SESSION['move']['from_postcode']= trim($_POST['from_postcode'] ?? '');
    $_SESSION['move']['from_city']    = trim($_POST['from_city'] ?? '');

    $_SESSION['move']['to_line']      = trim($_POST['to_line'] ?? '');
    $_SESSION['move']['to_postcode']  = trim($_POST['to_postcode'] ?? '');
    $_SESSION['move']['to_city']      = trim($_POST['to_city'] ?? '');

    $_SESSION['move']['date']         = $_POST['date'] ?? '';
    header('Location: ?step=3'); exit;
  }

  // Step 3 -> 4 (your details)
  if (isset($_POST['go_step4'])) {
  $_SESSION['move']['name']   = $currentUser['name']  ?? '';
  $_SESSION['move']['email']  = $currentUser['email'] ?? '';

  $postedNumber = trim($_POST['number'] ?? '');
  $_SESSION['move']['number'] = $postedNumber !== '' ? $postedNumber : ($currentUser['number'] ?? '');

  $_SESSION['move']['payment_method'] = $_POST['payment_method'] ?? 'Cash';
  header('Location: ?step=4'); exit;
  }

  // Confirm booking
  if (isset($_POST['confirm_booking'])) {
  // Start buffering so headers work even if something echoed earlier
  if (!headers_sent()) { ob_start(); }

  $m = $_SESSION['move'] ?? [];

  // Safety: ensure payment method is in session from Step 3
  $payMethod = $m['payment_method'] ?? 'Cash';

  $plan  = strtoupper($m['plan'] ?? 'A');
  $price = $PLAN_PRICES[$plan] ?? 450;

  $serviceName = "Move In / Move Out - Plan {$plan}";
  $serviceDesc = "Relocation moving service (Plan {$plan})";
  $addressText = build_move_address_text($m);
  $schedule    = 'One-time (Move)';

  // ---- 1) ONLINE → hand off to gateway
  if ($payMethod === 'Online') {
    $_SESSION['pending_payment'] = [
      'type'         => 'move',
      'booking'      => $m,
      'total_price'  => $price,
      'service_meta' => [
        'name'        => $serviceName,
        'description' => $serviceDesc,
        'price'       => $price,
      ],
      'address_text' => $addressText,
      'schedule'     => $schedule,
    ];

    session_write_close();

    header('Location: payment_gateway.php');
    exit;
    
  }

  // ---- 2) CASH → create booking immediately
  try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $pdo->beginTransaction();

    // Use the logged-in user
    $uid = (int)($_SESSION['user_id'] ?? 0);

    // Optional: update phone if changed
    if (!empty($m['number']) && isset($currentUser['number']) && $m['number'] !== $currentUser['number']) {
      $upd = $pdo->prepare("UPDATE users SET number = ?, updated_at = NOW() WHERE id = ?");
      $upd->execute([$m['number'], $uid]);
    }

    // Address
    $insA = $pdo->prepare("INSERT INTO addresses (user_id, address_text) VALUES (?, ?)");
    $insA->execute([$uid, $addressText]);
    $addrId = (int)$pdo->lastInsertId();

    // Service (ensure exists)
    $serviceId = ensure_service($pdo, $serviceName, $serviceDesc, $price);

    // Booking
    $insB = $pdo->prepare("
      INSERT INTO bookings (user_id,address_id,service_id,schedule,date,payment_method,total_price)
      VALUES (?,?,?,?,?,?,?)
    ");
    $insB->execute([
      $uid, $addrId, $serviceId,
      $schedule,
      $m['date'] ?? date('Y-m-d'),
      'Cash',
      $price,
    ]);

    $pdo->commit();

    $_SESSION['move_receipt'] = [
      'service' => $serviceName,
      'date'    => $m['date'] ?? '',
      'address' => $addressText,
      'name'    => $m['name'] ?? '',
      'email'   => $m['email'] ?? '',
      'number'  => $m['number'] ?? '',
      'payment' => 'Cash',
      'total'   => $price,
    ];
    $_SESSION['move'] = [];

    // Flush session & redirect
    session_write_close();
    header('Location: ?success=1');
    exit;

  } catch (Exception $e) {
    if (isset($pdo)) $pdo->rollBack();
    session_write_close();
    header('Location: ?step=4&error=' . urlencode($e->getMessage()));
    exit;
  }
}
}
/* --- Success page --- */
if (isset($_GET['success'])) {
  $r = $_SESSION['move_receipt'] ?? [];
  ?>
  <!doctype html><html lang="en"><head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Move Booking Confirmed - Shine & Sparkle</title>
    <link rel="stylesheet" href="css/style-service-details.css">
  </head><body>
    <div class="card success">
      <h1 class="title">Booking Confirmed 🎉</h1>
      <div class="row"><strong>Service</strong><div><?=h($r['service'])?></div></div>
      <div class="row"><strong>Date</strong><div><?=h($r['date'])?></div></div>
      <div class="row"><strong>Route</strong><div><?=h($r['address'])?></div></div>
      <div class="row"><strong>Name</strong><div><?=h($r['name'])?></div></div>
      <div class="row"><strong>Email</strong><div><?=h($r['email'])?></div></div>
      <div class="row"><strong>Phone</strong><div><?=h($r['number'])?></div></div>
      <div class="row"><strong>Payment</strong><div><?=h($r['payment'])?></div></div>
      <div class="row"><strong>Total</strong><div>RM <?=number_format((int)$r['total'],2)?></div></div>
      <a class="btn" href="index.php">Back to Home</a>
    </div>
  </body></html>
  <?php 
  // Clear the receipt after displaying
  unset($_SESSION['move_receipt']);
  exit;
}

/* --- View (Steps) --- */
$m = $_SESSION['move'] ?? [];
$plan   = strtoupper($m['plan'] ?? $_GET['plan'] ?? 'A');
$price  = $PLAN_PRICES[$plan] ?? 450;
$progress = [1=>25,2=>50,3=>75,4=>100][$step];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Move In / Move Out — Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/style-service-details.css">
</head>
<body>
  <header class="details-header">
    <a class="back-btn" href="javascript:history.back()">← Back</a>
    <div class="service-title">
      <h1>Move In / Move Out</h1>
      <div class="sub" id="plan-display">Plan <?=h($plan)?> — RM <?=number_format($price,2)?></div>
    </div>
  </header>

  <div class="container">
    <!-- progress -->
    <div class="progress">
      <div class="progress-track"><div class="progress-bar" style="width: <?=$progress?>%"></div></div>
      <div class="progress-label">Step <?=$step?> of 4</div>
    </div>

    <div class="stepper">
      <div class="chip <?=$step===1?'active':''?>">Step 1 · Select Plan</div>
      <div class="chip <?=$step===2?'active':''?>">Step 2 · Address & Date</div>
      <div class="chip <?=$step===3?'active':''?>">Step 3 · Your Details</div>
      <div class="chip <?=$step===4?'active':''?>">Step 4 · Review & Confirm</div>
    </div>

    <?php if ($step === 1): ?>
      <form method="post" class="card">
        <div class="row">
          <div>
            <label for="plan">Selected Plan</label>
            <select id="plan" name="plan" required onchange="updatePlanDisplay()">
              <?php foreach ($PLAN_PRICES as $p => $amt): ?>
                <option value="<?=$p?>" <?=$plan===$p?'selected':''?>>Plan <?=$p?> — RM <?=number_format($amt,2)?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="actions">
          <button class="btn primary" type="submit" name="go_step2">Continue</button>
        </div>
      </form>

    <?php elseif ($step === 2): ?>
      <form method="post" class="card">
        <h3 class="review-title">Where are you moving?</h3>
        <div class="row">
          <div>
            <label>From — Address Line</label>
            <input name="from_line" required value="<?=h($m['from_line'] ?? '')?>">
          </div>
          <div>
            <label>From — Postcode</label>
            <input name="from_postcode" required value="<?=h($m['from_postcode'] ?? '')?>">
          </div>
          <div>
            <label>From — City</label>
            <input name="from_city" required value="<?=h($m['from_city'] ?? '')?>">
          </div>
        </div>

        <div class="row">
          <div>
            <label>To — Address Line</label>
            <input name="to_line" required value="<?=h($m['to_line'] ?? '')?>">
          </div>
          <div>
            <label>To — Postcode</label>
            <input name="to_postcode" required value="<?=h($m['to_postcode'] ?? '')?>">
          </div>
          <div>
            <label>To — City</label>
            <input name="to_city" required value="<?=h($m['to_city'] ?? '')?>">
          </div>
        </div>

        <div class="row">
          <div>
            <label>Preferred Date</label>
            <input type="date" name="date" required value="<?=h($m['date'] ?? '')?>">
          </div>
        </div>

        <div class="actions">
          <a class="btn" href="?step=1">Back</a>
          <button class="btn primary" type="submit" name="go_step3">Continue</button>
        </div>
      </form>

    <?php elseif ($step === 3): ?>
      <form method="post" class="card">
        <div class="row">
          <div>
            <label>Your Name</label>
            <input name="name" 
            value="<?= h($currentUser['name'] ?? ($m['name'] ?? '')) ?>"
            readonly
            class="readonly"
            title="This is your account name">
          </div>
          <div>
            <label>Email</label>
            <input type="email" 
            name="email"  
            value="<?= h($currentUser['email'] ?? ($m['email'] ?? '')) ?>"
            readonly
            class="readonly"
            title="This is your account email">
          </div>
          <div>
            <label>Phone Number</label>
            <input name="number" required value="<?=h($m['number'] ?? '')?>">
          </div>
          <div>
            <label>Payment Method</label>
            <select name="payment_method" required>
              <?php $pm = $m['payment_method'] ?? 'Cash'; ?>
              <option <?=$pm==='Cash'?'selected':''?>>Cash</option>
              <option <?=$pm==='Online'?'selected':''?>>Online</option>
            </select>
          </div>
        </div>
        <div class="actions">
          <a class="btn" href="?step=2">Back</a>
          <button class="btn primary" type="submit" name="go_step4">Continue</button>
        </div>
      </form>

    <?php elseif ($step === 4): ?>
      <?php $err = $_GET['error'] ?? ''; ?>
      <div class="card">
        <h3 class="review-title">Review your booking</h3>
        <?php if ($err): ?><p class="error">Error: <?=h($err)?></p><?php endif; ?>
        <div class="summary">
          <strong>Service</strong><div><?=h("Move In / Move Out — Plan {$plan}")?></div>
          <strong>Date</strong><div><?=h($m['date'] ?? '')?></div>

          <strong>From</strong>
          <div class="muted">
            <?=h($m['from_line'] ?? '')?><br>
            <?=h(($m['from_postcode'] ?? '').' '.($m['from_city'] ?? ''))?>
          </div>

          <strong>To</strong>
          <div class="muted">
            <?=h($m['to_line'] ?? '')?><br>
            <?=h(($m['to_postcode'] ?? '').' '.($m['to_city'] ?? ''))?>
          </div>

          <strong>Name</strong><div><?=h($m['name'] ?? '')?></div>
          <strong>Email</strong><div><?=h($m['email'] ?? '')?></div>
          <strong>Phone</strong><div><?=h($m['number'] ?? '')?></div>
          <strong>Payment</strong><div><?=h($m['payment_method'] ?? 'Cash')?></div>
          <strong>Estimated Total</strong><div>RM <?=number_format($price,2)?></div>
        </div>
        <form method="post" class="actions">
          <a class="btn" href="?step=3">Back</a>
          <button class="btn primary" type="submit" name="confirm_booking">Confirm Booking</button>
        </form>
      </div>
    <?php endif; ?>

  </div>
  
  <?php if ($step === 1): ?>
  <script>
  function updatePlanDisplay() {
    const planSelect = document.getElementById('plan');
    const planDisplay = document.getElementById('plan-display');
    const selectedOption = planSelect.options[planSelect.selectedIndex];
    
    // Update the header with the selected plan
    planDisplay.textContent = selectedOption.textContent;
  }
  
  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    // Set up the change event listener
    document.getElementById('plan').addEventListener('change', updatePlanDisplay);
  });
  </script>
  <?php endif; ?>
</body>
</html>