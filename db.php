<?php
$host = 'localhost'; // Хост (обычно localhost)
$username = 'root'; // Имя пользователя для базы данных
$password = ''; // Пароль для базы данных
$database = 'kurs'; // Имя базы данных

// Подключение к базе данных
$conn = mysqli_connect($host, $username, $password, $database);

// Проверка подключения
if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}
?>
