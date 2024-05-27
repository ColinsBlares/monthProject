<?php
session_start();
// Подключение к базе данных
$conn = new mysqli("localhost", "root", "12345678", "housing");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверка аутентификации админа
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Выход из системы
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit();
}

// Обработка отправки сообщения
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'], $_POST['receiver_id'])) {
    $message = $conn->real_escape_string($_POST['message']);
    $receiver_id = $conn->real_escape_string($_POST['receiver_id']);
    $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (9, '$receiver_id', '$message')";
    if ($conn->query($sql)) {
        // Перенаправление для избежания повторной отправки формы
        header("Location: admin_panel.php");
        exit();
    }
}

// Обработка добавления объявления
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'], $_POST['content'], $_POST['start_date'], $_POST['end_date'], $_POST['importance'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $start_date = $conn->real_escape_string($_POST['start_date']);
    $end_date = $conn->real_escape_string($_POST['end_date']);
    $importance = $conn->real_escape_string($_POST['importance']);
    $sql = "INSERT INTO announcements (title, content, start_date, end_date, importance) VALUES ('$title', '$content', '$start_date', '$end_date', '$importance')";
    if ($conn->query($sql)) {
        // Перенаправление для избежания повторной отправки формы
        header("Location: admin_panel.php");
        exit();
    }
}

// Обработка удаления объявления
if (isset($_GET['delete_announcement'])) {
    $announcement_id = intval($_GET['delete_announcement']);
    $sql = "DELETE FROM announcements WHERE id = $announcement_id";
    if ($conn->query($sql)) {
        header("Location: admin_panel.php");
        exit();
    }
}

// Получение всех сообщений вместе с информацией о пользователях
$sql = "SELECT messages.*, residents.id as resident_id, residents.full_name, residents.phone_number 
        FROM messages 
        JOIN residents ON messages.sender_id = residents.id 
        ORDER BY messages.created_at DESC";
$result = $conn->query($sql);

// Получение всех объявлений
$announcements_sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$announcements_result = $conn->query($announcements_sql);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Admin Panel</h1>
    <a href='?logout' class='btn btn-danger mb-3'>Log Out</a>

    <form method="post" class="mb-4">
        <div class="mb-3">
            <label for="receiver_id" class="form-label">Выбор пользователя</label>
            <select class="form-select" id="receiver_id" name="receiver_id">
                <?php
                $users = $conn->query("SELECT id, full_name FROM residents ORDER BY full_name ASC");
                while ($user = $users->fetch_assoc()) {
                    echo "<option value='{$user['id']}'>{$user['full_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Сообщение</label>
            <textarea class="form-control" id="message" name="message" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Ответить</button>
    </form>

    <h2>Добавить объявление</h2>
    <form method="post" class="mb-4">
        <div class="mb-3">
            <label for="title" class="form-label">Заголовок</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Содержание</label>
            <textarea class="form-control" id="content" name="content" required></textarea>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Дата начала</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">Дата окончания</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>
        <div class="mb-3">
            <label for="importance" class="form-label">Важность</label>
            <select class="form-select" id="importance" name="importance">
                <option value="low">Низкая</option>
                <option value="medium">Средняя</option>
                <option value="high">Высокая</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Добавить объявление</button>
    </form>

    <h2>Объявления</h2>
    <?php
    if ($announcements_result->num_rows > 0) {
        while ($row = $announcements_result->fetch_assoc()) {
            echo "<div class='alert alert-info'>";
            echo "<h4>" . htmlspecialchars($row['title']) . "</h4>";
            echo "<p>" . htmlspecialchars($row['content']) . "</p>";
            echo "<small>Valid from " . htmlspecialchars($row['start_date']) . " to " . htmlspecialchars($row['end_date']) . "</small>";
            echo "<form method='get' class='mt-2'>";
            echo "<input type='hidden' name='delete_announcement' value='{$row['id']}'>";
            echo "<button type='submit' class='btn btn-danger btn-sm'>Удалить</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p>No announcements yet.</p>";
    }
    ?>

    <h2>Сообщения</h2>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $card_class = $row['sender_id'] == 1 ? 'alert alert-success' : 'alert alert-secondary';
            echo "<div class='$card_class'>";
            echo "<h4>{$row['full_name']} (ID: {$row['resident_id']}, Телефон: {$row['phone_number']})</h4>";
            echo "<p> Текст: " . htmlspecialchars($row['message']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>Пока тут тихо. Даже слишком</p>";
    }
    ?>
</div>
</body>
</html>
