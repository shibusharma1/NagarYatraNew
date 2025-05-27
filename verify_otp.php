<?php
session_start();
$title = "NagarYatra | Verify OTP";
$current_page = 'otp';
include_once "register_login_header.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="assets/logo1.png" type="image/png" />
    <title>Verify OTP</title>

    <style>
        .container {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
            background-color: #282474;
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
        const countdownDuration = 120; // 2 minutes in seconds
        let countdown;

        function startCountdown() {
            const timerDisplay = document.getElementById("timer");
            const verifyButton = document.getElementById("verify-btn");
            const resendOtp = document.getElementById("resend-otp");

             resendOtp.style.display = "none";
            // Load saved countdown or start fresh
            let savedTime = localStorage.getItem("otpCountdown");
            if (savedTime !== null && !isNaN(savedTime)) {
                countdown = parseInt(savedTime);
            } else {
                countdown = countdownDuration;
            }

            // Immediately reflect UI if countdown already expired on page load
            if (countdown <= 0) {
                timerDisplay.innerText = "00:00";
                verifyButton.style.display = "none";
                resendOtp.style.display = "block";
                
                localStorage.removeItem("otpCountdown");
                return;
            }

            const timer = setInterval(() => {
                if (countdown >= 0) {
                    let minutes = Math.floor(countdown / 60);
                    let seconds = countdown % 60;
                    seconds = seconds < 10 ? '0' + seconds : seconds;

                    timerDisplay.innerText = `${minutes}:${seconds}`;

                    localStorage.setItem("otpCountdown", countdown);

                    countdown--;
                } else {
                    clearInterval(timer);
                    timerDisplay.innerText = "00:00";

                    verifyButton.style.display = "none";
                    resendOtp.style.display = "block";

                    localStorage.removeItem("otpCountdown");
                }
            }, 1000);
        }
    </script>
</head>

<body onload="startCountdown()">
    <form action="verify_otp_process.php" method="POST">
        <div class="first" style="margin-top: -20px;">
            <h2 class="title">Enter OTP</h2>
            <p>Time left: <span class="countdown" id="timer">2:00</span></p>

            <div class="input-field">
                <i class="fas fa-address-card"></i>
                <input type="text" placeholder="Enter OTP" name="otp"
                    value="<?= htmlspecialchars($_POST['otp'] ?? '') ?>" required />
            </div>

            <button type="submit" class="btn solid" id="verify-btn">Verify</button>
        

        <div id="resend-otp" class="resend-otp btn solid">
            <!-- <p>OTP expired!</p> -->
             <a href="resend_otp.php" style="text-decoration: none;color:white;">Resend OTP</a>
        </div>
        </div>
    </form>
</body>

</html>
