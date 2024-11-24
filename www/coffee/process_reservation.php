<?php
// Подключение к базе данных
include 'db.php';

// Проверка, что форма была отправлена методом POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    $people_count = $_POST['people_count'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];

    // Проверка на наличие данных
    if (!empty($people_count) && !empty($reservation_date) && !empty($reservation_time)) {
        // Запрос на добавление данных в таблицу
        try {
            $stmt = $pdo->prepare("INSERT INTO reservation (people_count, reservation_date, reservation_time) 
                                   VALUES (:people_count, :reservation_date, :reservation_time)");
            $stmt->bindParam(':people_count', $people_count);
            $stmt->bindParam(':reservation_date', $reservation_date);
            $stmt->bindParam(':reservation_time', $reservation_time);

            // Выполняем запрос
            $stmt->execute();

            // Переадресация или сообщение об успешном добавлении
            header('Location: contacts.php'); // Или другой путь, на котором находится страница с отзывами
            // Вы можете добавить редирект на другую страницу, например:
            // header("Location: thank_you.php");
        } catch (PDOException $e) {
            // Обработка ошибки, если запрос не прошел
            echo "Ошибка при добавлении бронирования: " . $e->getMessage();
        }
    } else {
        echo "Пожалуйста, заполните все поля формы.";
    }
}
?>
