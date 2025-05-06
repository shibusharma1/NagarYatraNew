</div>
</form>
</div>
</div>
<script>
    function togglePassword() {
        const passwordField = document.getElementById("password");
        const toggleIcon = document.getElementById("togglePasswordIcon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }

    function toggleConfirmPassword() {
        const passwordField = document.getElementById("confirm_password");
        const toggleIcon = document.getElementById("toggleConfirmPasswordIcon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }
</script>

<script>
    function validateForm() {
        let isValid = true;
        document.querySelectorAll(".error").forEach(e => e.textContent = ""); // Clear errors

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const confirm_password = document.getElementById("confirm_password").value;

        if (name === "") {
            document.querySelector("input[name='name'] + .error").textContent = "Name is required.";
            isValid = false;
        }
        if (email === "") {
            document.querySelector("input[name='email'] + .error").textContent = "Email is required.";
            isValid = false;
        } else if (!/\S+@\S+\.\S+/.test(email)) {
            document.querySelector("input[name='email'] + .error").textContent = "Invalid email format.";
            isValid = false;
        }
        if (password.length < 6) {
            document.querySelector("input[name='password'] + .error").textContent = "Password must be at least 6 characters.";
            isValid = false;
        }
        if (password !== confirm_password) {
            document.querySelector("input[name='confirm_password'] + .error").textContent = "Passwords do not match.";
            isValid = false;
        }

        return isValid;
    }
</script>
</body>

</html>