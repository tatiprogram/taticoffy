<?php
$host = 'localhost'; // Имя хоста
$db = 'coffee'; // Имя базы данных
$user = 'root'; // Пользователь базы данных
$pass = 'mysql'; // Пароль базы данных

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>
