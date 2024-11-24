<?php
require 'db.php';

$errorMessage = ""; // Переменная для хранения сообщений об ошибках

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($name) && !empty($email) && !empty($password)) {
        // Хэшируем пароль
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Проверка, существует ли пользователь
            $stmt = $pdo->prepare("SELECT id FROM users WHERE name = ?");
            $stmt->execute([$name]);

            if ($stmt->rowCount() > 0) {
                $errorMessage = "Пользователь с таким именем уже существует.";
            } else {
                // Добавляем пользователя в базу
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashedPassword]);

                // Редирект на страницу входа
                header("Location: login.php");
                exit; // Важно, чтобы код после редиректа не выполнялся
            }
        } catch (PDOException $e) {
            $errorMessage = "Ошибка: " . $e->getMessage();
        }
    } else {
        $errorMessage = "Пожалуйста, заполните все поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>

<?php include 'header.php'; ?>

<section class="auth-form">
    <h1 class="auth-form__title">Регистрация</h1>
    <p class="auth-form__text">Заполните форму, чтобы создать новый аккаунт.</p>
    <form action="register.php" method="post">
        <input class="auth-form__input" type="text" name="name" placeholder="Введите ваше имя" required>
        <input class="auth-form__input" type="email" name="email" placeholder="Введите ваш email" required>
        <input class="auth-form__input" type="password" name="password" placeholder="Введите ваш пароль" required>
        <input class="auth-form__input" type="password" name="confirm_password" placeholder="Повторите ваш пароль" required>
        <button class="auth-form__btn" type="submit">Зарегистрироваться</button>

        <?php if (!empty($errorMessage)): ?>
    <p style="color: red;"><?= $errorMessage ?></p>
<?php endif; ?>
    </form>
    <a class="auth-form__link" href="login.php">Уже есть аккаунт? Войдите!</a>
</section>

<?php include 'footer.php'; ?>

</html>




