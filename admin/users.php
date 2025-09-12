<?php
require __DIR__.'/includes/auth.php';
require __DIR__.'/../includes/db.php';
include __DIR__.'/includes/header.php';

$q = '%'.($_GET['q']??'').'%';
$stmt=$pdo->prepare("SELECT id,name,email,number FROM users WHERE name LIKE ? OR email LIKE ? OR number LIKE ? ORDER BY id DESC LIMIT 200");
$stmt->execute([$q,$q,$q]);
$list=$stmt->fetchAll();
?>
<div class="card">
  <h2>Users</h2>
  <form method="get" class="row">
    <input name="q" placeholder="Search name/email/phone…" value="<?= htmlspecialchars($_GET['q']??'') ?>">
    <button class="btn" type="submit">Search</button>
  </form>
</div>
<div class="card">
  <table class="table">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th></tr></thead>
    <tbody>
      <?php foreach($list as $u): ?>
        <tr>
          <td>#<?= (int)$u['id'] ?></td>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['number']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
