<?php
session_start();
include 'db.php'; // Подключение к базе данных

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    // Перенаправление на страницу входа
    header("Location: login.php");
    exit(); // Завершаем выполнение скрипта после редиректа
}

// Получение текущего user_id из сессии
$user_id = $_SESSION['user_id'];

try {
    // Получаем товары из таблицы `cart` для текущего пользователя
    $stmt = $pdo->prepare("
        SELECT 
            cart.quantity,
            drinks.name,
            drinks.price
        FROM cart
        JOIN drinks ON cart.drink_id = drinks.id
        WHERE cart.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC); // Извлекаем данные в виде массива
} catch (PDOException $e) {
    die("Ошибка получения данных из базы данных: " . $e->getMessage());
}

// Подсчет общей суммы
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity']; // Умножаем цену на количество и добавляем к общей сумме
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Righteous&family=Urbanist:wght@500;600&display=swap">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

    <div class="cart-container">
        <h1 class="cart-title">Моя корзина</h1>
        <?php if (!empty($cart_items)): ?>
            <ul class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <li class="cart-item">
                        <span class="cart-item-name"><?= htmlspecialchars($item['name']) ?></span>
                        <span class="cart-item-quantity"><?= htmlspecialchars($item['quantity']) ?> шт.</span>
                        <span class="cart-item-price"><?= htmlspecialchars($item['price']) ?> ₽</span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Подсчет общей суммы -->
            <div class="cart-total">
                <strong>Итого:</strong> <?= number_format($total_price, 2, ',', ' ') ?> ₽
            </div>

            <button class="btn-primary">Оформить заказ</button>
        <?php else: ?>
            <p class="empty-cart">Ваша корзина пуста.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
