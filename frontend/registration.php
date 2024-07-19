<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Registration</title>
    <link rel="stylesheet" href="assets/css/stylesheet2.css">
</head>
<body>
    <div class="registration-container">
        <h1>REGISTRATION</h1>
       
        <form id="registration-form" action="admin/register.php" method="POST" onsubmit="return validateForm()">
            <input type="text" id="partner-name" name="partnerName" placeholder="Partner Name" >
            <div class="error" id="partner-name-error"></div>

            <input type="email" id="email" name="email" placeholder="Email" >
            <div class="error" id="email-error"></div>

            <input type="tel" id="contact-number" name="contactNumber" placeholder="Contact Number" >
            <div class="error" id="contact-number-error"></div>

            <input type="text" id="address" name="address" placeholder="Address" >
            <div class="error" id="address-error"></div>

            <input type="password" id="password" name="password" placeholder="Password" >
            <div class="error" id="password-error"></div>

            <input type="password" id="confirm-password" name="confirmPassword" placeholder="Confirm Password" >
            <div class="error" id="confirm-password-error"></div>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <script>
        function validateForm() {
            // Clear previous errors
            document.querySelectorAll('.error').forEach(error => error.innerHTML = '');

            const partnerName = document.getElementById('partner-name').value;
            const email = document.getElementById('email').value;
            const contactNumber = document.getElementById('contact-number').value;
            const address = document.getElementById('address').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            // Validate Partner Name
            if (!partnerName) {
                document.getElementById('partner-name-error').innerHTML = 'Partner Name is required.';
                return false;
            }

            // Validate Email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                document.getElementById('email-error').innerHTML = 'Email is required.';
                return false;
            } else if (!emailPattern.test(email)) {
                document.getElementById('email-error').innerHTML = 'Invalid email format.';
                return false;
            }

            // Validate Contact Number
            const contactNumberPattern = /^\d{10}$/;
            if (!contactNumber) {
                document.getElementById('contact-number-error').innerHTML = 'Contact Number is required.';
                return false;
            } else if (!contactNumberPattern.test(contactNumber)) {
                document.getElementById('contact-number-error').innerHTML = 'Contact Number must be 10 digits.';
                return false;
            }

            // Validate Address
            if (!address) {
                document.getElementById('address-error').innerHTML = 'Address is required.';
                return false;
            }

            // Validate Password
            if (!password) {
                document.getElementById('password-error').innerHTML = 'Password is required.';
                return false;
            }

            // Validate Confirm Password
            if (!confirmPassword) {
                document.getElementById('confirm-password-error').innerHTML = 'Confirm Password is required.';
                return false;
            } else if (password !== confirmPassword) {
                document.getElementById('confirm-password-error').innerHTML = 'Passwords do not match.';
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
