<?php
session_start();
require_once 'includes/db.php';

$success = '';
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $purpose = trim($_POST['purpose']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($contact) || empty($email) || empty($purpose) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, contact_number, email, purpose, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $contact, $email, $purpose, $message]);
            
            $success = "Your message has been sent successfully! We'll get back to you soon.";
            
          
            $_POST = array();
            
        } catch (PDOException $e) {
            $error = "Sorry, there was an error sending your message. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Shine & Sparkle - Contact Us</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE-edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link type="text/css" rel="stylesheet" href="css/style.css" />
  <link type="text/css" rel="stylesheet" href="css/style-switcher.css" />
  <link type="text/css" rel="stylesheet" href="css/style-inform.css" />
  <style>
    .alert {
      padding: 15px;
      margin: 20px 0;
      border-radius: 5px;
      text-align: center;
    }
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .alert-error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>
<body>

  <!-- header start -->
  <?php include 'components/header.php'; ?>
  <!-- header end -->

  
  <!-- Contact Section Start -->
<section class="section-padding" id="team-contact">
  <div class="container">
    <div class="section-title">
      <h2 class="sub-title"><br><u>CONTACT OUR TEAM</u></br></h2>
    </div>

    <!-- Team Phone Number Grid -->
    <div class="grid">

      <div class="contact-number">
        <img src="images/officeCleaning-img.jpg" alt="Office Cleaning">
        <span>012-0120120</span>
      </div>

      <div class="contact-number">
        <img src="images/houseCleaning-img.jpg" alt="House Cleaning">
        <span>012-3456789</span>
      </div>

      <div class="contact-number">
        <img src="images/moveInmoveOut-img.jpg" alt="Move In/Out">
        <span>012-6789012</span>
      </div>

      <div class="contact-number">
        <img src="images/airbnbCleaning1-img.jpg" alt="Airbnb Cleaning">
        <span>012-9012345</span>
      </div>

    </div>
  </div>
</section>
    <!-- Contact section ends-->

   <!-- Contact Form section start -->
    <section class="section-padding" id="contact-form" style="display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 10px;">
    
            <div class="section-title">
                <h2 class="sub-title"><u>GET IN TOUCH</u></h2>
            </div>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <h2 style="text-align: center; margin-bottom: 30px;">We are looking forward to helping you with enquiries.</h2>
            <div class="contact-form">
                <form method="post" action="">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>

                    <label for="contact">Contact Number</label>
                    <input type="tel" name="contact" id="contact" value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>" required>

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>

                    <label for="purpose">Purpose of Inquiry</label>
                    <input type="text" name="purpose" id="purpose" value="<?php echo isset($_POST['purpose']) ? htmlspecialchars($_POST['purpose']) : ''; ?>" required>

                    <label for="message">Special Request</label>
                    <textarea id="message" name="message" rows="4" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    
                    <button type="submit" name="submit_contact">Send Form Details</button>
                </form>
            </div>
    
    </section>
    <!-- Contact Form section ends -->


<script src="js/script.js"></script>
<script src="js/style-switcher.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

<?php include 'components/footer.php'; ?>
</body>
</html>