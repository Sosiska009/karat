<?php
session_start();
include('db.php');

// Проверка, администратор ли пользователь
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Ошибка: доступ запрещен'], JSON_UNESCAPED_UNICODE);
    exit();
}

// Проверка, переданы ли данные
if (isset($_POST['booking_id']) && isset($_POST['confirm'])) {
    $booking_id = intval($_POST['booking_id']);
    $status = $_POST['confirm'];

    // Обновляем статус
    $sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $status, $booking_id);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Статус бронирования изменен'], JSON_UNESCAPED_UNICODE);
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'admin.php';
                    }, 3000);
                  </script>";
        } else {
            echo json_encode(['success' => false, 'message' => 'Ошибка обновления: ' . mysqli_error($conn)], JSON_UNESCAPED_UNICODE);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ошибка запроса'], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Некорректные данные'], JSON_UNESCAPED_UNICODE);
}

mysqli_close($conn);
?>
