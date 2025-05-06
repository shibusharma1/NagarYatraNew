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