<?php
// Подключаем базу данных
include('db.php');

// Проверяем, что пользователь является администратором
session_start();
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Обработка подтверждения бронирования
if (isset($_GET['confirm_booking'])) {
    $booking_id = $_GET['confirm_booking'];
    $sql = "UPDATE bookings SET status = 'confirmed' WHERE booking_id = $booking_id";
    if (mysqli_query($conn, $sql)) {
        echo "Статус бронирования изменен.";
    } else {
        echo "Ошибка при обновлении статуса: " . mysqli_error($conn);
    }
}

// Получаем все бронирования
$sql = "SELECT b.booking_id, b.check_in, b.check_out, b.status, r.room_number, u.first_name, u.last_name
        FROM bookings b
        JOIN rooms r ON b.room_id = r.room_id
        JOIN users u ON b.user_id = u.user_id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>
    <link rel="stylesheet" href="style5.css">
</head>
<body>
    <div class="container">
        <h2>Админ панель</h2>
        <div class="admin-container">
            <h3>Список бронирований</h3>
            <table class="booking-list">
                <tr>
                    <th>ID</th>
                    <th>Номер</th>
                    <th>Имя пользователя</th>
                    <th>Дата заезда</th>
                    <th>Дата выезда</th>
                    <th>Статус</th>
                    <th>Подтвердить</th>
                </tr>

                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['booking_id'] . "</td>";
                        echo "<td>" . $row['room_number'] . "</td>";
                        echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                        echo "<td>" . $row['check_in'] . "</td>";
                        echo "<td>" . $row['check_out'] . "</td>";
                        echo "<td>" . ucfirst($row['status']) . "</td>";

                        // Если статус не подтвержден, показываем кнопку для подтверждения
                        if ($row['status'] === 'pending') {
                            echo "<td><a href='admin.php?confirm_booking=" . $row['booking_id'] . "'><button>Подтвердить</button></a></td>";
                        } else {
                            echo "<td>Подтверждено</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Нет доступных бронирований.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <!-- Добавьте эту кнопку в нужное место вашего HTML -->
<button onclick="window.location.href='index.php'">На главную</button>

</body>
</html>
