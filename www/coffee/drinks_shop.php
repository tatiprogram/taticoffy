<?php
// Подключение к базе данных
session_start();
require 'db.php';

// Получение данных из базы данных
// Получаем напитки
$stmt_drinks = $pdo->query("SELECT id, name, description, price, image FROM drinks");
$drinks = $stmt_drinks->fetchAll(PDO::FETCH_ASSOC);

// Количество продуктов на одной странице (4 элемента: 1 строка по 4 элемента)
$items_per_page = 6;

// Получаем текущую страницу из URL, если не задано, то по умолчанию первая
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Запрос для напитков с пагинацией
$stmt_drinks = $pdo->prepare("SELECT id, name, description, price, image FROM drinks LIMIT :limit OFFSET :offset");
$stmt_drinks->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt_drinks->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt_drinks->execute();
$drinks = $stmt_drinks->fetchAll(PDO::FETCH_ASSOC);

// Получаем общее количество записей в таблице
$stmt_total = $pdo->query("SELECT COUNT(*) FROM drinks");
$total_items = $stmt_total->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

if (isset($_POST['add_to_cart'])) {
    $drink_id = (int)$_POST['drink_id'];
    $drink_name = $_POST['drink_name'];
    $drink_price = (float)$_POST['drink_price'];
    $user_id = $_SESSION['user_id']; // ID текущего пользователя

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Инициализация корзины
    }

    // Локальное добавление в сессию
    if (isset($_SESSION['cart'][$drink_id])) {
        $_SESSION['cart'][$drink_id]['quantity']++;
    } else {
        $_SESSION['cart'][$drink_id] = [
            'name' => $drink_name,
            'price' => $drink_price,
            'quantity' => 1,
        ];
    }

    // Сохранение в базу данных
    $stmt = $pdo->prepare("
        INSERT INTO cart (user_id, drink_id, quantity)
        VALUES (:user_id, :drink_id, 1)
        ON DUPLICATE KEY UPDATE quantity = quantity + 1
    ");
    $stmt->execute([
        ':user_id' => $user_id,
        ':drink_id' => $drink_id,
    ]);

    // Перенаправление
    header("Location: drinks_shop.php?page=" . $_GET['page']);
    exit;
}


// Получение данных о напитках
$stmt_drinks = $pdo->query("SELECT id, name, description, price, image FROM drinks");
$drinks = $stmt_drinks->fetchAll(PDO::FETCH_ASSOC);

// Пагинация
$items_per_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$stmt_drinks = $pdo->prepare("SELECT id, name, description, price, image FROM drinks LIMIT :limit OFFSET :offset");
$stmt_drinks->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt_drinks->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt_drinks->execute();
$drinks = $stmt_drinks->fetchAll(PDO::FETCH_ASSOC);

$stmt_total = $pdo->query("SELECT COUNT(*) FROM drinks");
$total_items = $stmt_total->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drink Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Раздел каталога напитков -->
    <main>
        <section id="menu-section" class="section-main">
            <div class="container">
                <h2 class="section-title">Меню</h2>
                <h3 class="section-subtitle">Меню напитков</h3>
                <ul class="menu-wrap">
                <?php foreach ($drinks as $drink): ?>
        <li class="menu">
            <img class="menu__img" src="<?= htmlspecialchars($drink['image']) ?>" alt="<?= htmlspecialchars($drink['name']) ?>">
            <h3 class="menu__title"><?= htmlspecialchars($drink['name']) ?></h3>
            <b class="menu__price"><?= htmlspecialchars($drink['price']) ?> ₽</b>

            <!-- Форма для добавления в корзину -->
            <form method="POST" action="drinks_shop.php?page=<?= $page ?>">
                <input type="hidden" name="drink_id" value="<?= $drink['id'] ?>">
                <input type="hidden" name="drink_name" value="<?= htmlspecialchars($drink['name']) ?>">
                <input type="hidden" name="drink_price" value="<?= htmlspecialchars($drink['price']) ?>">
                <button type="submit" name="add_to_cart" class="menu__btn small-btn">В корзину</button>
            </form>
        </li>
    <?php endforeach; ?>

                <!-- Пагинация -->
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="drinks_shop.php?page=<?= $page - 1 ?>" class="pagination__link">« Назад</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="drinks_shop.php?page=<?= $i ?>" class="pagination__link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="drinks_shop.php?page=<?= $page + 1 ?>" class="pagination__link">Вперед »</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
