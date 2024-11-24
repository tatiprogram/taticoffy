<?php
// Подключение к базе данных
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $name = $_POST['name'];
    $text = $_POST['text'];
    $rating = $_POST['rating'];

    // Проверка на пустые значения
    if (empty($name) || empty($text) || empty($rating)) {
        die("Пожалуйста, заполните все поля.");
    }

    // Вставка данных в таблицу comment
    try {
        $stmt = $pdo->prepare("INSERT INTO comment (name, text, rating) VALUES (:name, :text, :rating)");
        $stmt->execute([
            'name' => $name,
            'text' => $text,
            'rating' => $rating
        ]);

        // Перенаправляем обратно на страницу с отзывами
        header('Location: contacts.php'); // Или другой путь, на котором находится страница с отзывами
        exit;
    } catch (PDOException $e) {
        die("Ошибка добавления отзыва: " . $e->getMessage());
    }
}
?>
