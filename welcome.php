<?php
// Rozpoczęcie sesji
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Przekierowanie na stronę logowania, jeśli nie jest zalogowany
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Rezerwacje</title>
</head>
<body>
    <header>
        <div class="site-name">
            <h1>System Rezerwacji Hali Sportowej</h1> 
            <h3 class="user-name">Witaj, <?= htmlspecialchars($_SESSION['username']) ?>!</h3>
        </div>
    </header>
    <nav class="nav">
        <a href="welcome.php">Strona główna</a>
        <a href="reservation.php">Rezerwacja hali</a>
        <a href="profile.php">Mój profil</a>
        <a href="contact.php">Kontakt</a>
        <a href="logout.php">Wyloguj się</a>
    </nav>

    <div class="container">
        <p> Witaj w Systemie Rezerwacji Hali Sportowej w skrócie SRHS. System ten umożliwia w prosty sposób sprawdzenia dostępności hali sportowej oraz zarezerwowanie wybranego terminu. Zapraszamy do korzystania z naszej hali!</p>
    </div>
</body>
</html>