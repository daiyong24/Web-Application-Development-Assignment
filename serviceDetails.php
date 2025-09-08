<?php
// serviceDetails.php (refactored to remove redundancy & logic mismatch)

require_once __DIR__ . '/includes/auth.php'; // starts session + enforces login
require_once __DIR__ . '/includes/db.php';   // provides $pdo (PDO connection)
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$userId = (int)$_SESSION['user_id'];

// --- Helpers (kept local; you can move to a helpers.php later)
function h($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function is_post() { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function slug_to_label($slug, $map, $fallback = '—') {
  return $map[$slug]['label'] ?? ($map[$slug] ?? $fallback);
}
function service_meta($slug, $map) {
  return $map[$slug] ?? ['name' => 'Cleaning Service', 'description' => 'Standard Cleaning', 'price' => 100];
}
function ensure_service_id(PDO $pdo, $serviceMeta) {
  $stmt = $pdo->prepare("SELECT id FROM services WHERE name = ?");
  $stmt->execute([$serviceMeta['name']]);
  $id = $stmt->fetchColumn();
  if ($id) return (int)$id;

  $stmt = $pdo->prepare("INSERT INTO services (name, description, price) VALUES (?, ?, ?)");
  $stmt->execute([$serviceMeta['name'], $serviceMeta['description'], (int)$serviceMeta['price']]);
  return (int)$pdo->lastInsertId();
}
function calc_total_price($serviceSlug, $schedule, $SERVICE_MAP) {
  $meta = service_meta($serviceSlug, $SERVICE_MAP);
  $base = (int)$meta['price'];
  $mult = 1.0;
  if ($schedule === 'Weekly')  $mult = 0.90;
  if ($schedule === 'Monthly') $mult = 0.95;
  return (int) round($base * $mult);
}

// --- Static maps (you can move these to a config file later)
$SERVICE_MAP = [
  'house-cleaning'  => ['name' => 'House Cleaning',  'description' => 'Standard Cleaning', 'price' => 140],
  'office-cleaning' => ['name' => 'Office Cleaning', 'description' => 'Standard Cleaning', 'price' => 250],
  'airbnb-cleaning' => ['name' => 'AirBnb Cleaning', 'description' => 'Turnover Cleaning', 'price' => 160],
];
$LOCATION_MAP = [
  'kuala-lumpur' => 'Kuala Lumpur',
  'selangor'     => 'Selangor',
];

// --- Load logged-in account (for display; source of truth is user_id)
$stmt = $pdo->prepare("SELECT id, name, email, number FROM users WHERE id = ?");
$stmt->execute([$userId]);
$account = $stmt->fetch() ?: ['name'=>'','email'=>'','number'=>''];

// --- Step router
$step = isset($_GET['step']) ? max(1, min(4, (int)$_GET['step'])) : 1;

if (isset($_GET['service']))  $_SESSION['booking']['service']  = $_GET['service'];
if (isset($_GET['location'])) $_SESSION['booking']['location'] = $_GET['location'];

// Handle steps
if (is_post()) {
  if (isset($_POST['go_step2'])) {
    $_SESSION['booking']['service']  = $_POST['service'] ?? $_SESSION['booking']['service'] ?? '';
    $_SESSION['booking']['location'] = $_POST['location'] ?? $_SESSION['booking']['location'] ?? '';
    header('Location: ?step=2'); exit;
  }

  if (isset($_POST['go_step3'])) {
    $_SESSION['booking']['address_line1'] = $_POST['address_line1'] ?? '';
    $_SESSION['booking']['city']          = slug_to_label($_SESSION['booking']['location'] ?? '', $LOCATION_MAP, '');
    $_SESSION['booking']['postcode']      = $_POST['postcode'] ?? '';
    $_SESSION['booking']['schedule']      = $_POST['schedule'] ?? 'One-time';
    $_SESSION['booking']['date']          = $_POST['date'] ?? '';
    header('Location: ?step=3'); exit;
  }

  if (isset($_POST['go_step4'])) {
    $_SESSION['booking']['number']         = $_POST['number'] ?? '';
    $_SESSION['booking']['payment_method'] = $_POST['payment_method'] ?? 'Cash';
    header('Location: ?step=4'); exit;
  }

  if (isset($_POST['confirm_booking'])) {
    $b = $_SESSION['booking'] ?? [];
    $serviceSlug = $b['service'] ?? '';
    $schedule    = $b['schedule'] ?? 'One-time';
    $total_price = calc_total_price($serviceSlug, $schedule, $SERVICE_MAP);

    if (($b['payment_method'] ?? 'Cash') === 'Online') {
      $_SESSION['pending_payment'] = [
        'booking'     => $b,
        'total_price' => $total_price
      ];
      header('Location: payment_gateway.php'); // your gateway handoff page
      exit;
    }

    // Build address string per your schema (addresses.address_text)
    $address_text = trim(
      ($b['address_line1'] ?? '') .
      ((isset($b['postcode']) && $b['postcode'] !== '') ? (', ' . $b['postcode']) : '') .
      ((isset($b['city'])     && $b['city']     !== '') ? (' ' . $b['city'])     : '')
    );

    try {
      $pdo->beginTransaction();

      // Address
      $stmt = $pdo->prepare("INSERT INTO addresses (user_id, address_text) VALUES (?, ?)");
      $stmt->execute([$userId, $address_text]);
      $addressId = (int)$pdo->lastInsertId();

      // Service (ensure exists)
      $meta = service_meta($serviceSlug, $SERVICE_MAP);
      $serviceId = ensure_service_id($pdo, $meta);

      // Booking (bind to logged-in user)
      $stmt = $pdo->prepare("
        INSERT INTO bookings (user_id, address_id, service_id, schedule, date, payment_method, total_price)
        VALUES (?, ?, ?, ?, ?, ?, ?)
      ");
      $stmt->execute([
        $userId,
        $addressId,
        $serviceId,
        $schedule,
        $b['date'],
        $b['payment_method'],
        $total_price
      ]);

      $pdo->commit();

      // Success receipt
      $receipt = $b;
      $receipt['total_price']  = $total_price;
      $receipt['address_text'] = $address_text;
      $_SESSION['booking'] = [];
      $_SESSION['receipt'] = $receipt;
      header('Location: ?success=1'); exit;

    } catch (Exception $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      header('Location: ?step=4&error=' . urlencode($e->getMessage())); exit;
    }
  }
}

// --- Success page
if (isset($_GET['success'])) {
  $r = $_SESSION['receipt'] ?? [];
  $service  = service_meta($r['service'] ?? '', $SERVICE_MAP)['name'] ?? '—';
  $location = slug_to_label($r['location'] ?? '', $LOCATION_MAP, '—');
  ?>
  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmed</title>
    <link rel="stylesheet" href="css/style-service-details.css">
  </head>
  <body>
    <div class="card success">
      <h1 class="title">Booking Confirmed</h1>
      <div class="row"><strong>Service:</strong><div><?=h($service)?></div></div>
      <div class="row"><strong>Location:</strong><div><?=h($location)?></div></div>
      <div class="row"><strong>Address:</strong><div><?=h($r['address_text'] ?? '')?></div></div>
      <div class="row"><strong>Date:</strong><div><?=h($r['date']??'')?></div></div>
      <div class="row"><strong>Schedule:</strong><div><?=h($r['schedule']??'')?></div></div>
      <div class="row"><strong>Payment:</strong><div><?=h($r['payment_method']??'')?></div></div>
      <div class="row"><strong>Total:</strong><div>RM <?=number_format((int)($r['total_price']??0), 2)?></div></div>
      <a class="btn" href="index.php">Back to Home</a>
    </div>
  </body>
  </html>
  <?php
  exit;
}

// --- View (Steps 1–4)
$b = $_SESSION['booking'] ?? [];
$serviceSlug   = $b['service']  ?? $_GET['service']  ?? '';
$locationSlug  = $b['location'] ?? $_GET['location'] ?? '';
$serviceLabel  = service_meta($serviceSlug, $SERVICE_MAP)['name'] ?? 'Select Service';
$locationLabel = slug_to_label($locationSlug, $LOCATION_MAP, 'Select Location');
$cityLabel     = slug_to_label($locationSlug, $LOCATION_MAP, '');

$progressByStep = [1 => 25, 2 => 50, 3 => 75, 4 => 100];
$progress = $progressByStep[$step] ?? 25;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Service Details - Shine & Sparkle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/style-service-details.css">
</head>
<body>
  <header class="details-header">
    <a class="back-btn" href="javascript:void(0)" onclick="history.back()">← Back</a>
    <div class="service-title">
      <h1><?=h($serviceLabel)?></h1>
      <div class="sub"><?=h($locationLabel)?></div>
    </div>
  </header>

  <div class="container">
    <!-- Progress bar -->
    <div class="progress">
      <div class="progress-track">
        <div class="progress-bar" style="width: <?= (int)$progress ?>%"></div>
      </div>
      <div class="progress-label">Step <?= (int)$step ?> of 4</div>
    </div>

    <div class="stepper">
      <div class="chip <?= $step===1?'active':'' ?>">Step 1 · Service & Location</div>
      <div class="chip <?= $step===2?'active':'' ?>">Step 2 · Address & Schedule</div>
      <div class="chip <?= $step===3?'active':'' ?>">Step 3 · Your Details</div>
      <div class="chip <?= $step===4?'active':'' ?>">Step 4 · Review & Confirm</div>
    </div>

    <?php if ($step === 1): ?>
      <form method="post" class="card">
        <div class="row">
          <div>
            <label for="service">Service</label>
            <select id="service" name="service" required>
              <option value="">Select Your Service</option>
              <?php foreach ($SERVICE_MAP as $slug => $meta): ?>
                <option value="<?=$slug?>" <?= $serviceSlug===$slug?'selected':'' ?>><?=$meta['name']?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="location">Location</label>
            <select id="location" name="location" required>
              <option value="">Select Your Nearest Location</option>
              <?php foreach ($LOCATION_MAP as $slug => $label): ?>
                <option value="<?=$slug?>" <?= $locationSlug===$slug?'selected':'' ?>><?=$label?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="discount-banner">
          <strong> Special Offers!</strong> Get 10% off on Weekly cleaning and 5% off on Monthly cleaning!
        </div>
        <div class="actions">
          <button class="btn primary" name="go_step2" type="submit">Continue</button>
        </div>
      </form>

    <?php elseif ($step === 2): ?>
      <form method="post" class="card">
        <div class="row">
          <div>
            <label for="address_line1">Address Line</label>
            <input id="address_line1" name="address_line1" required placeholder="Street, unit, etc." value="<?=h($b['address_line1'] ?? '')?>">
          </div>
          <div>
            <label for="city_display">City</label>
            <input id="city_display" name="city_display" value="<?= h($cityLabel) ?>" readonly>
            <input type="hidden" id="city" name="city" value="<?= h($cityLabel) ?>">
          </div>
          <div>
            <label for="postcode">Postcode</label>
            <input id="postcode" name="postcode" required value="<?=h($b['postcode'] ?? '')?>">
          </div>
          <div>
            <label for="schedule">Schedule</label>
            <select id="schedule" name="schedule" required>
              <?php $sched = $b['schedule'] ?? 'One-time'; ?>
              <option<?= $sched==='One-time'?' selected':'' ?>>One-time</option>
              <option<?= $sched==='Weekly'?' selected':'' ?>>Weekly (10% OFF!)</option>
              <option<?= $sched==='Monthly'?' selected':'' ?>>Monthly (5% OFF!)</option>
            </select>
          </div>
          <div>
            <label for="date">Preferred Date</label>
            <input type="date" id="date" name="date" required value="<?=h($b['date'] ?? '')?>">
          </div>
        </div>
        <div class="actions">
          <a class="btn" href="?step=1">Back</a>
          <button class="btn primary" name="go_step3" type="submit">Continue</button>
        </div>
      </form>

    <?php elseif ($step === 3): ?>
      <form method="post" class="card">
        <div class="row">
          <div>
            <label>Your Name (Account)</label>
            <input value="<?=h($account['name'])?>" readonly>
          </div>
          <div>
            <label>Email (Account)</label>
            <input type="email" value="<?=h($account['email'])?>" readonly>
          </div>
          <div>
            <label for="number">Phone Number (Contact)</label>
            <input id="number" name="number" value="<?=h($b['number'] ?? $account['number'] ?? '')?>">
          </div>
          <div>
            <label for="payment_method">Payment Method</label>
            <select id="payment_method" name="payment_method" required>
              <?php $pm = $b['payment_method'] ?? 'Cash'; ?>
              <option<?= $pm==='Cash'?' selected':'' ?>>Cash</option>
              <option<?= $pm==='Online'?' selected':'' ?>>Online</option>
            </select>
          </div>
        </div>
        <div class="actions">
          <a class="btn" href="?step=2">Back</a>
          <button class="btn primary" name="go_step4" type="submit">Continue</button>
        </div>
      </form>

    <?php elseif ($step === 4): ?>
      <?php
        $meta     = service_meta($serviceSlug, $SERVICE_MAP);
        $service  = $meta['name'];
        $location = slug_to_label($locationSlug, $LOCATION_MAP, '—');
        $estimate = calc_total_price($serviceSlug, $b['schedule'] ?? 'One-time', $SERVICE_MAP);
        $err = isset($_GET['error']) ? $_GET['error'] : '';
      ?>
      <div class="card">
        <h3 class="review-title">Review your booking</h3>
        <?php if ($err): ?><p class="error">Error: <?=h($err)?></p><?php endif; ?>
        <div class="summary">
          <strong>Service     :</strong><div><?=h($service)?></div>
          <strong>Location    :</strong><div><?=h($location)?></div>
          <strong>Date        :</strong><div><?=h($b['date'] ?? '')?></div>
          <strong>Schedule    :</strong><div><?=h($b['schedule'] ?? '')?></div>
          <strong>Address     :</strong>
          <div class="muted">
            <?=h($b['address_line1'] ?? '')?><br>
            <?=h($b['postcode'] ?? '')?> <?=h($b['city'] ?? '')?>
          </div>
          <strong>Name (Account)     :</strong><div><?=h($account['name'])?></div>
          <strong>Email (Account)     :</strong><div><?=h($account['email'])?></div>
          <strong>Phone     :</strong><div><?=h($b['number'] ?? $account['number'] ?? '')?></div>
          <strong>Payment     :</strong><div><?=h($b['payment_method'] ?? '')?></div>
          <strong>Estimated Total     :</strong><div>RM <?=number_format($estimate, 2)?></div>
        </div>
        <form method="post" class="actions">
          <a class="btn" href="?step=3">Back</a>
          <button class="btn primary" type="submit" name="confirm_booking">Confirm Booking</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
