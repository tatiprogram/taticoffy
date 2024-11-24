<?php
// Подключение к базе данных
require 'db.php';

// Получение данных из базы данных
// Получаем кофе
$stmt_coffee = $pdo->query("SELECT * FROM coffee");
$coffees = $stmt_coffee->fetchAll(PDO::FETCH_ASSOC);

// Получаем устройства для кофе
$stmt_machines = $pdo->query("SELECT * FROM coffee_machines");
$machines = $stmt_machines->fetchAll(PDO::FETCH_ASSOC);

// Получаем напитки
$stmt_drinks = $pdo->query("SELECT * FROM drinks");
$drinks = $stmt_drinks->fetchAll(PDO::FETCH_ASSOC);


// Количество продуктов на одной странице
$items_per_page = 4;

// Получаем текущую страницу из URL, если не задано, то по умолчанию первая
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Запрос для кофе с пагинацией
$stmt_coffee = $pdo->prepare("SELECT * FROM coffee LIMIT :limit OFFSET :offset");
$stmt_coffee->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt_coffee->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt_coffee->execute();
$coffees = $stmt_coffee->fetchAll(PDO::FETCH_ASSOC);

// Получаем общее количество записей в таблице
$stmt_total = $pdo->query("SELECT COUNT(*) FROM coffee");
$total_items = $stmt_total->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffeee Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Раздел каталога кофе -->
    <main>
        <section id="popular-section" class="section-main">
            <div class="container">
                <h2 class="section-title">Популярные продукты</h2>
                <h3 class="section-subtitle">Кофе</h3>
                <div class="popular-wrap">
                    <?php foreach ($coffees as $coffee): ?>
                        <div class="popular">
                            <img class="popular__img" src="<?= htmlspecialchars($coffee['image']) ?>" alt="<?= htmlspecialchars($coffee['name']) ?>">
                            <div class="rating">
                                <h4 class="rating__title">крепость</h4>
                                <ul class="rating__list">
                                    <?php for ($i = 0; $i < $coffee['rating']; $i++): ?>
                                        <li class="rating__item material-icons-outlined">star</li>
                                    <?php endfor; ?>
                                    <?php for ($i = $coffee['rating']; $i < 5; $i++): ?>
                                        <li class="rating__item material-icons-outlined">star_border</li>
                                    <?php endfor; ?>
                                </ul>
                            </div>
                            <h3 class="popular__title"><?= htmlspecialchars($coffee['name']) ?></h3>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Раздел слайдера для устройств -->
        <section id="discover-section" class="section-main">
            <div class="container">
                <div class="discover-wrap">
                    <img class="discover-img" src="img/discover.png" alt="Coffee Machine">
                    <div class="discover">
                        <h3 class="discover__title section-subtitle">Преимущества нашей кофемашины</h3>
                        <p class="section__text">Профессиональная экстракция: Кофемашина обеспечивает идеальное давление и температуру для раскрытия вкуса каждого сорта зерен.</p>
                        <p class="section__text">Широкий выбор: Помимо классических вариантов, кофемашина может предлагать уникальные напитки, которых нет в стандартных меню кафе.</p>
                        <p class="section__text">Персонализация напитков: Возможность регулировать крепость, объем и температуру.</p>
                        
                    </div>
                </div>
            </div>
        </section>

        <!-- Раздел меню напитков -->
        <section id="menu-section" class="section-main">
            <div class="container">
                <h2 class="section-title">Меню</h2>
                <h3 class="section-subtitle">Меню напитков</h3>
                <ul class="menu-wrap">
                    <?php foreach ($drinks as $drink): ?>
                        <li class="menu">
                            <img class="menu__img" src="<?= htmlspecialchars($drink['image']) ?>" alt="<?= htmlspecialchars($drink['name']) ?>">
                            <h3 class="menu__title"><?= htmlspecialchars($drink['name']) ?></h3>
                            <b class="menu__price"><?= htmlspecialchars($drink['price']) ?></b>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>