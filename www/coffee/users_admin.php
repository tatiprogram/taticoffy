<?php
// Подключение к базе данных
include 'db.php';

// Проверка, что пользователь авторизован и является админом
session_start();
if ($_SESSION['user_name'] != 'admin') {
    header('Location: index.php'); // Перенаправление на главную, если не админ
    exit();
}

// Удаление пользователя, если запрос на удаление был отправлен
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    try {
        // Начало транзакции
        $pdo->beginTransaction();

        // Удаление товаров пользователя из таблицы cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Удаление пользователя из таблицы users
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        // Подтверждение транзакции
        $pdo->commit();

        echo "Пользователь и его товары успешно удалены.";
    } catch (PDOException $e) {
        // Откат транзакции в случае ошибки
        $pdo->rollBack();
        echo "Ошибка при удалении: " . $e->getMessage();
    }
}

// Получаем всех пользователей
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'header.php'; ?>
<h2>Пользователи</h2>
<link rel="stylesheet" href="style.css">
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Электронная почта</th>
            <th>Удалить</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя и все его товары?');">Удалить</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
