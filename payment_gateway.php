<?php
session_start();
if (empty($_SESSION['pending_payment'])) {
  header('Location: serviceDetails.php?step=4&error=' . urlencode('No payment session.'));
  exit;
}
$pending = $_SESSION['pending_payment'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Payment Gateway</title>
  <style>
    body { font-family: sans-serif; background:#f7fafc; padding:40px; }
    .card { max-width:500px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,.1); }
    .actions { margin-top:20px; display:flex; gap:10px; }
    .btn { padding:10px 16px; border:none; border-radius:8px; cursor:pointer; }
    .btn.success { background:#3B82F6; color:#fff; }
    .btn.cancel { background:#ddd; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Payment Gateway</h2>
    <p><strong>Amount:</strong> MYR <?= number_format($pending['total_price'], 2) ?></p>
    <form method="post" action="payment_return.php">
      <button class="btn success" type="submit" name="Status" value="1">Proceed (Success)</button>
      <button class="btn cancel" type="submit" name="Status" value="0">Cancel</button>
    </form>
  </div>
</body>
</html>
