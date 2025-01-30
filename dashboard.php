<?php
session_start();
include('db.php');

// Проверяем, что пользователь залогинен
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];  // Получаем user_id из сессии

// Извлекаем данные о пользователе
$sql_user = "SELECT first_name, last_name, email, role FROM users WHERE user_id = '$user_id'";
$result_user = mysqli_query($conn, $sql_user);

// Проверка на ошибку SQL-запроса
if (!$result_user) {
    echo "Ошибка SQL: " . mysqli_error($conn);
    exit();
}

if (mysqli_num_rows($result_user) > 0) {
    $user = mysqli_fetch_assoc($result_user);
} else {
    echo "Ошибка: Пользователь не найден.";
    exit();
}

// Получаем список активных бронирований
$sql_bookings = "SELECT b.booking_id, r.room_number, b.check_in, b.check_out, b.total_price, b.status
                 FROM bookings b
                 JOIN rooms r ON b.room_id = r.room_id
                 WHERE b.user_id = '$user_id' AND b.status = 'confirmed'";
$result_bookings = mysqli_query($conn, $sql_bookings);

// Проверка на ошибку SQL-запроса
if (!$result_bookings) {
    echo "Ошибка SQL: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    <div class="container">
        <h2>Добро пожаловать, <?php echo $user['first_name']; ?>!</h2>
        <p>Ваш email: <?php echo $user['email']; ?></p>
        <p>Роль: <?php echo ucfirst($user['role']); ?></p>
        
        <h3>Ваши активные бронирования:</h3>
        <table>
            <thead>
                <tr>
                    <th>Номер</th>
                    <th>Дата заезда</th>
                    <th>Дата выезда</th>
                    <th>Общая стоимость</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result_bookings) > 0) {
                    while ($booking = mysqli_fetch_assoc($result_bookings)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($booking['room_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($booking['check_in']) . "</td>";
                        echo "<td>" . htmlspecialchars($booking['check_out']) . "</td>";
                        echo "<td>" . htmlspecialchars($booking['total_price']) . " руб.</td>";
                        echo "<td>" . ucfirst(htmlspecialchars($booking['status'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>У вас нет активных бронирований.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Добавьте эту кнопку в нужное место вашего HTML -->
<button onclick="window.location.href='index.php'">На главную</button>

</body>
</html>
