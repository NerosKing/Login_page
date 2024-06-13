<?php
require 'database.php';
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        $error = "All fields are required!";
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            if (Database::user_exists($username)) {
                $error = "Username already exists!";
            } else {
                if (Database::register_user($username, $password)) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Error: Could not register user.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #f44336;
            color: white;
            padding: 15px;
            border-radius: 5px;
            z-index: 1000;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="background">
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
            <div class="triangle"></div>
        </div>
        <div class="blur-overlay"></div>
        <div class="login-form">
            <h2>Register</h2>
            <form action="register.php" method="post" onsubmit="return validateForm()">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <br>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <br>
                <button type="submit">Register</button>
            </form>
            <a href="login.php" class="login_button_register">
                <button type="button">Login</button>
            </a>
        </div>
    </div>
    <div id="popup" class="popup"></div>
    <script>
        function validateForm() {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;

            if (!username || !password || !confirmPassword) {
                showPopup("All fields are required!");
                return false;
            }

            if (password !== confirmPassword) {
                showPopup("Passwords do not match!");
                return false;
            }

            return true;
        }

        function showPopup(message) {
            var popup = document.getElementById("popup");
            popup.innerText = message;
            popup.style.display = "block";
            setTimeout(function() {
                popup.style.display = "none";
            }, 3000);
        }

        <?php if (!empty($error)): ?>
            document.addEventListener("DOMContentLoaded", function() {
                showPopup("<?php echo $error; ?>");
            });
        <?php endif; ?>
    </script>
</body>
</html>
