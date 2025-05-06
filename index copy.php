<?php
require_once('config/connection.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NagarYatra</title>
  <!-- <link rel="icon" type="image/png" sizes="64x64" href="assets/logo1.png" /> -->
  <link rel="icon" href="assets/logo1.png" type="image/png">
  <link rel="shortcut icon" href="assets/logo1.png" type="image/png">

  <link rel="stylesheet" href="design.css" />
  <!-- owl crousal cdn -->
  <!-- Owl Carousel CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Owl Carousel JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
</head>

<body>
  <div class="main_container">
    <nav>
      <div class="logo">
        <img src="assets/logo1.png" alt="Logo" />
        NagarYatra
      </div>
      <div class="nav-links">
        <a href="#home">Home</a>
        <a href="#about">About</a>
        <a href="#services">Services</a>
        <a href="#testimonial">Testimonial</a>
        <a href="#contactus">Contact Us</a>
      </div>
      <div class="nav-buttons">
        <a href="user/book_ride.php">
          <button>Book Ride</button>
        </a>
        <a href="register.php">
          <button>Sign Up</button>
        </a>
      </div>
      <!-- </div> -->
    </nav>
    <div class="container" id="home">
      <div class="welcome-text">WELCOME TO NagarYatra</div>
      <div class="welcome-image">
        <img src="auto.png" alt="Welcome Image" />
      </div>
    </div>
  </div>

  <!-- About Us -->
  <div class="main_container" id="about" style="padding:15px;">
    <h1 class="heading1">About us</h1>
    <p
      style="text-align: center; font-size: 22px; font-weight: 600; max-width: 800px; margin: auto;padding-bottom:20px;">
      NagarYatra is a
      modern vehicle booking platform dedicated to providing hassle-free and affordable ride solutions across Nepal.
      Whether you're traveling for work or leisure, we connect you to the best vehicles and trusted drivers in minutes.
    </p>
  </div>

  <!-- Services -->
  <div class="main_container" id="service">
    <h1 class="heading1">Services</h1>
    <div class="testimonial-section" style="
    padding-left: 112px;">
      <h2 class="heading2">What Our Services Are</h2>
      <div class="owl-carousel owl-theme">
        <div class="item testimonial-card">
          <img src="assets/logo2.png" alt="Customer 2" class="testimonial-image" style="width: 100px;
              height: 100px;
              border-radius: 50%;
              margin-bottom: 15px;
              background-color: white;
              text-align:center;">
          <h4>City Rides</h4>
          <p>Comfortable and quick rides across town.</p>
        </div>

        <div class="item testimonial-card">
          <img src="assets/logo2.png" alt="Customer 2" class="testimonial-image" style="width: 100px;
              height: 100px;
              border-radius: 50%;
              margin-bottom: 15px;
              background-color: white;
              text-align:center;">
          <h4>Outstation Trips</h4>
          <p>Plan weekend getaways or long-distance journeys.</p>
        </div>

        <div class="item testimonial-card">
          <img src="assets/logo2.png" alt="Customer 2" class="testimonial-image" style="width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
            background-color: white;
            text-align:center;">
          <h4>Business Rentals</h4>
          <p>Reliable cars for professionals and corporate use.</p>
        </div>

        <div class="item testimonial-card" style="text-align: center;">
          <img src="assets/logo2.png" alt="Customer 2" class="testimonial-image" style="width: 100px;
          height: 100px;
          border-radius: 50%;
          margin-bottom: 15px;
          background-color: white;
          text-align:center;">
          <h4>Airport Transfers</h4>
          <p>On-time pickups and drops to/from airports.</p>
        </div>

      </div>
    </div>
  </div>


  <!-- testimonial -->
  <div class="main_container">
    <h1 class="heading1">Testimonial</h1>
    <div class="testimonial-section">
      <h2 class="heading2">What Our Customers Say</h2>
      <div class="testimonial-cards">
        <div class="testimonial-card">
          <img src="assets/logo2.png" alt="Customer 1" class="testimonial-image">
          <h3 class="customer-name">Priya Sharma</h3>
          <p class="testimonial-text">"Their service is absolutely amazing! I always feel confident and stylish after
            every visit."</p>
        </div>

        <div class="testimonial-card">
          <img src="assets/logo2.png" alt="Customer 2" class="testimonial-image">
          <h3 class="customer-name">Ravi Thapa</h3>
          <p class="testimonial-text">"Very professional and courteous. They truly understand what the customer wants."
          </p>
        </div>

        <div class="testimonial-card">
          <img src="assets/logo2.png" alt="Customer 3" class="testimonial-image">
          <h3 class="customer-name">Anisha Koirala</h3>
          <p class="testimonial-text">"They transformed my wardrobe! Now I get compliments wherever I go."</p>
        </div>

      </div>
    </div>
  </div>

  <footer>

    <div class="foot-panel1">
      <div class="col1" style="padding:1rem;">
        <img src="assets/logo1.png" alt="Loading" widht="auto" height="100px"
          style="background-color: white; border-radius:50%">
        <p>NagarYatra, founded in 2070 B.S., offers innovative opportunities in a highly academic
          setting. The college aims to deliver value-based quality education at the graduate level, promoting
          personal and professional growth through its experienced faculty and national experts. </p>
      </div>
      <ul>
        <p class="footer-title" style="margin-left:6px;">Get In Touch</p>
        <div style="display:flex;align-items:center;margin-left:0.3rem;padding:0.5rem;">
          <span><i class="fa-solid fa-envelope text-color"></i></span>
          <div>
            <!-- <a href="#">
                        Email</a> -->
            <a href="mailto:nagarctservices@gmail.com">nagarctservices@gmail.com</a>
          </div>
        </div>
        <div style="display:flex;align-items:center;margin-left:0.3rem;padding:0.5rem;">
          <span><i class="fa-solid fa-phone text-color"></i>
            </i></span>
          <div>

            <!-- <a href="#">Phone Number:</a> -->
            <a href="tel:021-590471">021-590471</a>
          </div>
        </div>
        <div style="display:flex;align-items:center;margin-left:0.3rem;padding:0.5rem;">
          <span><i class="fa-solid fa-location-dot text-color"></i></span>
          <div>


            <!-- <a href="#">Address</a> -->
            <a href="https://maps.app.goo.gl/1n8EmcytJQgj1LVH8" target="_blank"> Main Road,Biratnagar-09</a>
          </div>
        </div>

      </ul>
      <div style="display:flex; flex-direction:column;">
        <ul style="text-align: center;">
          <p class="footer-title">Follow us</p>
          <div class="social">
            <li> <a href="https://www.facebook.com/215660679312132?ref=embed_page" target="_blank"><img
                  src="assets/facebook.png" height="40" width="40" alt=""></a></li>
            <li><a href="https://www.instagram.com/hdc.college?igsh=ampuaThqa2plYmk2" target="_blank"><img
                  src="assets/instagram.png" height="40" width="40" alt=""></a></li>
            <li> <a href="https://www.linkedin.com/company/himalayacollege/" target="_blank"><img
                  src="assets/linkedin.png" height="40" width="40" alt=""></a></li>
          </div>


        </ul>

      </div>
    </div>


    <div class="foot-panel2">
      <div class="copyright">
        Copyright &copy; <span id="year"></span>, All rights reserved. NagarYatra.
      </div>
    </div>

  </footer>

</body>

<script>
  document.getElementById("year").textContent = new Date().getFullYear();
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
  $(document).ready(function () {
    $('.owl-carousel').owlCarousel({
      loop: true,
      margin: 20,
      nav: false,
      dots: false,
      autoplay: true,
      autoplayTimeout: 3000,
      responsive: {
        0: { items: 1 },
        600: { items: 2 },
        1000: { items: 3 }
      }
    });
  });
</script>
<!-- </body> -->

</html>