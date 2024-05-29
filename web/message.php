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

$conn = new mysqli("localhost", "root", "12345678", "housing");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Обработка отправки сообщения
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $conn->real_escape_string($_POST['message']);
    $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$user_id', '9', '$message')";  // 9 - ID администратора
    if ($conn->query($sql)) {
        header("Location: message.php"); // PRG чтобы избежать дублирования
        exit();
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
    <h1>Ваши сообщения</h1>
    <a href="?logout" class="btn btn-danger mb-3">Выйти</a>
    <form method="post" class="mb-4">
        <div class="mb-3">
            <label for="message" class="form-label">Ваше сообщение</label>
            <textarea class="form-control emoji-picker" id="message" name="message" required></textarea>
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
        $(".emoji-picker").emojioneArea();
    });
</script>
</body>
</html>
