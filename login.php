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
$password = "";
$dbname = "rezerwacje";

// Nawiązanie połączenia z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Zmienna na komunikaty dla użytkownika
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sprawdzenie, czy użytkownik istnieje
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Weryfikacja hasła
        if (password_verify($password, $user['password'])) {
            // Logowanie pomyślne: ustawienie sesji
            $_SESSION['username'] = $user['username'];
            header("Location: welcome.php"); // Przekierowanie na stronę powitalną
            exit();
        } else {
            $message = "Nieprawidłowe hasło.";
        }
    } else {
        $message = "Nie znaleziono użytkownika o podanej nazwie.";
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
    <div class="login-container">
        <h1>Logowanie</h1>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post" action="login.php">
            <input type="text" name="username" placeholder="Nazwa użytkownika" required>
            <input type="password" name="password" placeholder="Hasło" required>
            <button type="submit">Zaloguj się</button>
        </form>
    </div>
</body>
</html>