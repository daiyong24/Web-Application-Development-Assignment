<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Gateway - iPay88</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body {
      background-color: #f7fafc;
      color: #333;
      line-height: 1.6;
      padding: 20px;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    .payment-container {
      max-width: 800px;
      width: 100%;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    
    .payment-header {
      background: #3B82F6;
      color: white;
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .logo i {
      font-size: 28px;
    }
    
    .logo h1 {
      font-size: 24px;
      font-weight: 600;
    }
    
    .secure-badge {
      background: rgba(255, 255, 255, 0.2);
      padding: 8px 15px;
      border-radius: 20px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    .payment-content {
      display: flex;
    }
    
    .payment-methods {
      width: 40%;
      background: #f8fafc;
      padding: 25px;
      border-right: 1px solid #e2e8f0;
    }
    
    .payment-form {
      width: 60%;
      padding: 25px;
    }
    
    .section-title {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 20px;
      color: #1e293b;
      padding-bottom: 10px;
      border-bottom: 2px solid #e2e8f0;
    }
    
    .method {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 12px;
      margin-bottom: 10px;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }
    
    .method:hover {
      background: #edf2f7;
    }
    
    .method.active {
      background: #ebf5ff;
      border: 1px solid #3B82F6;
    }
    
    .method-icon {
      width: 40px;
      height: 40px;
      background: white;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      font-size: 20px;
      color: #3B82F6;
    }
    
    .transaction-summary {
      background: #f8fafc;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 25px;
    }
    
    .summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 12px;
    }
    
    .summary-item.total {
      font-weight: 600;
      font-size: 18px;
      color: #3B82F6;
      border-top: 1px solid #e2e8f0;
      padding-top: 12px;
      margin-top: 8px;
    }
    
    .bank-selection {
      margin: 25px 0;
    }
    
    .bank-select {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 16px;
      margin-bottom: 15px;
      background: white;
    }
    
    .terms {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin: 20px 0;
      font-size: 14px;
      color: #64748b;
    }
    
    .timer {
      background: #fffbeb;
      border: 1px solid #fcd34d;
      border-radius: 8px;
      padding: 15px;
      text-align: center;
      margin: 20px 0;
      color: #92400e;
      font-weight: 500;
    }
    
    .action-buttons {
      display: flex;
      gap: 15px;
      margin-top: 25px;
    }
    
    .btn {
      flex: 1;
      padding: 15px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .btn-proceed {
      background: #3B82F6;
      color: white;
    }
    
    .btn-proceed:hover {
      background: #2563eb;
    }
    
    .btn-cancel {
      background: #f1f5f9;
      color: #64748b;
    }
    
    .btn-cancel:hover {
      background: #e2e8f0;
    }
    
    .footer {
      text-align: center;
      padding: 20px;
      background: #f8fafc;
      color: #64748b;
      font-size: 14px;
      border-top: 1px solid #e2e8f0;
    }
    
    .footer p {
      margin: 5px 0;
    }
    
    .footer a {
      color: #3B82F6;
      text-decoration: none;
    }
    
    .footer a:hover {
      text-decoration: underline;
    }
    
    .payment-details {
      display: none;
    }
    
    .payment-details.active {
      display: block;
    }
    
    .card-form {
      margin-top: 20px;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
      color: #374151;
    }
    
    .form-input {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 16px;
    }
    
    .form-row {
      display: flex;
      gap: 15px;
    }
    
    .form-row .form-group {
      flex: 1;
    }
    
    .ewallet-options {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }
    
    .ewallet-option {
      flex: 1;
      text-align: center;
      padding: 15px;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .ewallet-option:hover {
      border-color: #3B82F6;
      background: #f0f7ff;
    }
    
    .ewallet-option.active {
      border-color: #3B82F6;
      background: #ebf5ff;
    }
    
    .ewallet-icon {
      font-size: 32px;
      margin-bottom: 10px;
      color: #3B82F6;
    }
    
    @media (max-width: 768px) {
      .payment-content {
        flex-direction: column;
      }
      
      .payment-methods, .payment-form {
        width: 100%;
      }
      
      .payment-methods {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
      }
      
      .form-row {
        flex-direction: column;
        gap: 0;
      }
      
      .ewallet-options {
        flex-direction: column;
      }
    }
  </style>
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
          <div class="method-icon">
            <i class="fas fa-credit-card"></i>
          </div>
          <div class="method-info">
            <h3>Credit/Debit Card</h3>
            <p>Visa, Mastercard, AMEX</p>
          </div>
        </div>
        
        <div class="method" data-method="banking">
          <div class="method-icon">
            <i class="fas fa-building"></i>
          </div>
          <div class="method-info">
            <h3>Online Banking</h3>
            <p>FPX Transfer</p>
          </div>
        </div>
        
        <div class="method" data-method="ewallet">
          <div class="method-icon">
            <i class="fas fa-mobile-alt"></i>
          </div>
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
            <span>MYR 140.00</span>
          </div>
          
          <div class="summary-item">
            <span>Pay To</span>
            <span>Shine & Sparkle Cleaning</span>
          </div>
          
          <div class="summary-item">
            <span>Payment For</span>
            <span>Cleaning Service Booking #1234</span>
          </div>
          
          <div class="summary-item">
            <span>Reference No</span>
            <span>REF123456</span>
          </div>
          
          <div class="summary-item total">
            <span>Total Amount</span>
            <span>MYR 140.00</span>
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
        
        <!-- Online Banking Form -->
        <div class="payment-details" id="banking-details">
          <h2 class="section-title">Internet Banking</h2>
          
          <div class="bank-selection">
            <label>
              <input type="radio" name="account-type" checked> Individual Account
            </label>
            
            <select class="bank-select">
              <option value="">Select Bank</option>
              <option value="maybank">Maybank</option>
              <option value="cimb">CIMB Bank</option>
              <option value="public">Public Bank</option>
              <option value="rhb">RHB Bank</option>
              <option value="hongleong">Hong Leong Bank</option>
            </select>
            
            <div class="type-badge">
              <span>Type: <strong>FPX</strong></span>
            </div>
          </div>
        </div>
        
        <!-- eWallet Form -->
        <div class="payment-details" id="ewallet-details">
          <h2 class="section-title">eWallet</h2>
          
          <div class="ewallet-options">
            <div class="ewallet-option active">
              <div class="ewallet-icon">
                <i class="fab fa-cc-apple-pay"></i>
              </div>
              <div class="ewallet-name">GrabPay</div>
            </div>
            
            <div class="ewallet-option">
              <div class="ewallet-icon">
                <i class="fas fa-money-bill-wave"></i>
              </div>
              <div class="ewallet-name">Touch 'n Go</div>
            </div>
            
            <div class="ewallet-option">
              <div class="ewallet-icon">
                <i class="fab fa-google-pay"></i>
              </div>
              <div class="ewallet-name">Boost</div>
            </div>
          </div>
        </div>
        
        <div class="timer">
          <i class="fas fa-clock"></i> Timeout: <span id="countdown">07:57</span>
        </div>
        
        <div class="terms">
          <input type="checkbox" id="terms-agree" checked>
          <label for="terms-agree">By proceeding, you agree to the Terms and Conditions.</label>
        </div>
        
        <form class="action-buttons" method="post" action="payment_return.php">
          <button class="btn btn-proceed" type="submit" name="Status" value="1">
            <i class="fas fa-check-circle"></i> Proceed
          </button>
          <button class="btn btn-cancel" type="submit" name="Status" value="0">
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
    // Countdown timer
    function startTimer(duration, display) {
      let timer = duration, minutes, seconds;
      setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
          timer = duration;
        }
      }, 1000);
    }

    // Payment method selection
    const methods = document.querySelectorAll('.method');
    const paymentDetails = document.querySelectorAll('.payment-details');
    
    methods.forEach(method => {
      method.addEventListener('click', () => {
        // Remove active class from all methods and details
        methods.forEach(m => m.classList.remove('active'));
        paymentDetails.forEach(d => d.classList.remove('active'));
        
        // Add active class to clicked method
        method.classList.add('active');
        
        // Show corresponding payment details
        const methodType = method.getAttribute('data-method');
        document.getElementById(`${methodType}-details`).classList.add('active');
      });
    });

    // eWallet option selection
    const ewalletOptions = document.querySelectorAll('.ewallet-option');
    
    ewalletOptions.forEach(option => {
      option.addEventListener('click', () => {
        ewalletOptions.forEach(o => o.classList.remove('active'));
        option.classList.add('active');
      });
    });

    // Initialize on page load
    window.onload = function () {
      const fiveMinutes = 60 * 7 + 57; // 7 minutes 57 seconds
      const display = document.querySelector('#countdown');
      startTimer(fiveMinutes, display);
    };
  </script>
</body>
</html>