<?php
session_start();
$title = "NagarYatra | Verify OTP";
include_once "register_login_header.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/logo1.png" type="image/png">

    <!-- <title>Verify OTP</title> -->
    <style>
        /* body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            background-color: #092448;

        } */

        .container {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            /* width: 300px; */
            background-color: white;
        }

        .countdown {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: red;
        }

        .disabled {
            background-color: gray;
            cursor: not-allowed;
        }

        .resend-otp {
            display: none;
            margin-top: 10px;
        }

        .btn {
            display: block;
            width: 100%;
            height: 50px;
            border-radius: 25px;
            outline: none;
            border: none;
            /* background-image: linear-gradient(to right, #32be8f, #38d39f, #32be8f); */
            background-color: #282474;
            /* background-size: 200%; */
            font-size: 1.2rem;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            text-transform: uppercase;
            margin: 1rem 0;
            cursor: pointer;
            transition: background-position 0.5s, transform 0.3s;
        }

        .btn:hover {
            border: 1px solid #282474;
            transform: scale(1.05);
        }

        .input-field {
            /* max-width: 380px; */
            width: 100%;
            background-color: #f0f0f0;
            margin: 10px 0;
            height: 45px;
            border-radius: 55px;
            display: grid;
            grid-template-columns: 15% 85%;
            padding: 0 0.4rem;
            position: relative;
            animation: fadeInUp 1s ease-in-out;
            overflow: hidden;
        }

        /* .input-field select:focus,
  .input-field input:focus {
    border: 2px solid blue !important;
    outline: none !important;
  } */
        .input-field:focus-within {
            border: 2px solid #282474 !important;
            overflow: hidden;

        }

        .input-field i {
            text-align: center;
            line-height: 45px;
            color: #1a4331;
            transition: 0.5s;
            font-size: 1.1rem;
        }

        .input-field input {
            background: #F0F0F0;
            outline: none;
            border: none;
            line-height: 1;
            font-weight: 600;
            font-size: 1.1rem;
            color: black;
        }

        .input-field input::placeholder {
            color: #2e6a50;
            font-weight: 500;
        }
    </style>
    <script>
        let countdown = 119; // 1:59 in seconds
        function startCountdown() {
            let timerDisplay = document.getElementById("timer");
            let verifyButton = document.getElementById("verify-btn");
            let resendOtp = document.getElementById("resend-otp");

            let timer = setInterval(() => {
                let minutes = Math.floor(countdown / 60);
                let seconds = countdown % 60;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                timerDisplay.innerText = `${minutes}:${seconds}`;
                countdown--;

                if (countdown < 0) {
                    clearInterval(timer);
                    timerDisplay.innerText = "00:00";
                    verifyButton.disabled = true;
                    verifyButton.classList.add("disabled");
                    resendOtp.style.display = "block";
                }
            }, 1000);
        }
    </script>
</head>

<body onload="startCountdown()">

    <!-- <div class="container"> -->
    <!-- <h2>Enter OTP</h2> -->
    <form action="verify_otp_process.php" method="POST">
        <div class="first" style="margin-top: -20px;">
            <h2 class="title">Enter OTP</h2>
            <p>Time left: <span class="countdown" id="timer">2:00</span></p>
            <div class="input-field">
                <i class="fas fa-address-card"></i>
                <input type="text" placeholder="Enter OTP" name="otp"
                    value="<?= htmlspecialchars($_POST['otp'] ?? '') ?>" required />
            </div>
            <button type="submit" class="btn solid" id="verify-btn btn">Verify</button>
        </div>
        <div id="resend-otp" class="resend-otp">
            <p>OTP expired! <a href="resend_otp.php">Resend OTP</a></p>
        </div>
    </form>


    <!-- </div> -->
</body>

</html>