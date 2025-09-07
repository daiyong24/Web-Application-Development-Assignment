<?php
// register.php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/includes/db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $name  = trim($_POST['name'] ?? '');
  $pass1 = $_POST['password'] ?? '';
  $pass2 = $_POST['confirm'] ?? '';
  $phone = trim($_POST['number'] ?? '');

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
  if ($name === '')                                $errors[] = 'Please enter your name.';
  if (strlen($pass1) < 6)                          $errors[] = 'Password must be at least 6 characters.';
  if ($pass1 !== $pass2)                           $errors[] = 'Passwords do not match.';
  if ($phone === '')                               $errors[] = 'Please enter your mobile number.';

  // unique email check
  if (!$errors) {
    $stmt = $pdo->prepare("SELECT 1 FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) $errors[] = 'Email is already registered.';
  }

  if (!$errors) {
    $hash = password_hash($pass1, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, number, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $hash]);
    // Success -> redirect to login with flag
    header('Location: login.php?registered=1');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shine & Sparkle — Sign Up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <link type="text/css" rel="stylesheet" href="css/style-register.css" />
</head>
<body>

  <div class="wrap">
    <div class="logo">Logo</div>
    <h1>Shine & Sparkle</h1>

    <div class="card">
      <h2>Create Account</h2>

      <?php if ($errors): ?>
        <div class="alert err">
          <?php foreach ($errors as $e) echo "<div>".htmlspecialchars($e)."</div>"; ?>
        </div>
      <?php endif; ?>

      <form method="post" autocomplete="on">
        <div class="field">
          <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
          <label>Email</label>
          <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>

        <div class="field">
          <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
          <label>Your Name</label>
          <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="field">
          <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
          <label>Password</label>
          <input type="password" name="password" required>
        </div>

        <div class="field">
          <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
          <label>Confirm Password</label>
          <input type="password" name="confirm" required>
        </div>

        <div class="field">
          <span class="icon"><ion-icon name="call-outline"></ion-icon></span>
          <label>Mobile No</label>
          <input type="tel" name="number" required value="<?= htmlspecialchars($_POST['number'] ?? '') ?>">
        </div>

        <button class="btn" type="submit">Create Account</button>

        <div class="links">
          <a href="login.php">Back to Login</a>
          <a href="index.php">Back to Home</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
