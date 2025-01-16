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

// Sprawdzenie połączenia
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Zmienna do komunikatów
$message = "";

// Sprawdzanie, czy formularz został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza
    $rezerwujący = $_POST['rezerwujacy'] ?? null;
    $class = $_POST['klasa'] ?? null;
    $date = $_POST['data_rezerwacji'] ?? null;
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    
    // Jeśli użytkownik kliknął "Zarezerwuj halę"
    if (isset($_POST['reserve'])) {
        // Wykonaj zapis do bazy danych
        $sql_reserve = "INSERT INTO rezerwacje (rezerwujacy, klasa, data_rezerwacji, start_time, end_time) VALUES (?, ?, ?, ?, ?)";
        $stmt_reserve = $conn->prepare($sql_reserve);
        
        if ($stmt_reserve) {
            $stmt_reserve->bind_param("sssss", $rezerwujący, $class, $date, $start_time, $end_time);
            if ($stmt_reserve->execute()) {
                $message = "Rezerwacja została pomyślnie dodana.";
            } else {
                $message = "Wystąpił błąd podczas rezerwacji: " . $stmt_reserve->error;
            }
            $stmt_reserve->close();
        } else {
            $message = "Błąd zapytania: " . $conn->error;
        }
    }
    
    // Jeśli użytkownik kliknął "Sprawdź dostępność"
    else {
        // Sprawdzenie, czy termin jest wolny
        $sql_check = 'SELECT * FROM rezerwacje WHERE data_rezerwacji = ? AND 
                      (start_time < ? AND end_time > ?)';
        $stmt_check = $conn->prepare($sql_check);
        
        if ($stmt_check) {
            $stmt_check->bind_param("sss", $date, $end_time, $start_time);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $message = "Termin jest już zajęty.";
            } else {
                $message = "Termin dostępny. Możesz dokonać rezerwacji.";
            }
            $stmt_check->close();
        } else {
            $message = "Błąd zapytania: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezerwacja Hali</title>
    <link rel="stylesheet" href="style.css"> <!-- Jeśli używasz osobnego pliku CSS -->
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
    <div class="container">
        <h1>Rezerwacja Hali</h1>
        <form action="reservation.php" method="post">
            <label for="rezerwujacy">Dane rezerwującego:</label>
            <input type="text" id="rezerwujacy" name="rezerwujacy" value="<?= isset($rezerwujacy) ? htmlspecialchars($rezerwujący) : '' ?>" required><br>

            <label for="klasa">Klasa:</label>
            <input type="text" id="klasa" name="klasa" value="<?= isset($class) ? htmlspecialchars($class) : '' ?>" required><br>

            <label for="data_rezerwacji">Data rezerwacji:</label>
            <input type="date" id="data_rezerwacji" name="data_rezerwacji" value="<?= isset($date) ? htmlspecialchars($date) : '' ?>" required><br>

            <label for="start_time">Godzina rozpoczęcia:</label>
            <input type="time" id="start_time" name="start_time" value="<?= isset($start_time) ? htmlspecialchars($start_time) : '' ?>" required><br>

            <label for="end_time">Godzina zakończenia:</label>
            <input type="time" id="end_time" name="end_time" value="<?= isset($end_time) ? htmlspecialchars($end_time) : '' ?>" required><br>

            <input type="submit" value="Sprawdź dostępność">
        </form>

        <?php if (!empty($message)) echo "<p>$message</p>"; ?>

        <?php if ($message === "Termin dostępny. Możesz dokonać rezerwacji.") { ?>
            <form action="reservation.php" method="post">
                <input type="hidden" name="rezerwujacy" value="<?= htmlspecialchars($rezerwujacy) ?>">
                <input type="hidden" name="klasa" value="<?= htmlspecialchars($class) ?>">
                <input type="hidden" name="data_rezerwacji" value="<?= htmlspecialchars($date) ?>">
                <input type="hidden" name="start_time" value="<?= htmlspecialchars($start_time) ?>">
                <input type="hidden" name="end_time" value="<?= htmlspecialchars($end_time) ?>">
                <button type="submit" name="reserve">Zarezerwuj halę</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>

