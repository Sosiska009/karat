<?php
include('db.php'); // Подключаем базу данных

// Получаем категорию из параметра
$category = $_GET['category'];

// Запрос номеров по категории
$sql = "SELECT * FROM rooms WHERE category_id = (SELECT category_id FROM room_categories WHERE name = ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$category]);
$rooms = $stmt->fetchAll();

// Возвращаем данные в формате JSON
echo json_encode(['rooms' => $rooms]);
?>

