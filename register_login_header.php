<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <!-- <link rel="icon" href="assets/logo1.png" type="image/png"> -->
   <link rel="icon" href="assets/logo1.png" type="image/png">
   <link rel="icon" href="assets/logo1.png" type="image/png">


   <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="register_style.css" />
   <!-- Font Awesome CDN -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
   <!-- Include SweetAlert2 from CDN -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <title><?php echo $title; ?></title>
</head>

<body>
   <div class="container">
      <div class="img">
         <?php
         if ($current_page == 'otp') {
            ?>
            <img src="assets/enter-otp-animate.svg">

             <?php
         } elseif($current_page == 'reset') {
            ?>
            <img src="assets/reset-password-animate.svg">
            <?php
         }
         elseif($current_page == 'forget') {
            ?>
            <img src="assets/reset-password-animate.svg">
            <?php
         }
        
            else {
            ?>
            <img src="assets/bbg.svg">
            <?php
         }
         ?>


      </div>
      <div class="login-content">