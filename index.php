<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NagarYatra - Premium Ride Services</title>
  <link rel="icon" href="assets/logo1.png" type="image/png">

  <!-- External Resources -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

  <!-- Local Styles -->
  <link rel="stylesheet" href="design.css" />
</head>

<body>
  <!-- Navigation -->
  <header class="header">
    <nav class="nav container">
      <a href="#" class="nav__logo">
        <img src="assets/logo1.png" alt="NagarYatra Logo" width="150px" height="140px">  
        <!-- NagarYatra -->
      </a>

      <div class="nav__menu">
        <ul class="nav__list">
          <li><a href="#home" class="nav__link">Home</a></li>
          <li><a href="#about" class="nav__link">About</a></li>
          <li><a href="#services" class="nav__link">Services</a></li>
          <li><a href="#testimonial" class="nav__link">Testimonials</a></li>
          <li><a href="#contact" class="nav__link">Contact</a></li>
        </ul>
      </div>

      <div class="nav__buttons">
        <a href="user/book_ride.php" class="btn btn--primary">Book Ride</a>
        <a href="register.php" class="btn btn--outline">Sign Up</a>
      </div>
    </nav>
  </header>

  <!-- Hero Section -->
  <main>
    <section class="hero section" id="home">
      <div class="hero__content container">
        <div class="hero__text">
          <h1 class="hero__title">Experience Premium Ride Services</h1>
          <p class="hero__subtitle">Safe, reliable, and comfortable transportation solutions</p>
          <a href="#services" class="btn btn--primary">Explore Services</a>
        </div>
        <div class="hero__image">
          <img src="assets/bbg.svg" alt="Premium vehicle illustration">
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section class="about section" id="about">
      <div class="about__content container">
        <h2 class="section__title">About NagarYatra</h2>
        <p class="section__description">
          NagarYatra is a modern vehicle booking platform dedicated to providing hassle-free
          and affordable ride solutions across Nepal. Whether you're traveling for work or leisure,
          we connect you to the best vehicles and trusted drivers in minutes.
        </p>
      </div>
    </section>

    <!-- Services Section -->
    <section class="services section" id="services">
      <div class="services__content container">
        <h2 class="section__title">Our Services</h2>

        <div class="services__grid owl-carousel">
          <div class="service__card">
            <i class="fa-solid fa-city service__icon"></i>
            <h3>City Rides</h3>
            <p>Comfortable and quick rides across town</p>
          </div>

          <div class="service__card">
            <i class="fa-solid fa-road service__icon"></i>
            <h3>Outstation Trips</h3>
            <p>Plan weekend getaways or long journeys</p>
          </div>

          <div class="service__card">
            <i class="fa-solid fa-screwdriver-wrench service__icon"></i>
            <h3>Mechanic Services</h3>
            <p>Comprehensive vehicle maintenance and repair solutions</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials section" id="testimonial">
      <div class="testimonials__content container">
        <h2 class="section__title">Client Testimonials</h2>

        <div class="testimonials__grid">
          <article class="testimonial__card">
            <div class="testimonial__header">
              <img src="assets/shibu.jpg" alt="Shibu Sharma">
              <div>
                <h3>Shibu Sharma</h3>
                <div class="testimonial__rating">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                </div>
              </div>
            </div>
            <p class="testimonial__text">
              "Absolutely reliable service! Their professional drivers and clean vehicles make every ride a pleasure."
            </p>
          </article>

          <article class="testimonial__card">
            <div class="testimonial__header">
              <img src="assets/kajal.jpg" alt="Kajal Mehta">
              <div>
                <h3>Kajal Mehta</h3>
                <div class="testimonial__rating">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                </div>
              </div>
            </div>
            <p class="testimonial__text">
              "Absolutely reliable service! Their professional drivers and clean vehicles make every ride a pleasure."
            </p>
          </article>

          <!-- Add more testimonial cards -->
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
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
        <p class="footer-title" id="contact" style="margin-left:6px;">Get In Touch</p>
        <div style="display:flex;align-items:center;margin-left:0.3rem;padding:0.5rem;">
          <span><i class="fa-solid fa-envelope text-color"></i></span>
          <div>
            <a href="mailto:nagarctservices@gmail.com" style="margin-top: 0 !important;">&nbsp;
              nagarctservices@gmail.com</a>
          </div>
        </div>
        <div style="display:flex;align-items:center;margin-left:0.3rem;padding:0.5rem;">
          <span><i class="fa-solid fa-phone text-color" style="margin-top: 0 !important;"></i>
            </i></span>
          <div>
            <a href="tel:021-590471">&nbsp; 021-590471</a>
          </div>
        </div>
        <div style="display:flex;align-items:center;margin-left:0.3rem;padding:0.5rem;">
          <span><i class="fa-solid fa-location-dot text-color" style="margin-top: 0 !important;"></i></span>
          <div>
            <a href="https://maps.app.goo.gl/1n8EmcytJQgj1LVH8" target="_blank">&nbsp; Main Road,Biratnagar-09</a>
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
  </footer>
  </div>

  <div class="footer__copyright">
    <p>&copy; <span id="year"></span> NagarYatra. All rights reserved.</p>
  </div>
  </footer>
  <script>
    document.getElementById("year").textContent = new Date().getFullYear();
  </script>

  <script src="main.js"></script>
</body>

</html>