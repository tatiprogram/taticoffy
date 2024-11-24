<?php
// Подключение к базе данных
include 'db.php';

// Проверка, что пользователь авторизован и является админом
session_start();
if ($_SESSION['user_name'] != 'admin') {
    header('Location: index.php'); // Перенаправление на главную, если не админ
    exit();
}

// Удаление комментария, если запрос на удаление был отправлен
if (isset($_GET['delete'])) {
    $comment_id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM comment WHERE id = :id");
        $stmt->bindParam(':id', $comment_id);
        $stmt->execute();
        echo "Комментарий успешно удален.";
    } catch (PDOException $e) {
        echo "Ошибка при удалении комментария: " . $e->getMessage();
    }
}

// Получаем все комментарии
$stmt = $pdo->query("SELECT * FROM comment");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'header.php'; ?>
<h2>Комментарии</h2>
<link rel="stylesheet" href="style.css">
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Текст</th>
            <th>Рейтинг</th>
            <th>Удалить</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($comments as $comment): ?>
        <tr>
            <td><?php echo htmlspecialchars($comment['id']); ?></td>
            <td><?php echo htmlspecialchars($comment['name']); ?></td>
            <td><?php echo htmlspecialchars($comment['text']); ?></td>
            <td><?php echo htmlspecialchars($comment['rating']); ?></td>
            <td><a href="?delete=<?php echo $comment['id']; ?>">Удалить</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>