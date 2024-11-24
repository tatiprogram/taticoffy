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
        <section id="banner-section">
            <div class="container">
                <div class="banner">
                    <div class="banner-info">
                        <h2 class="banner__header">Enjoy Your Morning Coffee.</h2>
                        <p class="banner__text">Кофе варится путем обжаривания зеленых кофейных зерен в жаровне на горячих углях</p>
                        <a href="drinks_shop.php"  class="banner__btn btn-primary">Попробовать кофе</a>
                    </div>
                </div>
            </div>
        </section>
        <div class="container">
            <ol class="features section-main">
                <li class="features__item">
                    <span class="features__item_dark">01</span>Лучший вкус кофе
                    <img class="features__img" src="img/feature-1.jpg" alt="#">
                </li>
                <li class="features__item">
                    <span class="features__item_dark">02</span>Место где можно отдохнуть
                    <img class="features__img" src="img/feature-2.jpg" alt="#">
                </li>
                <li class="features__item">
                    <span class="features__item_dark">03</span>Правильная жарка
                    <img class="features__img" src="img/feature-3.jpg" alt="#">
                </li>
            </ol>
        </div>
        <section id="history-section" class="section-main">
            <div class="container">
                <div class="history-wrap">
                    <img class="history-wrap__img" src="img/feature-2.jpg" alt="#">
                    <img class="history-wrap__img" src="img/feature-4.jpg" alt="#">
                    <img class="history-wrap__img" src="img/feature-1.jpg" alt="#">
                    <div class="history">
                        <h2 class="history__title section-title">Ваша история</h2>
                        <h3 class="history__subtitle section-subtitle">Посетите<br>
                            незабываемый мир кофе</h3>
                        <p class="history__text section__text">
                            Приглашаем вас на уникальное путешествие в мир ароматов и вкусов в нашем кофейном уголке. У нас вы откроете для себя не только высококачественный кофе, созданный вдохновенными бариста, но и атмосферу истинного уюта, где каждая чашка становится неповторимым произведением искусства. Здесь вы не просто пьете кофе, вы наслаждаетесь волшебством каждого момента, делая наше заведение идеальным местом для встреч, творчества и умиротворенного отдыха.</p>
                    </div>
                </div>
            </div>
        </section>
        <?php
// Подключение к базе данных
include 'db.php';

try {
    // Получаем только 3 новости с самой ранней датой
    $stmt = $pdo->query("SELECT id, title, author, date, content, image FROM news ORDER BY date DESC LIMIT 3");

    $news = $stmt->fetchAll(PDO::FETCH_ASSOC); // Сохраняем все новости в переменной $news
} catch (PDOException $e) {
    die("Ошибка получения новостей из базы данных: " . $e->getMessage());
}
?>

<section id="section-news" class="section-main">
    <div class="container">
        <h2 class="section-title">Статьи</h2>
        <h3 class="section-subtitle">Кофе-новости</h3>
        <div class="news-wrap">
            <?php if (!empty($news)): // Проверяем, есть ли новости ?>
                <?php foreach ($news as $item): ?>
                    <div class="news">
                        <img class="news__img" src="<?= htmlspecialchars($item['image']) ?>" alt="#">
                        <div class="news-description">
                            <div class="news-info">
                                <span class="news__author"><span class="news__author_dark">By: </span><?= htmlspecialchars($item['author']) ?></span>
                                <time class="news__date" datetime="<?= $item['date'] ?>"><?= date('d.m.Y', strtotime($item['date'])) ?></time>
                            </div>
                            <p class="news__text"><?= htmlspecialchars(mb_substr($item['content'], 0, 50)) ?>...</p> <!-- Обрезаем текст до 50 символов -->
                            <a class="news__link" href="news.php?id=<?= $item['id'] ?>">Больше<span class="material-icons-outlined">arrow_forward</span></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Новостей нет.</p>
            <?php endif; ?>
        </div>
    </div>
</section>



        <?php include 'footer.php'; ?>
    </main>
</body>
</html>