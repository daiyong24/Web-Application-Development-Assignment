<?php
session_start();
require __DIR__ . '/includes/db.php';

$data = json_decode($_POST['allData'], true);
$userId = $_SESSION['user_id'] ?? null;

if ($userId && $data) {
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, address_id, service_id, schedule, date, payment_method, total_price)
      VALUES (?, ?, ?, ?, ?, ?, ?)");

    // For now, dummy values for address_id/service_id
    $stmt->execute([$userId, 1, 1, $data['schedule'], $data['date'], $data['payment'], 0]);

    unset($_SESSION['moveinout']); // clear session
    echo "Booking confirmed!";
} else {
    echo "Error: missing booking data.";
}
