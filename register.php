<?php
// Start sesji, aby śledzić zalogowanego użytkownika
session_start();

// Sprawdzenie, czy użytkownik jest już zalogowany
if (isset($_SESSION['username'])) {
    header("Location: welcome.php"); // Przekierowanie na stronę powitalną, jeśli użytkownik jest zalogowany
    exit();
}

// Dane do połączenia z bazą danych
$servername = "localhost";
$username = "root";
$password = ""; // Domyślnie w XAMPP hasło jest puste
$dbname = "rezerwacje";

// Nawiązanie połączenia z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Szyfrowanie hasła
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Zapytanie SQL do wstawienia danych
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "Rejestracja zakończona sukcesem!";
    } else {
        echo "Błąd: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Logowanie</title>
</head>
<body>
    <h2>Rejestracja użytkownika</h2>
    <form action="register.php" method="POST">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="password">Hasło:</label>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Zarejestruj się</button>
    </form>
</body>
</html>