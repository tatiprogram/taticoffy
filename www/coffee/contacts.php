<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffeee shop</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
    <main>
    <section id="testimonial-section" class="section-main">
    <div class="container">
        <h2 class="section-title">Отзывы</h2>
        <h3 class="section-subtitle">Говорят наши посетители</h3>

        <!-- Форма для добавления отзыва -->
        <form action="add_comment.php" method="POST" class="testimonial-form">
    <h4>Оставьте свой отзыв</h4>
    <label for="name">Ваше имя:</label>
    <input type="text" id="name" name="name" required maxlength="30">

    <label for="text">Ваш отзыв:</label>
    <textarea id="text" name="text" rows="4" required maxlength="67"></textarea>

    <label for="rating">Рейтинг:</label>
    <select id="rating" name="rating" required>
        <option value="1">1 звезда</option>
        <option value="2">2 звезды</option>
        <option value="3">3 звезды</option>
        <option value="4">4 звезды</option>
        <option value="5">5 звезд</option>
    </select>

    <button type="submit" class="btn-primary">Отправить отзыв</button>
</form>


       <!-- Блок с отзывами -->
<div class="testimonial-wrap">
    <!-- Здесь будут отображаться отзывы, добавленные в базу данных -->
    <?php
        // Подключаемся к базе данных
        include 'db.php';

        try {
            // Получаем отзывы из базы данных
            $stmt = $pdo->query("SELECT * FROM comment ORDER BY created_at DESC");
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка получения отзывов: " . $e->getMessage());
        }

        // Отображаем отзывы
        foreach ($comments as $comment) {
            echo '<div class="testimonial">';
            echo '<div class="testimonial-data">';
            echo '<p class="testimonial__text section__text">' . htmlspecialchars($comment['text']) . '</p>';
            echo '</div>';
            echo '<div class="testimonial-info">';
            echo '<div class="testimonial-person">';
            echo '<span class="testimonial__name">' . htmlspecialchars($comment['name']) . '</span>';
            echo '</div>';
            echo '<ul class="testimonial__list rating__list">';
            for ($i = 1; $i <= 5; $i++) {
                echo '<li class="rating__item material-icons-outlined' . ($i <= $comment['rating'] ? ' filled' : '') . '">star</li>';
            }
            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }
    ?>
</div>

    </div>
</section>

<h3 class="section-subtitle">Регистрация</h3>
<section id="section-reservation" class="section-main">
    <div class="container">
        <div class="reservation-wrap">
            <div class="reservation-map"></div>
            <form action="process_reservation.php" method="POST" class="reservation">
                <h2 class="reservation__title section-title">Бронирование</h2>
                <h3 class="reservation__subtitle section-subtitle">
                    Бронирование столика
                </h3>
                <label for="people_count">Количество человек:</label>
                <input class="reservation__input" type="number" id="people_count" name="people_count" value="человек" required>

                <label for="reservation_date">Дата бронирования:</label>
                <input class="reservation__input" type="date" id="reservation_date" name="reservation_date" value="дата" required>

                <label for="reservation_time">Время бронирования:</label>
                <input class="reservation__input" type="time" id="reservation_time" name="reservation_time" value="время" required>

                <button class="reservation__btn btn-primary">Подтвердить</button>
            </form>
        </div>
    </div>
</section>

        <?php include 'footer.php'; ?>
    </main>
</body>
</html>