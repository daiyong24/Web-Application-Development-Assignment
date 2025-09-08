<?php
session_start();

if (empty($_SESSION['pending_payment'])) {
  header('Location: serviceDetails.php?step=4&error=' . urlencode('No payment session.'));
  exit;
}

$status = $_POST['Status'] ?? '0';
$pending = $_SESSION['pending_payment'];

if ($status === '1') {
  // Success → mark as receipt and clear booking
  $_SESSION['receipt'] = $pending['booking'];
  $_SESSION['receipt']['total_price'] = $pending['total_price'];
  $_SESSION['booking'] = [];
  unset($_SESSION['pending_payment']);

  header('Location: serviceDetails.php?success=1');
  exit;
} else {
  // Cancel → go back to review step
  unset($_SESSION['pending_payment']);
  header('Location: serviceDetails.php?step=4&error=' . urlencode('Payment cancelled.'));
  exit;
}
