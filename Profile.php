<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/db.php';

// 1) Load current user
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, name, email, number, password, updated_at FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
  die("User not found.");
}

$errors = [];
$success = "";

// 2) Handle profile save (name/phone)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
  $name   = trim($_POST['name'] ?? '');
  $number = trim($_POST['number'] ?? '');

  if ($name === '')   $errors[] = "Full name is required.";
  if ($number === '') $errors[] = "Phone number is required.";

  if (!$errors) {
    $stmt = $pdo->prepare("UPDATE users SET name = ?, number = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$name, $number, $userId]);

    $success = "Profile updated.";
    // refresh user data
    $stmt = $pdo->prepare("SELECT id, name, email, number, password, updated_at FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
  }
}

// 3) Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
  $old = $_POST['old_password'] ?? '';
  $new = $_POST['new_password'] ?? '';

  if ($old === '' || $new === '') {
    $errors[] = "Both existing and new password are required.";
  } elseif (!password_verify($old, $user['password'])) {
    $errors[] = "Existing password is incorrect.";
  } elseif (strlen($new) < 6) {
    $errors[] = "New password must be at least 6 characters.";
  }

  if (!$errors) {
    $hash = password_hash($new, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$hash, $userId]);

    $success = "Password changed.";
    // refresh user data
    $stmt = $pdo->prepare("SELECT id, name, email, number, password, updated_at FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
  }
}

// Format last updated
$lastUpdated = $user['updated_at'] ? date('Y-m-d H:i:s', strtotime($user['updated_at'])) : '-';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Shine & Sparkle — Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Ionicons -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <link type="text/css" rel="stylesheet" href="css/style.css" />
  <link type="text/css" rel="stylesheet" href="css/style-profile.css" />
</head>
<body class="profile-page">
<?php include('components/header.php');?>
<div class="profile-wrap container">
  <div class="profile-card">
    <!-- header -->
    <div class="profile-header">
      <div style="position:absolute; top:28px; width:100%; text-align:center;">
        <div class="header-name"><?= htmlspecialchars($user['name']) ?></div>
      </div>
      <div class="avatar"><div class="circle"><ion-icon name="person-outline"></ion-icon></div></div>
    </div>

    <div class="profile-content">

      <?php if ($success): ?>
        <div class="flash success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>
      <?php if ($errors): ?>
        <div class="flash error">
          <?php foreach ($errors as $e) echo "<div>".htmlspecialchars($e)."</div>"; ?>
        </div>
      <?php endif; ?>

      <!-- Profile -->
      <form method="post" class="section">
        <h3>User Profile</h3>
        <div class="fields grid-2">

          <label class="field">
            <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly />
            <span class="trailing" title="Email cannot be changed"><ion-icon name="lock-closed-outline"></ion-icon></span>
          </label>

          <label class="field">
            <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
            <input id="name" name="name" type="text" value="<?= htmlspecialchars($user['name']) ?>" />
            <span class="trailing"><button type="button" onclick="document.getElementById('name').focus()"><ion-icon name="create-outline"></ion-icon></button></span>
          </label>

          <label class="field">
            <span class="icon"><ion-icon name="call-outline"></ion-icon></span>
            <input id="number" name="number" type="tel" value="<?= htmlspecialchars($user['number']) ?>" />
            <span class="trailing"><button type="button" onclick="document.getElementById('number').focus()"><ion-icon name="create-outline"></ion-icon></button></span>
          </label>

          <div style="grid-column:1/-1; display:flex; justify-content:flex-end; gap:10px;">
            <button class="btn secondary" type="reset">Reset</button>
            <button class="btn primary" type="submit" name="save_profile">
              <ion-icon name="save-outline"></ion-icon> Save changes
            </button>
          </div>
        </div>
      </form>

      <!-- Password -->
      <form method="post" class="section" autocomplete="off">
        <h3>User Password</h3>
        <div class="fields">

          <label class="field">
            <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
            <input id="oldpass" name="old_password" type="password" placeholder="Existing Password" />
            <span class="trailing"><button type="button" onclick="toggle('oldpass')"><ion-icon name="eye-outline"></ion-icon></button></span>
          </label>

          <label class="field">
            <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
            <input id="newpass" name="new_password" type="password" placeholder="New Password" />
            <span class="trailing"><button type="button" onclick="toggle('newpass')"><ion-icon name="eye-outline"></ion-icon></button></span>
          </label>

          <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button class="btn secondary" type="reset">Clear</button>
            <button class="btn primary" type="submit" name="change_password">
              <ion-icon name="key-outline"></ion-icon> Change password
            </button>
          </div>
        </div>
      </form>

      <!-- Footer -->
      <div class="profile-footer">
        <div class="note">Last Updated : <strong><?= htmlspecialchars($lastUpdated) ?></strong></div>
        <div>
          <button class="btn secondary" type="button" onclick="location.href='index.php'">Close</button>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  function toggle(id){
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
  }
</script>
<script src="js/script.js"></script>
<?php include('components/footer.php');?>
</body>
</html>
