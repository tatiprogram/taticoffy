<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header id="header-section">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined">
    <div class="container container-header">
        <div class="header">
            <nav class="nav-main">
                <ul class="nav-main__list">
                    <li class="nav-main__item">
                        <a class="nav-main__link nav-main__link_selected" href="/coffee/index.php">Главная</a>
                    </li>
                    <li class="nav-main__item">
                        <a class="nav-main__link" href="/coffee/shop.php">Магазин</a>
                        <ul class="dropdown-menu">
                            <li><a href="/coffee/drinks_shop.php">Напитки</a></li>
                        </ul>
                    </li>
                    <li class="nav-main__item">
                        <a class="nav-main__link" href="/coffee/contacts.php">Контакты</a>
                    </li>
                </ul>
                <img class="header__logo" src="img/logo.svg" alt="#">
            </nav>
            <div class="header-action">
                <a href="cart.php" button class="header-action__cart material-icons-outlined">local_mall</button>
                <div class="header-action">
                    
                <?php if (isset($_SESSION['user_name'])): ?>
                    <a class="header-action__user"><?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
                    <a href="logout.php" class="header-action__logout">Выйти</a>
                    <?php if ($_SESSION['user_name'] == 'admin'): ?>
                        <a href="users_admin.php" class="header-action__admin-link">Пользователи</a>
                        <a href="comments_admin.php" class="header-action__admin-link">Комментарии</a>
                    <?php endif; ?>
                <?php else: ?>
                <a href="login.php" class="header-action__login">Войти</a>
                <a href="register.php" class="header-action__register">Зарегистрироваться</a>
            <?php endif; ?>
        </div>
            </div>
        </div>
    </div>
</header>
