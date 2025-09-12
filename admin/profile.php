<?php
require __DIR__.'/includes/auth.php';
require __DIR__.'/../includes/db.php';
require __DIR__.'/includes/csrf.php';
include __DIR__.'/includes/header.php';

$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  csrf_check();
  $name=trim($_POST['name']??'');
  $pass=$_POST['password'] ?? '';
  $id=$_SESSION['admin_id'];
  if($name){
    if($pass){
      $hash=password_hash($pass,PASSWORD_DEFAULT);
      $pdo->prepare("UPDATE admins SET name=?, password=? WHERE id=?")->execute([$name,$hash,$id]);
    }else{
      $pdo->prepare("UPDATE admins SET name=? WHERE id=?")->execute([$name,$id]);
    }
    $_SESSION['admin_name']=$name; $msg='Profile updated.';
  } else $msg='Name is required.';
}
?>
<div class="card">
  <h2>My Profile</h2>
  <?php if($msg): ?><div class="card" style="background:#0a0f1c"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <form method="post">
    <?php csrf_field(); ?>
    <div class="row"><input name="name" value="<?= htmlspecialchars($_SESSION['admin_name']) ?>" required></div>
    <div class="row"><input type="password" name="password" placeholder="New password (optional)"></div>
    <button class="btn" type="submit">Save Changes</button>
  </form>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
