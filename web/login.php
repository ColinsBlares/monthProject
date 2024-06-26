<?php
session_start();
include 'config.php';

// Проверка наличия куки и автоматический вход
if (isset($_COOKIE['username']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    header("Location: message.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'], $_POST['password'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT id FROM residents WHERE full_name = '$username' AND phone_number = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];

        // Если пользователь выбрал опцию "Запомнить меня"
        if (!empty($_POST['remember'])) {
            setcookie('username', $username, time() + (86400 * 30), "/"); // Куки на 30 дней
            setcookie('user_id', $user['id'], time() + (86400 * 30), "/"); // Куки на 30 дней
        } else {
            // Если опция не выбрана, удаляем куки, если они были
            if (isset($_COOKIE['username'])) {
                setcookie('username', '', time() - 3600, "/");
                setcookie('user_id', '', time() - 3600, "/");
            }
        }

        header("Location: message.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php
        if (isset($_COOKIE["theme"]) && $_COOKIE["theme"] === "dark") {
            echo '<link href="/styles/dark-theme.css" rel="stylesheet">';
        } else {
            echo '<link href="/styles/light-theme.css" rel="stylesheet">';
        }
    ?>
</head>
<body>
<div class="container mt-5">
    <h2>Авторизация</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="login.php" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">ФИО</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Телефонный номер</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Запомнить меня</label>
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
        <a href="index.php" class="btn btn-secondary">Назад</a>
    </form>

    <div class="theme-toggle">
        <label for="theme-toggle-checkbox">Тема:</label>
        <input type="checkbox" id="theme-toggle-checkbox" <?php if (isset($_COOKIE["theme"]) && $_COOKIE["theme"] === "dark") echo "checked"; ?>>
    </div>
</div>
<script src="scripts/script.js" defer></script> <!-- Подключение внешнего файла JavaScript -->
</body>
</html>
