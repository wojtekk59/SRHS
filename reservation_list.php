<?php

session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Przekierowanie na stronę logowania, jeśli nie jest zalogowany
    exit();
}

// Połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rezerwacje";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobranie wszystkich rezerwacji
$sql = "SELECT * FROM rezerwacje ORDER BY data_rezerwacji, start_time";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezerwacja Hali</title>
    <link rel="stylesheet" href="style.css"> <!-- Jeśli używasz osobnego pliku CSS -->
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
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
        <a href="reservation_list.php">Aktualne rezerwacje</a>
        <a href="profile.php">Mój profil</a>
        <a href="contact.php">Kontakt</a>
        <a href="logout.php">Wyloguj się</a>
    </nav>

    <h1 style="text-align: center;">Lista Rezerwacji</h1>
    <table>
        <tr>
            <th>Data</th>
            <th>Godzina rozpoczęcia</th>
            <th>Godzina zakończenia</th>
            <th>Imię i nazwisko</th>
            <th>Klasa</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['data_rezerwacji']; ?></td>
            <td><?php echo $row['start_time']; ?></td>
            <td><?php echo $row['end_time']; ?></td>
            <td><?php echo $row['rezerwujacy']; ?></td>
            <td><?php echo $row['klasa']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
