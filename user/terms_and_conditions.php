<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="64x64" href="../assets/logo1.png" />
    <title>Terms and Conditions - NagarYatra</title>
    <!-- <link rel="stylesheet" href="style.css"> Link to your external CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            color: #092448;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        h2 {
            color: #092448;
            font-size: 1.8em;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        p {
            font-size: 1.1em;
            line-height: 1.8em;
            margin-bottom: 15px;
        }

        ul {
            font-size: 1.1em;
            line-height: 1.8em;
        }

        .btn {
            display: inline-block;
            background-color: #092448;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 30px;
        }

        .btn:hover {
            background-color: #1a3d7f;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Terms and Conditions</h1>
            <p>Welcome to NagarYatra! Please read our terms and conditions carefully before using our services.</p>
        </div>

        <section>
            <h2>1. General Terms</h2>
            <!-- <p>By accessing or using the services provided by NagarYatra, you agree to be bound by these Terms and Conditions. </p> -->

            <h3>1.1 Acceptance of Terms</h3>
            <p>By accessing or using the services provided by NagarYatra, you agree to be bound by these Terms and Conditions. </p>

            <h3>1.2 Changes to Terms</h3>
            <p>We reserve the right to update or change these Terms and Conditions at any time. Changes will be posted on this page, and your continued use of our service after such changes constitutes acceptance of the new terms.</p>

            <h3>1.3 Eligibility</h3>
            <p>You must be at least 18 years old and legally capable of entering into binding contracts to use our service.</p>
        </section>

        <section>
            <h2>2. Vehicle Booking</h2>
            <h3>2.1 Booking Process</h3>
            <p>To book a vehicle, you must provide accurate information regarding your pick-up and drop-off location, date, and time. You must confirm that the vehicle type and services offered match your requirements.</p>

            <h3>2.2 Booking Confirmation</h3>
            <p>Once your booking request is submitted, it will be processed, and you will receive a booking confirmation email with the details. A booking is considered confirmed when you receive a confirmation message from us.</p>

            <h3>2.3 Payment Terms</h3>
            <p>Bookings may require a deposit or full payment at the time of booking. The payment can be made through accepted payment gateways (e.g., credit card, e-wallet, etc.). We will not be responsible for any payment issues arising from incorrect payment details.</p>
        </section>

        <section>
            <h2>3. Cancellation and Refund Policy</h2>
            <h3>3.1 Cancellation by User</h3>
            <p>You may cancel your booking at any time before the scheduled pick-up time. However, cancellation fees may apply depending on the time of cancellation and our cancellation policy.</p>

            <h3>3.2 Refunds</h3>
            <p>Refunds are only issued in accordance with our cancellation policy. In case of a refund, it will be processed to the original payment method.</p>

            <h3>3.3 Cancellation by NagarYatra</h3>
            <p>We reserve the right to cancel bookings in case of unforeseen circumstances, such as vehicle breakdowns or force majeure events. If we cancel your booking, we will offer a full refund or reschedule the booking based on your preference.</p>
        </section>

        <section>
            <h2>4. Liability</h2>
            <h3>4.1 Limitation of Liability</h3>
            <p>NagarYatra is not liable for any direct, indirect, incidental, special, or consequential damages resulting from your use of our services. We are also not liable for any loss or damage caused during the ride, except in cases of gross negligence.</p>

            <h3>4.2 Force Majeure</h3>
            <p>We are not responsible for any failure to perform due to circumstances beyond our control, such as weather conditions, traffic accidents, natural disasters, or strikes.</p>
        </section>

        <section>
            <h2>5. Privacy and Data Protection</h2>
            <h3>5.1 Privacy Policy</h3>
            <p>We value your privacy and are committed to protecting your personal information. Please refer to our Privacy Policy for details on how we collect, use, and protect your data.</p>

            <h3>5.2 Data Sharing</h3>
            <p>We may share your personal information with third-party service providers or authorities as required by law or for the purpose of providing our services (e.g., payment gateways, insurance companies, etc.).</p>
        </section>

        <section>
            <h2>6. Contact Us</h2>
            <p>If you have any questions or concerns about our Terms and Conditions, please feel free to contact us at:</p>
            <p>Email: <a href="mailto: nagarctservices@gmail.com">nagarctservices@gmail.com</a></p>
            <p>Phone: <a href="tel: +977-9819099126">+977-9819099126</a></p>
            <p>Address: Biratnagar</p>
        </section>
        <?php
        if(isset($_SESSION['id'])){
        ?>
        <a href="index.php" class="btn">Back to Home</a>
        <?php }else{ ?>
            <a href="../register.php" class="btn">Back to Register</a>
            <?php }?>    
    </div>
</body>

</html>
