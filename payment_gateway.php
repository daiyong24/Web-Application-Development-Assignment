<<?php
// payment_gateway.php — dynamic iPay88-like page that shows the real amount
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/includes/db.php'; // if you don’t need DB here, you can remove this

// Make sure we have a pending payment (set in serviceDetails.php when payment method = Online)
if (empty($_SESSION['pending_payment']) || empty($_SESSION['user_id'])) {
  // No checkout data -> send user back to the service details flow
  header('Location: serviceDetails.php?step=4');
  exit;
}

$pending = $_SESSION['pending_payment'];
$b       = $pending['booking'] ?? [];        // contains: service, location, schedule, date, address_line1, postcode, city, number, payment_method
$amount  = (float)($pending['total_price'] ?? 0);

//Service name for display 
$SERVICE_NAME_MAP = [
  'house-cleaning'  => 'House Cleaning',
  'office-cleaning' => 'Office Cleaning',
  'airbnb-cleaning' => 'AirBnb Cleaning',
];

$serviceSlug = $b['service'] ?? '';
$serviceName = $SERVICE_NAME_MAP[$serviceSlug] ?? 'Cleaning Service';

//Simple reference we keep consistent in session 
if (empty($_SESSION['payment_ref'])) {
  $_SESSION['payment_ref'] = 'REF' . strtoupper(bin2hex(random_bytes(4)));
}
$reference = $_SESSION['payment_ref'];

//Safety guard: don’t render a RM0 page
if ($amount <= 0) {
  http_response_code(400);
  die('<p style="font:16px sans-serif">Invalid amount. Please go back and reselect your service.</p>');
}

function money_my($v) { return 'MYR ' . number_format((float)$v, 2); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Gateway - iPay88</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link type="text/css" rel="stylesheet" href="css/style-payment.css" />
</head>
<body>
  <div class="payment-container">
    <div class="payment-header">
      <div class="logo">
        <i class="fas fa-shield-alt"></i>
        <h1>iPay88 Payment Gateway</h1>
      </div>
      <div class="secure-badge">
        <i class="fas fa-lock"></i>
        <span>Secure Payment</span>
      </div>
    </div>

    <div class="payment-content">
      <div class="payment-methods">
        <h2 class="section-title">Available Payment Methods</h2>

        <div class="method active" data-method="card">
          <div class="method-icon"><i class="fas fa-credit-card"></i></div>
          <div class="method-info">
            <h3>Credit/Debit Card</h3>
            <p>Visa, Mastercard, AMEX</p>
          </div>
        </div>

        <div class="method" data-method="banking">
          <div class="method-icon"><i class="fas fa-building"></i></div>
          <div class="method-info">
            <h3>Online Banking</h3>
            <p>FPX Transfer</p>
          </div>
        </div>

        <div class="method" data-method="ewallet">
          <div class="method-icon"><i class="fas fa-mobile-alt"></i></div>
          <div class="method-info">
            <h3>eWallet</h3>
            <p>GrabPay, Touch 'n Go</p>
          </div>
        </div>
      </div>

      <div class="payment-form">
        <h2 class="section-title">Transaction Details</h2>

        <div class="transaction-summary">
          <div class="summary-item">
            <span>Net Charges</span>
            <span><?= money_my($amount) ?></span>
          </div>

          <div class="summary-item">
            <span>Pay To</span>
            <span>Shine &amp; Sparkle Cleaning</span>
          </div>

          <div class="summary-item">
            <span>Payment For</span>
            <span><?= htmlspecialchars($serviceName) ?> Booking</span>
          </div>

          <div class="summary-item">
            <span>Reference No</span>
            <span><?= htmlspecialchars($reference) ?></span>
          </div>

          <div class="summary-item total">
            <span>Total Amount</span>
            <span><?= money_my($amount) ?></span>
          </div>
        </div>

        <!-- Credit/Debit Card Form -->
        <div class="payment-details active" id="card-details">
          <h2 class="section-title">Credit/Debit Card</h2>
          <div class="card-form">
            <div class="form-group">
              <label class="form-label">Card Number</label>
              <input type="text" class="form-input" placeholder="1234 5678 9012 3456">
            </div>
            <div class="form-group">
              <label class="form-label">Cardholder Name</label>
              <input type="text" class="form-input" placeholder="John Doe">
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Expiry Date</label>
                <input type="text" class="form-input" placeholder="MM/YY">
              </div>
              <div class="form-group">
                <label class="form-label">CVV</label>
                <input type="text" class="form-input" placeholder="123">
              </div>
            </div>
          </div>
        </div>

        <!-- Online Banking -->
        <div class="payment-details" id="banking-details">
          <h2 class="section-title">Internet Banking</h2>
          <div class="bank-selection">
            <label><input type="radio" name="account-type" checked> Individual Account</label>
            <select class="bank-select">
              <option value="">Select Bank</option>
              <option>Maybank</option><option>CIMB Bank</option><option>Public Bank</option>
              <option>RHB Bank</option><option>Hong Leong Bank</option>
            </select>
            <div class="type-badge"><span>Type: <strong>FPX</strong></span></div>
          </div>
        </div>

        <!-- eWallet -->
        <div class="payment-details" id="ewallet-details">
          <h2 class="section-title">eWallet</h2>
          <div class="ewallet-options">
            <div class="ewallet-option active">
              <div class="ewallet-icon"><i class="fab fa-cc-apple-pay"></i></div>
              <div class="ewallet-name">GrabPay</div>
            </div>
            <div class="ewallet-option">
              <div class="ewallet-icon"><i class="fas fa-money-bill-wave"></i></div>
              <div class="ewallet-name">Touch 'n Go</div>
            </div>
            <div class="ewallet-option">
              <div class="ewallet-icon"><i class="fab fa-google-pay"></i></div>
              <div class="ewallet-name">Boost</div>
            </div>
          </div>
        </div>

        <div class="timer"><i class="fas fa-clock"></i> Timeout: <span id="countdown">07:57</span></div>

        <div class="terms">
          <input type="checkbox" id="terms-agree" checked>
          <label for="terms-agree">By proceeding, you agree to the Terms and Conditions.</label>
        </div>

        <!-- Submit to your return handler -->
        <form class="action-buttons" method="post" action="includes/payment_return.php">
          <!-- Minimal context. Do NOT trust these blindly; verify server-side -->
          <input type="hidden" name="Status" value="1">
          <input type="hidden" name="reference" value="<?= htmlspecialchars($reference) ?>">
          <input type="hidden" name="amount" value="<?= number_format($amount, 2, '.', '') ?>">
          <input type="hidden" name="service_slug" value="<?= htmlspecialchars($serviceSlug) ?>">
          <!-- You can also pass schedule/date if you want, but you already have them in session -->
          <button class="btn btn-proceed" type="submit">
            <i class="fas fa-check-circle"></i> Proceed
          </button>
          <button class="btn btn-cancel" type="button" onclick="window.location.href='serviceDetails.php?step=4'">
            <i class="fas fa-times-circle"></i> Cancel
          </button>
        </form>
      </div>
    </div>

    <div class="footer">
      <p>IPay88.com © 2006 - 2023. All Rights Reserved.</p>
      <p>Customer Careline: <a href="tel:+6012345678">+60-3-2261 4668</a>, 8.30 am - 6.00 pm (Mon - Fri)</p>
      <p>Email: <a href="mailto:support@ipay88.com.my">support@ipay88.com.my</a></p>
    </div>
  </div>

  <script>
    function startTimer(duration, display){
      let timer=duration, m, s;
      setInterval(function(){
        m=parseInt(timer/60,10); s=parseInt(timer%60,10);
        m=m<10?"0"+m:m; s=s<10?"0"+s:s;
        display.textContent=m+":"+s;
        if(--timer<0) timer=duration;
      },1000);
    }
    const methods=document.querySelectorAll('.method');
    const details=document.querySelectorAll('.payment-details');
    methods.forEach(method=>{
      method.addEventListener('click',()=>{
        methods.forEach(m=>m.classList.remove('active'));
        details.forEach(d=>d.classList.remove('active'));
        method.classList.add('active');
        const t=method.getAttribute('data-method');
        document.getElementById(`${t}-details`).classList.add('active');
      });
    });
    const ews=document.querySelectorAll('.ewallet-option');
    ews.forEach(o=>o.addEventListener('click',()=>{ews.forEach(x=>x.classList.remove('active'));o.classList.add('active');}));
    window.onload=function(){const d=document.querySelector('#countdown');startTimer(60*7+57,d);}
  </script>
</body>
</html>