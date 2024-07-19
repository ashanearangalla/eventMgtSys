<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Login</title>
    <link rel="stylesheet" href="assets/css/stylesheet2.css">
    <style>
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .popup {
            background: #fff;
            padding: 40px;
            border-radius: 5px;
            position: relative;
            text-align: center;
        }
        .popup h2 {
            margin-top: 0;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-title">
            <h1>LOGIN</h1>
        </div>

        <form id="login-form" action="admin/authentication.php" method="POST" onsubmit="return validateForm()">
            <input type="text" id="username" name="username" placeholder="Username">
            <div class="error" id="username-error"></div>

            <input type="password" id="password" name="password" placeholder="Password">
            <div class="error" id="password-error"></div>

            <button type="submit">Login</button>
            <p>Don't have an account? <a href="registration.php">Sign up here</a></p>
        </form>
    </div>

    <?php
    session_start();
    if (isset($_SESSION['message'])) {
        echo '<div class="overlay" id="popup-overlay">
                <div class="popup">
                    <span class="close" id="popup-close">&times;</span>
                    <h2>' . $_SESSION['message'] . '</h2>
                </div>
              </div>';
        unset($_SESSION['message']);
    }
    ?>

    <script>
        function validateForm() {
            // Clear previous errors
            document.querySelectorAll('.error').forEach(error => error.innerHTML = '');

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            let isValid = true;

            // Validate Username
            if (!username) {
                document.getElementById('username-error').innerHTML = 'Username is required.';
                return false;
            }

            // Validate Password
            if (!password) {
                document.getElementById('password-error').innerHTML = 'Password is required.';
                return false;
            }

            return true;
        }

        // Display popup if it exists
        document.addEventListener('DOMContentLoaded', function() {
            const popupOverlay = document.getElementById('popup-overlay');
            const popupClose = document.getElementById('popup-close');

            if (popupOverlay) {
                popupOverlay.style.display = 'flex';
                popupClose.addEventListener('click', function() {
                    popupOverlay.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>
