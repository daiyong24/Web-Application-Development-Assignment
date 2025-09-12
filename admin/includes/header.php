<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin • Shine & Sparkle</title>
  <style>
    body{margin:0;font-family:system-ui,Segoe UI,Roboto; background:#0b1220; color:#e2e8f0}
    header{display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#0a0f1c;border-bottom:1px solid #1f2937}
    a{color:#38bdf8;text-decoration:none;margin-right:12px}
    .wrap{max-width:1100px;margin:0 auto;padding:20px}
    .card{background:#0f172a;border:1px solid #1f2937;border-radius:12px;padding:16px;margin-bottom:16px}
    .table{width:100%;border-collapse:collapse}
    .table th,.table td{padding:10px 12px;border-bottom:1px solid #1f2937;text-align:left}
    input,select,textarea{width:100%;padding:10px 12px;border-radius:8px;border:1px solid #334155;background:#0b1220;color:#e2e8f0}
    .row{display:flex;gap:12px;flex-wrap:wrap}
    .btn{background:#38bdf8;color:#0b1220;padding:8px 12px;border-radius:8px;border:0;font-weight:600;cursor:pointer}
    .btn.secondary{background:#334155;color:#e2e8f0}
    .btn.danger{background:#dc2626;color:#fff}
    .badge{padding:2px 8px;border-radius:12px;border:1px solid #334155}
  </style>
</head>
<body>
<header>
  <div>🧹 <strong>Admin</strong></div>
  <nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="bookings.php">Bookings</a>
    <a href="users.php">Users</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>
<div class="wrap">
