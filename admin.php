<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="style_admin_page.css">
</head>
<body>
    <div class="container">
        <h2>User List</h2>
        <ul class="user-list">
            <?php
            require 'database.php';

            // Haal alle gebruikers op uit de database
            $users = Database::get_users();
            foreach ($users as $user) {
                echo "<li>";
                echo "<span class='username'>{$user['username']}</span>";
                // Toon alleen de bewerkingsknop voor admins
                if ($_SESSION['is_admin']) {
                    echo "<button class='edit-button'>Edit</button>";
                    echo "<form class='edit-form' action='edit_user.php' method='post'>";
                    echo "<input type='hidden' name='user_id' value='{$user['id']}'>";
                    echo "<input type='text' name='new_username' placeholder='New Username'>";
                    echo "<input type='password' name='new_password' placeholder='New Password'>";
                    echo "<button type='submit'>Save</button>";
                    echo "</form>";
                }
                echo "</li>";
            }
            ?>
        </ul>
        <a class="logout" href="logout.php">Logout</a>
    </div>

    <script>
        // Voeg JavaScript toe om de bewerkingsformulieren weer te geven wanneer op de bewerkingsknop wordt geklikt
        const editButtons = document.querySelectorAll('.edit-button');
        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const form = button.nextElementSibling;
                form.style.display = 'block';
                button.style.display = 'none';
            });
        });
    </script>
</body>
</html>
