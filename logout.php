<?php
// Rozpoczęcie sesji
session_start();

// Zniszczenie sesji (wylogowanie)
session_unset();
session_destroy();

// Przekierowanie na stronę logowania po wylogowaniu
header("Location: login.php");
exit();
?>
