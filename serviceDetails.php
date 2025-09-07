<?php
  $service  = isset($_GET['service'])  ? htmlspecialchars($_GET['service'])  : 'Cleaning Service';
  $location = isset($_GET['location']) ? htmlspecialchars($_GET['location']) : 'Location';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Service Details - Shine & Sparkle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link type="text/css" rel="stylesheet" href="css/style.css" />
  <link type="text/css" rel="stylesheet" href="css/style-switcher.css" />
  <link type="text/css" rel="stylesheet" href="css/style-service-details.css" />



</head>
<body>
  <header class="details-header">
    <a class="back-btn" href="javascript:void(0)" onclick="history.back()">← Back</a>
    <div class="service-title">
      <h1><?php echo ucfirst(str_replace('-', ' ', $service)); ?></h1>
      <div class="sub"><?php echo ucfirst(str_replace('-', ' ', $location)); ?></div>
    </div>
  </header>

  <main style="max-width:1100px; margin:24px auto; padding:0 16px;">
    <p>Continue with service details, scheduling, and payment options here.</p>
  </main>
</body>
</html>
