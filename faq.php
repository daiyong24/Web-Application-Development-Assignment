<!DOCTYPE html>
<html lang="en">
<head>
  <title>Shine & Sparkle - FAQ</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE-edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link type="text/css" rel="stylesheet" href="css/style.css" />
  <link type="text/css" rel="stylesheet" href="css/style-switcher.css" />

    <style>
        .faq-items{
            border-bottom: 1px solid grey;
            padding: 10px 0;
        }

        .faq-question{
            border: none;
            width: 100%;
            font-size: 1.2rem;
            text-align: left;
            color: darkcyan;
            cursor: pointer;
            display: flex;
            padding: 30px;
            align-items: center;
            justify-content: space-between;
        }

        .icon{
            font-size: 1.5rem;
        }

        .faq-answer{
            padding: 10px;
            padding-bottom: 0%;
            display: none;
        }

        .faq-question.active.icon{
            content: "-";
        }
    </style>

</head>
<body>

    <!-- header start -->
  <header class="header js-header">
    <div class="container">
      <div class="logo">
        <a href="index.html">Shine <span>& Sparkle</span></a>
      </div>
      <button type="button" class="nav-toggler js-nav-toggler">
        <span></span>
      </button>
      <nav class="nav js-nav">
        <ul>
            <li><a href="index.html">HOME</a></li>
            <li class="dropdown">
              <a href="javascript:void(0);" class="dropbtn">
                INFO <ion-icon name="caret-down-outline"></ion-icon>
              </a>  
              <ul class="sub-menu-1">
                  <li><a href="aboutus.php">About Us</a></li>
                  <li><a href="contact.html">Contact Us</a></li>
                  <li><a href="faq.php">FAQ</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="javascript:void(0);" class="dropbtn"> 
                SERVICE INQUIRIES <ion-icon name="caret-down-outline"></ion-icon> 
              </a> 
              <ul class="sub-menu-1"> 
                <li><a href="#office-cleaning">Office Cleaning</a></li> 
                <li><a href="#house-cleaning">House Cleaning</a></li> 
                <li><a href="#move-in-out">Move In / Move Out</a></li> 
                <li><a href="#airbnb-cleaning">Airbnb Cleaning</a></li> 
              </ul> 
            </li>
            <li class="dropdown">
              <a href="javascript:void(0);" class="dropbtn">
                <ion-icon name="person-circle-outline"></ion-icon>
                PROFILE <ion-icon name="caret-down-outline"></ion-icon>  
              </a>
              <ul class="sub-menu-1">
                <li><a href="#">Edit Profile</a></li>
                <li><a href="#">Booking History</a></li>
                <li><a href="#">Translation History</a></li>
              </ul>
            </li>
            <li><a href="#wallet">WALLET</a></li>
            <li><button class="btnLogin-popup">Login</button></li>
        </ul>
      </nav>
    </div>
  </header>
  <!-- header end -->

  <!-- FAQ section starts -->
  <section class="section-padding">
    <div class="container">
        <div class="section-title">
            <h1 style="margin: 1.5rem; letter-spacing: 3px;">Frequently Asked Questions</h1>
            <h3>Have a read through our FAQs for house cleaning services or contact us for help.</h3>
        </div>

        <div class="faq-items">
            <button class="faq-question">
                Do I need to provide cleaning supplies and equipment?
                <span class="icon">+</span>   
            </button>
            <div class="faq-answer">
                <p>A: No, our team brings all the necessary cleaning products and equipment. 
                    We also use eco-friendly options to ensure safety for your family, pets, 
                    and the environment.</p>
            </div>
        </div>

        <div class="faq-items">
            <button class="faq-question">
                How long will the cleaning take?
                <span class="icon">+</span>   
            </button>
            <div class="faq-answer">
                <p>A: The duration depends on the size of your space and the type of service. 
                    For example, a standard apartment cleaning usually takes 2 to 3 hours.</p>
            </div>
        </div>

        <div class="faq-items">
            <button class="faq-question">
                What should I do before the cleaners arrive?
                <span class="icon">+</span>   
            </button>
            <div class="faq-answer">
                <p>A: To help our team work efficiently, we recommend tidying up personal 
                    belongings and making sure fragile items are safely stored away. 
                    We'll handle the rest!</p>
            </div>
        </div>

        <div class="faq-items">
            <button class="faq-question">
                Can I request the same cleaner each time?
                <span class="icon">+</span>   
            </button>
            <div class="faq-answer">
                <p>A: Yes, we'll do our best to assign the same team for 
                    recurring customers to ensure consistency and comfort.</p>
            </div>
        </div>

    </div>
  </section>
  <!-- FAQ section ended -->

  <div class="wrapper">
        <span class="icon-close">
          <ion-icon name="close"></ion-icon>
        </span>

        <div class="form-box login">
          <h2>LOGIN</h2>
          <form action="#"></form>
            <div class="input-box">
              <span class="icon">
                <ion-icon name="mail"></ion-icon>
              </span>
              <input type="email" required></input>
              <label>Email</label>
            </div>
            <div class="input-box">
              <span class="icon">
                <ion-icon name="lock-closed"></ion-icon>
              </span>
              <input type="password" required></input>
              <label>Password</label>
            </div>
            <div class="remember-forgot">
              <label><input type="checkbox">Remember me</label>
              <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" class="btn">Login</button>
            <div class="login-register">
              <p>Don't have an account? <a href="#"
              class="register-link">Register</a></p>
            </div>
          </form>
        </div>

        <div class="form-box register">
          <h2>REGISTRATION</h2>
          <form action="#">
            <div class="input-box">
              <span class="icon">
                <ion-icon name="person"></ion-icon>
              </span>
              <input type="text" required></input>
              <label>Username</label>
            </div>
            <div class="input-box">
              <span class="icon">
                <ion-icon name="mail"></ion-icon>
              </span>
              <input type="email" required></input>
              <label>Email</label>
            </div>
            <div class="input-box">
              <span class="icon">
                <ion-icon name="lock-closed"></ion-icon>
              </span>
              <input type="password" required></input>
              <label>Password</label>
            </div>
            <div class="remember-forgot">
              <label><input type="checkbox">I agree to the terms & conditions</label>
            </div>
            <button type="submit" class="btn">Register</button>
            <div class="login-register">
              <p>Already have an account? <a href="#"
              class="login-link">Login</a></p>
            </div>
          </form>
        </div>
  </div>


  
  <script>
    document.addEventListener("DOMContentLoaded", () =>
    {
        const questions =
        document.querySelectorAll(".faq-question");

        questions.forEach(question => {
            question.addEventListener("click", ()=>{
                question.classList.toggle("active");
                const answer = question.nextElementSibling;
                const icon = question.querySelector(".icon");

                if(answer.style.display == "block"){
                    answer.style.display = "none";
                    icon.textContent = "+";
                } else {
                    answer.style.display = "block";
                    icon.textContent = "-";
                }
            })
        })
    })
  </script>


   <script src="js/script.js"></script>
   <script src="js/style-switcher.js"></script>
   <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
   <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<?php include 'components/footer.php'; ?>
</body>
</html>