<?php
$db_host = "localhost";     // Хост базы данных
$db_user = "root"; // Имя пользователя базы данных
$db_password = "12345678"; // Пароль пользователя базы данных
$db_name = "housing"; // Имя базы данных

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
