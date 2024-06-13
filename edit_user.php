// In edit_user.php
<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];

    if (Database::update_user($user_id, $new_username, $new_password)) {
        // Gebruiker is succesvol bijgewerkt
        header("Location: admin.php");
        exit();
    } else {
        // Er is een fout opgetreden bij het bijwerken van de gebruiker
        echo "Error updating user.";
    }
}
?>
