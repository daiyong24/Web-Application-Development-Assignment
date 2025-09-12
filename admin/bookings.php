<?php
require __DIR__.'/includes/auth.php';
require __DIR__.'/../includes/db.php';
require __DIR__.'/includes/csrf.php';
include __DIR__.'/includes/header.php';

$msg = '';

// update status
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='update_status') {
  csrf_check();
  $id = (int)($_POST['id']??0);
  $status = $_POST['status'] ?? 'Pending';
  $allowed = ['Pending','Confirmed','Completed','Cancelled'];
  if ($id>0 && in_array($status,$allowed,true)) {
    $stmt=$pdo->prepare("UPDATE bookings SET status=? WHERE id=?");
    $stmt->execute([$status,$id]);
    $msg = "Booking #$id updated.";
  }
}

// filters
$where=[]; $params=[];
if (!empty($_GET['q'])) { $where[]="(u.name LIKE ? OR s.name LIKE ? OR b.payment_method LIKE ?)"; $q='%'.$_GET['q'].'%'; $params=[$q,$q,$q]; }
if (!empty($_GET['status'])) { $where[]="b.status=?"; $params[]=$_GET['status']; }

$sql="SELECT b.*,u.name user_name,s.name service_name
      FROM bookings b
      LEFT JOIN users u ON u.id=b.user_id
      LEFT JOIN services s ON s.id=b.service_id";
if ($where) $sql.=" WHERE ".implode(" AND ",$where);
$sql.=" ORDER BY b.id DESC LIMIT 100";
$stmt=$pdo->prepare($sql); $stmt->execute($params);
$list=$stmt->fetchAll();

// detail
$detail=null;
if (!empty($_GET['id'])) {
  $d=$pdo->prepare("SELECT b.*,u.name user_name,u.email,u.number,s.name service_name
                    FROM bookings b
                    LEFT JOIN users u ON u.id=b.user_id
                    LEFT JOIN services s ON s.id=b.service_id
                    WHERE b.id=? LIMIT 1");
  $d->execute([(int)$_GET['id']]); $detail=$d->fetch();
}
?>
<div class="card">
  <h2>Bookings</h2>
  <?php if($msg): ?><div class="card" style="background:#0a0f1c"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <form method="get" class="row">
    <input name="q" placeholder="Search user/service/payment…" value="<?= htmlspecialchars($_GET['q']??'') ?>">
    <select name="status">
      <option value="">All statuses</option>
      <?php foreach(['Pending','Confirmed','Completed','Cancelled'] as $s): ?>
        <option value="<?= $s ?>" <?= (($_GET['status'] ?? '')===$s?'selected':'') ?>><?= $s ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn" type="submit">Filter</button>
    <a class="btn secondary" href="export_bookings.php">Export CSV</a>
  </form>
</div>

<?php if($detail): ?>
<div class="card">
  <h3>Booking #<?= (int)$detail['id'] ?></h3>
  <p><strong>User:</strong> <?= htmlspecialchars($detail['user_name']) ?> (<?= htmlspecialchars($detail['email']) ?>)</p>
  <p><strong>Service:</strong> <?= htmlspecialchars($detail['service_name']) ?></p>
  <p><strong>Schedule:</strong> <?= htmlspecialchars($detail['schedule']) ?> on <?= htmlspecialchars($detail['date']) ?></p>
  <p><strong>Payment:</strong> <?= htmlspecialchars($detail['payment_method']) ?></p>
  <p><strong>Total:</strong> RM <?= number_format((float)$detail['total_price'],2) ?></p>
  <p><strong>Status:</strong> <span class="badge"><?= htmlspecialchars($detail['status']) ?></span></p>
  <form method="post" class="row">
    <?php csrf_field(); ?>
    <input type="hidden" name="action" value="update_status">
    <input type="hidden" name="id" value="<?= (int)$detail['id'] ?>">
    <select name="status">
      <?php foreach(['Pending','Confirmed','Completed','Cancelled'] as $s): ?>
        <option value="<?= $s ?>" <?= ($detail['status']===$s?'selected':'') ?>><?= $s ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn" type="submit">Update Status</button>
  </form>
</div>
<?php endif; ?>

<div class="card">
  <h3>Latest 100</h3>
  <table class="table">
    <thead><tr><th>ID</th><th>Date</th><th>User</th><th>Service</th><th>Schedule</th><th>Payment</th><th>Total (RM)</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach($list as $r): ?>
      <tr>
        <td>#<?= (int)$r['id'] ?></td>
        <td><?= htmlspecialchars($r['date']) ?></td>
        <td><?= htmlspecialchars($r['user_name'] ?? '—') ?></td>
        <td><?= htmlspecialchars($r['service_name'] ?? '—') ?></td>
        <td><?= htmlspecialchars($r['schedule']) ?></td>
        <td><?= htmlspecialchars($r['payment_method']) ?></td>
        <td><?= number_format((float)$r['total_price'],2) ?></td>
        <td><span class="badge"><?= htmlspecialchars($r['status']) ?></span></td>
        <td><a class="btn secondary" href="?id=<?= (int)$r['id'] ?>">View</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
