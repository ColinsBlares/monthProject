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

// Получение количества пользователей
$user_count_sql = "SELECT COUNT(*) as user_count FROM residents";
$user_count_result = $conn->query($user_count_sql);
$user_count = $user_count_result->fetch_assoc()['user_count'];

// Получение количества сообщений
$message_count_sql = "SELECT COUNT(*) as message_count FROM messages";
$message_count_result = $conn->query($message_count_sql);
$message_count = $message_count_result->fetch_assoc()['message_count'];

// Получение всех пользователей
$users_sql = "SELECT id, full_name, phone_number FROM residents ORDER BY full_name ASC";
$users_result = $conn->query($users_sql);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .message-container {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #f8f9fa;
            padding: 15px;
        }
        .content {
            margin-left: 270px;
            padding: 15px;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Админ панель</h2>
    <a href='?logout' class='btn btn-danger mb-3'>Выйти</a>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="#messages" data-bs-toggle="tab">Сообщения</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#announcements" data-bs-toggle="tab">Объявления</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#create_announcement" data-bs-toggle="tab">Создать Объявление</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#users" data-bs-toggle="tab">Пользователи</a>
        </li>
    </ul>
</div>
<div class="content">
    <div class="tab-content">
        <div class="tab-pane fade show active" id="messages">
            <h3>Сообщения</h3>
            <form method="post" class="mb-4">
                <div class="mb-3">
                    <label for="receiver_id" class="form-label">Выбор пользователя</label>
                    <select class="form-select" id="receiver_id" name="receiver_id">
                        <?php
                        $users_result = $conn->query($users_sql); // Повторное выполнение запроса для формы
                        while ($user = $users_result->fetch_assoc()) {
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

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $card_class = $row['sender_id'] == 1 ? 'alert alert-success message-container' : 'alert alert-secondary message-container';
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
        <div class="tab-pane fade" id="announcements">
            <h3>Объявления</h3>
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
        </div>
        <div class="tab-pane fade" id="create_announcement">
            <h3>Создать Объявление</h3>
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
                    <select class="form-select" id="importance" name="importance" required>
                        <option value="low">Низкая</option>
                        <option value="medium">Средняя</option>
                        <option value="high">Высокая</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>
            </form>
        </div>
        <div class="tab-pane fade" id="users">
            <h3>Информация о базе данных</h3>
            <p>Количество пользователей: <?php echo $user_count; ?></p>
            <p>Количество сообщений: <?php echo $message_count; ?></p>

            <h2>Пользователи</h2>
            <?php
            $users_sql = "SELECT id, full_name, phone_number FROM residents ORDER BY full_name ASC";
            $users_result = $conn->query($users_sql);
            if ($users_result->num_rows > 0) {
                echo "<ul class='list-group'>";
                while ($row = $users_result->fetch_assoc()) {
                    echo "<li class='list-group-item'>";
                    echo "<strong>ФИО:</strong> " . htmlspecialchars($row['full_name']) . "<br>";
                    echo "<strong>Телефон:</strong> " . htmlspecialchars($row['phone_number']);
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Нет пользователей.</p>";
            }
            ?>
        </div>
    </div>
</div>
<script>
    document.getElementById('sidebar-toggler').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('active');
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>