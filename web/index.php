<?php
$conn = new mysqli("localhost", "root", "12345678", "housing");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_date = date('Y-m-d');
$sql = "SELECT * FROM announcements WHERE start_date <= '$current_date' AND end_date >= '$current_date' ORDER BY FIELD(importance, 'high', 'medium', 'low')";
$result = $conn->query($sql);

if (!$result) {
    die("Error in SQL query: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добро пожаловать</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/styles/style.css" rel="stylesheet"> <!-- Подключение внешнего файла стилей -->
    <script src="scripts/script.js" defer></script> <!-- Подключение внешнего файла JavaScript -->
</head>
<body>
<div class="container mt-5">
    <div class="text-center mb-4">
        <h1 class="display-4">Добро пожаловать</h1>
        <p class="lead">Выберите действие:</p>
    </div>

    <div class="text-center mb-5">
        <div class="btn-group" role="group">
            <a href="login.php" class="btn btn-primary btn-lg me-3">Войти</a>
            <a href="admin_login.php" class="btn btn-outline-secondary btn-lg">Войти как админ</a>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <h2 class="text-center mb-4">Текущие объявления</h2>
            <div class="d-flex justify-content-center mb-4">
                <label for="theme-toggle-checkbox" class="form-switch-label me-2">Тема:</label>
                <div class="form-switch">
                    <input type="checkbox" id="theme-toggle-checkbox" class="form-switch-input">
                    <label for="theme-toggle-checkbox" class="form-switch-slider"></label>
                </div>
            </div>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $importance_class = strtolower($row['importance']) . '-importance';
            ?>
                    <div class="card mb-3 announcement <?php echo $importance_class; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['content']); ?></p>
                            <p class="card-text"><small class="text-muted">Действительно с <?php echo htmlspecialchars($row['start_date']); ?> по <?php echo htmlspecialchars($row['end_date']); ?></small></p>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-center mt-4'>Нет текущих объявлений.</p>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
