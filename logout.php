<?php
session_start();
session_unset();
session_destroy();

// Redirect to homepage (or login page if you prefer)
header("Location: index.php");
exit;
