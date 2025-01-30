<?php
session_start();
include('db.php'); // Подключаем файл с настройками базы данных

// Проверяем, что запрос пришел методом POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из POST-запроса
    $room_id = $_POST['room_id'];
    $booking_date = $_POST['booking_date'];
    $user_id = $_SESSION['user_id']; // Предполагаем, что ID пользователя хранится в сессии
    $check_in = $booking_date; // Если предполагается, что "check_in" это дата бронирования, можно использовать её
    $check_out = date('Y-m-d', strtotime($check_in . ' +1 day')); // Дата выезда (на следующий день после даты заселения)

    // Проверка, что номер доступен на выбранные даты
    $sql_check_availability = "
        SELECT * FROM bookings 
        WHERE room_id = '$room_id' 
        AND (
            (check_in BETWEEN '$check_in' AND '$check_out') 
            OR 
            (check_out BETWEEN '$check_in' AND '$check_out')
        )
    ";

    $result_check_availability = mysqli_query($conn, $sql_check_availability);

    if (mysqli_num_rows($result_check_availability) > 0) {
        echo json_encode(['success' => false, 'error' => 'Этот номер уже забронирован на выбранные даты']);
        exit;
    }

    // Получаем цену за день из базы данных для выбранного номера
    $sql_price = "SELECT price FROM rooms WHERE room_id = '$room_id'";
    $result_price = mysqli_query($conn, $sql_price);
    if ($result_price && mysqli_num_rows($result_price) > 0) {
        $room = mysqli_fetch_assoc($result_price);
        $price_per_day = $room['price'];

        // Рассчитываем количество дней
        $check_in_timestamp = strtotime($check_in);
        $check_out_timestamp = strtotime($check_out);
        $days_count = ($check_out_timestamp - $check_in_timestamp) / (60 * 60 * 24);

        // Рассчитываем общую стоимость
        $total_price = $price_per_day * $days_count;

        // Проверяем, что все данные были переданы
        if ($room_id && $booking_date && $user_id && $total_price) {
            // Запрос для добавления бронирования в базу данных
            $sql = "INSERT INTO bookings (room_id, user_id, booking_date, check_in, check_out, total_price) 
                    VALUES ('$room_id', '$user_id', '$booking_date', '$check_in', '$check_out', '$total_price')";
            if (mysqli_query($conn, $sql)) {
                // Возвращаем успешный ответ в формате JSON
                echo json_encode(['success' => true]);
            } else {
                // Выводим подробности ошибки для отладки
                echo json_encode(['success' => false, 'error' => 'Ошибка базы данных: ' . mysqli_error($conn)]);
            }
        } else {
            // Возвращаем ошибку, если не хватает данных
            echo json_encode(['success' => false, 'error' => 'Недостаточно данных']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Номер не найден']);
    }
} else {
    // Если запрос не POST
    echo json_encode(['success' => false, 'error' => 'Неверный запрос']);
}
?>
