<?php
session_start();
include 'db.php'; // Подключение к базе данных

// Получение ID новости из параметра URL
if (isset($_GET['id'])) {
    $news_id = (int)$_GET['id'];
} else {
    die("Ошибка: ID новости не передан.");
}

try {
    // Получаем подробности новости из базы данных
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = :id");
    $stmt->execute(['id' => $news_id]);
    $news_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$news_item) {
        die("Новость не найдена.");
    }
} catch (PDOException $e) {
    die("Ошибка получения данных из базы данных: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($news_item['title']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section id="news-detail" class="section-main">
            <div class="container">
                <h1 class="section-title"><?= htmlspecialchars($news_item['title']) ?></h1>
                <div class="news-info">
                    <span class="news__author">By: <?= htmlspecialchars($news_item['author']) ?></span>
                    <time class="news__date" datetime="<?= $news_item['date'] ?>"><?= date('d.m.Y', strtotime($news_item['date'])) ?></time>
                </div>
                <img class="news__img" src="<?= htmlspecialchars($news_item['image']) ?>" alt="<?= htmlspecialchars($news_item['title']) ?>">
                <div class="news-content">
                    <p><?= htmlspecialchars($news_item['content']) ?></p>
                </div>
                <a href="index.php" class="btn-primary">Назад к новостям</a>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
