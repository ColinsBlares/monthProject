<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

include 'config.php'; // Подключаем файл с подключением к базе данных
$user_id = $_SESSION['user_id'];

// Получение имени пользователя
$sql_user = "SELECT full_name FROM residents WHERE id = '$user_id'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $user = $result_user->fetch_assoc();
    $username = $user['full_name'];
} else {
    $username = "Гость"; // На случай если что-то пойдет не так
}

$error = ''; // Переменная для хранения сообщений об ошибках

// Обработка отправки сообщения
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $conn->real_escape_string($_POST['message']);
    if (strlen($message) > 255) {
        $error = "Сообщение не должно превышать 255 символов.";
    } else {
        $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$user_id', '9', '$message')";  // 9 - ID администратора
        if ($conn->query($sql)) {
            header("Location: message.php"); // PRG чтобы избежать дублирования
            exit();
        }
    }
}

$sql = "SELECT message, sender_id FROM messages WHERE sender_id = '$user_id' OR receiver_id = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сообщения</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.min.css" />
    <style>
        .message-container {
            max-height: 500px; 
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .alert {
            margin-bottom: 10px; 
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Здравствуйте, <?= htmlspecialchars($username) ?></h1>
    <h2>Ваши сообщения</h2>
    <a href="?logout" class="btn btn-danger mb-3">Выйти</a>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" class="mb-4">
        <div class="mb-3">
            <label for="message" class="form-label">Ваше сообщение</label>
            <textarea class="form-control emoji-picker" id="message" name="message" maxlength="255" required></textarea>
            <div class="form-text">Максимальная длина сообщения 255 символов.</div>
        </div>
        <button type="submit" class="btn btn-primary">Отправить сообщение</button>
    </form>

    <div class="message-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $class = $row['sender_id'] == 9 ? 'alert alert-warning' : 'alert alert-info';  // Выделяем ответы от администратора
                $title = $row['sender_id'] == 9 ? 'Ответ от администратора' : 'Ваше сообщение';
                echo "<div class='$class'>";
                echo "<h4>$title</h4>";
                echo "<p> Текст: " . htmlspecialchars($row['message']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Тут тихо... Даже слишком</p>";
        }
        ?>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.min.js"></script>
<script>
    $(document).ready(function(){
        $(".emoji-picker").emojioneArea({
            events: {
                keyup: function (editor, event) {
                    var textLength = this.getText().length;
                    if (textLength > 255) {
                        alert('Сообщение не должно превышать 255 символов.');
                        var truncatedText = this.getText().substring(0, 255);
                        this.setText(truncatedText);
                    }
                }
            }
        });
    });
</script>
</body>
</html>
