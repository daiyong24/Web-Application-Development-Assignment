<?php
// login.php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/includes/db.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';

  if ($email === '' || $pass === '') {
    $err = 'Please enter both email and password.';
  } else {
    $stmt = $pdo->prepare("SELECT id, name, email, number, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if (!$u || !password_verify($pass, $u['password'])) {
      $err = 'Invalid email or password.';
    } else {
      $_SESSION['user_id']   = $u['id'];
      $_SESSION['user_name'] = $u['name'];
      header('Location: index.php');
      exit;
    }
  }
}

// show success banner if redirected from register
$justRegistered = isset($_GET['registered']) && $_GET['registered'] === '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shine & Sparkle — Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <link type="text/css" rel="stylesheet" href="css/style-login.css" />
</head>
<body>

  <div class="wrap">
    <!-- Left: Logo + tagline -->
    <div class="brand">
      <div class="logo">Logo</div>
      <h1>Shine & Sparkle</h1>
      <p>Login to start your cleaning</p>
    </div>

    <!-- Right: Login card -->
    <div class="card">
      <h2>LOGIN</h2>

      <?php if ($justRegistered): ?>
        <div class="alert ok"><strong>Account created!</strong> You can log in now.</div>
      <?php endif; ?>

      <?php if ($err): ?>
        <div class="alert err"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>

      <form method="post" autocomplete="on">
        <div class="field">
          <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
          <label>Email</label>
          <input type="email" name="email" required>
        </div>

        <div class="field">
          <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
          <label>Password</label>
          <input type="password" name="password" required>
        </div>

        <div class="row" style="margin-top:6px;">
          <label><input type="checkbox" style="vertical-align:middle;"> Remember me</label>
          <a href="#" style="text-decoration:none;">Forgot Password?</a>
        </div>

        <button class="btn" type="submit">Log in</button>

        <div class="links">
          <span>Not account yet? <a href="register.php">Register here</a></span>
          <a href="index.php">Back to Home</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
