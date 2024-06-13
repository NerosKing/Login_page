<?php
$servername = "localhost";
$username = "root"; // vervang met jouw databasegebruikersnaam
$password = ""; // vervang met jouw databasewachtwoord
$dbname = "my_database";

// Maak verbinding met de database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Controleer of het formulier is ingediend met de POST-methode
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Formulier is verzonden via POST.<br>";

    // Verkrijg gegevens van het formulier
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $text = $_POST['text'] ?? '';

    // Debugging: Print de ontvangen gegevens
    echo "First Name: " . htmlspecialchars($first_name) . "<br>";
    echo "Last Name: " . htmlspecialchars($last_name) . "<br>";
    echo "Text: " . htmlspecialchars($text) . "<br>";

    // Controleer of de gegevens niet leeg zijn
    if (!empty($first_name) && !empty($last_name) && !empty($text)) {
        echo "Formuliergegevens zijn niet leeg.<br>";

        // Bereid de SQL-instructie voor om gegevens in te voegen
        $sql = "INSERT INTO my_table (first_name, last_name, text) VALUES (?, ?, ?)";

        // Bereid de statement voor
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Statement voorbereiding mislukt: " . $conn->error);
        }

        // Bind de parameters aan de statement
        $stmt->bind_param("sss", $first_name, $last_name, $text);

        // Voer de statement uit
        if ($stmt->execute() === true) {
            echo "Gegevens succesvol ingevoerd";
        } else {
            echo "Fout bij invoeren gegevens: " . $stmt->error;
        }

        // Sluit de statement
        $stmt->close();
    } else {
        echo "Alle velden zijn verplicht.";
    }
}

// Sluit de verbinding
$conn->close();
?>
