<?php
require __DIR__.'/includes/auth.php';
require __DIR__.'/../includes/db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="bookings.csv"');

$out = fopen('php://output','w');
fputcsv($out,['ID','Date','User','Service','Schedule','Payment','Total','Status']);

$stmt=$pdo->query("
  SELECT b.id,b.date,u.name user_name,s.name service_name,b.schedule,b.payment_method,b.total_price,b.status
  FROM bookings b
  LEFT JOIN users u ON u.id=b.user_id
  LEFT JOIN services s ON s.id=b.service_id
  ORDER BY b.id DESC LIMIT 1000
");
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
  fputcsv($out, [$row['id'],$row['date'],$row['user_name'],$row['service_name'],$row['schedule'],$row['payment_method'],$row['total_price'],$row['status']]);
}
fclose($out);
