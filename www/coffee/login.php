<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($email) && !empty($password)) {
        try {
            // Проверяем, существует ли пользователь
            $stmt = $pdo->prepare("SELECT id, name, password_hash FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Авторизация успешна
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                // Перенаправляем на главную страницу
                header("Location: index.php");
                exit;
            } else {
                $error_message = "Неправильный email или пароль.";
            }
        } catch (PDOException $e) {
            die("Ошибка: " . $e->getMessage());
        }
    } else {
        $error_message = "Пожалуйста, заполните все поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>

<?php include 'header.php'; ?>

<section class="auth-form">
    <h1 class="auth-form__title">Вход</h1>
    <p class="auth-form__text">Пожалуйста, введите свои данные, чтобы войти в систему.</p>
    <form action="login.php" method="post">
        <input class="auth-form__input" type="email" name="email" placeholder="Введите ваш email" required>
        <input class="auth-form__input" type="password" name="password" placeholder="Введите ваш пароль" required>
        <button class="auth-form__btn" type="submit">Войти</button>
    </form>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <a class="auth-form__link" href="register.php">Еще нет аккаунта? Зарегистрируйтесь!</a>
</section>

<?php include 'footer.php'; ?>

</html>
