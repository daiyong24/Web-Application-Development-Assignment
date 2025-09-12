<?php
require __DIR__.'/includes/auth.php';
require __DIR__.'/../includes/db.php';
require __DIR__.'/includes/csrf.php';
include __DIR__.'/includes/header.php';

if (($_SESSION['admin_role'] ?? 'admin') !== 'superadmin') {
  echo '<div class="card"><strong>Only superadmin can manage admins.</strong></div>';
  include __DIR__.'/includes/footer.php'; exit;
}

$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  csrf_check();
  $id=(int)($_POST['id']??0);
  $name=trim($_POST['name']??'');
  $email=trim($_POST['email']??'');
  $role=$_POST['role'] ?? 'admin';
  $pass=$_POST['password'] ?? '';
  if($id>0){
    if($name && $email){
      if($pass){
        $hash=password_hash($pass,PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE admins SET name=?,email=?,role=?,password=? WHERE id=?")->execute([$name,$email,$role,$hash,$id]);
      }else{
        $pdo->prepare("UPDATE admins SET name=?,email=?,role=? WHERE id=?")->execute([$name,$email,$role,$id]);
      }
      $msg='Admin updated.';
    } else $msg='Name & email required.';
  } else {
    if($name && $email && $pass){
      $hash=password_hash($pass,PASSWORD_DEFAULT);
      $pdo->prepare("INSERT INTO admins (name,email,role,password) VALUES (?,?,?,?)")->execute([$name,$email,$role,$hash]);
      $msg='Admin created.';
    } else $msg='All fields required for create.';
  }
}
if(!empty($_GET['delete'])){
  $pdo->prepare("DELETE FROM admins WHERE id=?")->execute([(int)$_GET['delete']]);
  $msg='Admin deleted.';
}
$edit=null;
if(!empty($_GET['id'])){ $s=$pdo->prepare("SELECT * FROM admins WHERE id=?"); $s->execute([(int)$_GET['id']]); $edit=$s->fetch(); }
$list=$pdo->query("SELECT id,name,email,role FROM admins ORDER BY id DESC")->fetchAll();
?>
<div class="card">
  <h2>Admins</h2>
  <?php if($msg): ?><div class="card" style="background:#0a0f1c"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <form method="post" class="card">
    <?php csrf_field(); ?>
    <input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>">
    <div class="row">
      <input name="name" placeholder="Full name" value="<?= htmlspecialchars($edit['name']??'') ?>" required>
      <input name="email" type="email" placeholder="Email" value="<?= htmlspecialchars($edit['email']??'') ?>" required>
      <select name="role">
        <?php foreach(['admin','superadmin'] as $r): ?>
          <option value="<?= $r ?>" <?= (($edit['role']??'')===$r?'selected':'') ?>><?= ucfirst($r) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="row">
      <input name="password" type="password" placeholder="<?= $edit?'New password (optional)':'Password' ?>">
    </div>
    <button class="btn" type="submit"><?= $edit?'Update Admin':'Create Admin' ?></button>
    <?php if($edit): ?><a class="btn secondary" href="admins.php">Cancel</a><?php endif; ?>
  </form>
</div>
<div class="card">
  <h3>All Admins</h3>
  <table class="table">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th></th></tr></thead>
    <tbody>
      <?php foreach($list as $a): ?>
        <tr>
          <td>#<?= (int)$a['id'] ?></td>
          <td><?= htmlspecialchars($a['name']) ?></td>
          <td><?= htmlspecialchars($a['email']) ?></td>
          <td><span class="badge"><?= htmlspecialchars($a['role']) ?></span></td>
          <td>
            <a class="btn secondary" href="?id=<?= (int)$a['id'] ?>">Edit</a>
            <a class="btn danger" href="?delete=<?= (int)$a['id'] ?>" onclick="return confirm('Delete this admin?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
