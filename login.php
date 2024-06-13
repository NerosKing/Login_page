<?php
require 'database.php';
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = Database::authenticate_user($username, $password);
    if ($user) {
        // Controleer of de gebruiker een admin is
        $is_admin = Database::is_admin($username);
        if ($is_admin) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = true;
            header("Location: admin.php");
            exit();
        } else {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = false;
            header("Location: dashboard.php");
            exit();
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
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
            <h2>Login</h2>
            <form action="login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <br>
                <div class="button_wrapper">
                    <button type="submit">Login</button>
                    <a class="register_button_home" href="register.php">
                        <button type="button">Register</button>
                    </a>
                </div>
            </form>
            <?php if ($error): ?>
                <div class="popup"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function showPopup(message) {
            var popup = document.querySelector('.popup');
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
