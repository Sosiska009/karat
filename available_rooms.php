<?php
include('db.php'); // Подключение к базе данных

// Получаем категорию номеров из GET параметра
$category = $_GET['category'];

// Получаем доступные номера по выбранной категории
$sql = "SELECT * FROM rooms WHERE category_id = (SELECT category_id FROM room_categories WHERE name = ?) AND is_available = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$category]);

// Выводим доступные номера
$rooms = $stmt->fetchAll();
?>

<h2>Доступные номера категории "<?php echo ucfirst($category); ?>"</h2>

<?php if (count($rooms) > 0): ?>
    <ul>
        <?php foreach ($rooms as $room): ?>
            <li>
                Номер: <?php echo $room['room_number']; ?>, Цена: <?php echo $room['price']; ?> рублей
                <form action="booking.php" method="POST">
                    <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
                    <button type="submit">Забронировать</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Нет доступных номеров в этой категории.</p>
<?php endif; ?>
