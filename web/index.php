<?php
$conn = new mysqli("localhost", "root", "12345678", "housing");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_date = date('Y-m-d');
$sql = "SELECT * FROM announcements WHERE start_date <= '$current_date' AND end_date >= '$current_date' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добро пожаловать</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            width: 100%;
            margin-bottom: 10px;
        }
        .announcements {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-header">
                    <h3>Добро пожаловать</h3>
                </div>
                <div class="card-body">
                    <a href="login.php" class="btn btn-primary btn-custom">Войти</a>
                    <a href="admin_login.php" class="btn btn-secondary btn-custom">Войти как админ</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row announcements justify-content-center">
        <div class="col-md-6">
            <h2>Текущие объявления</h2>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='alert alert-info'>";
                    echo "<h4>" . htmlspecialchars($row['title']) . "</h4>";
                    echo "<p>" . htmlspecialchars($row['content']) . "</p>";
                    echo "<small>Действительно с " . htmlspecialchars($row['start_date']) . " по " . htmlspecialchars($row['end_date']) . "</small>";
                    echo "</div>";
                }
            } else {
                echo "<p>Нет текущих объявлений.</p>";
            }
            ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
