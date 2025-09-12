<?php
require __DIR__.'/includes/auth.php';
require __DIR__.'/../includes/db.php';
include __DIR__.'/includes/header.php';

$totalUsers     = (int)($pdo->query("SELECT COUNT(*) c FROM users")->fetch()['c'] ?? 0);
$totalBookings  = (int)($pdo->query("SELECT COUNT(*) c FROM bookings")->fetch()['c'] ?? 0);
$totalRevenue   = (float)($pdo->query("SELECT COALESCE(SUM(total_price),0) s FROM bookings")->fetch()['s'] ?? 0);

$recent = $pdo->query("
  SELECT b.id,b.date,b.schedule,b.payment_method,b.total_price,b.status,
         u.name user_name,s.name service_name
  FROM bookings b
  LEFT JOIN users u ON u.id=b.user_id
  LEFT JOIN services s ON s.id=b.service_id
  ORDER BY b.id DESC LIMIT 8
")->fetchAll();
?>
<div class="row">
  <div class="card" style="flex:1"><div>Users</div><h2><?= $totalUsers ?></h2></div>
  <div class="card" style="flex:1"><div>Bookings</div><h2><?= $totalBookings ?></h2></div>
  <div class="card" style="flex:1"><div>Total Revenue (RM)</div><h2><?= number_format($totalRevenue,2) ?></h2></div>
  <div class="card" style="flex:1"><div>Hello</div><h2><?= htmlspecialchars($_SESSION['admin_name']) ?></h2></div>
</div>

<div class="card">
  <h3>Recent Bookings</h3>
  <table class="table">
    <thead><tr><th>ID</th><th>Date</th><th>User</th><th>Service</th><th>Schedule</th><th>Payment</th><th>Total</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach($recent as $r): ?>
        <tr>
          <td><a href="bookings.php?id=<?= (int)$r['id'] ?>">#<?= (int)$r['id'] ?></a></td>
          <td><?= htmlspecialchars($r['date']) ?></td>
          <td><?= htmlspecialchars($r['user_name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($r['service_name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($r['schedule']) ?></td>
          <td><?= htmlspecialchars($r['payment_method']) ?></td>
          <td><?= number_format((float)$r['total_price'],2) ?></td>
          <td><span class="badge"><?= htmlspecialchars($r['status']) ?></span></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
