<?php
session_start();
session_unset();
session_destroy();

// Перенаправление на главную страницу
header("Location: index.php");
exit;
?>
