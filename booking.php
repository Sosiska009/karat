<?php
include('db.php'); // Подключаем файл с настройками базы данных

// Получаем ID категории из параметра URL
$category = $_GET['category'];

// Запрос для получения всех номеров в выбранной категории
$sql = "SELECT * FROM rooms WHERE category_id = (SELECT category_id FROM room_categories WHERE name = '$category')";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style4.css">
</head>
<body>
<section class="rooms">
    <div class="container">
        <div class="rooms__inner">
            <?php while ($room = mysqli_fetch_assoc($result)): ?>
                <div class="room-category">
                    <!-- <div class="room-img"><img src="/media/nom/<?php echo $room['image']; ?>" alt="Номер <?php echo $room['room_number']; ?>"></div> -->
                    <h3>Номер №<?php echo $room['room_number']; ?></h3>
                    <p>Цена: <?php echo $room['price']; ?> рублей</p>
                    <button class="open-modal" data-room-id="<?php echo $room['room_id']; ?>" data-room-number="<?php echo $room['room_number']; ?>">Забронировать</button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Модальное окно -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Выберите дату для бронирования</h2>
        <label for="booking_date">Дата бронирования:</label>
        <input type="date" value='2025-01-30' id="booking_date" required>
        <button id="confirm_booking">Подтвердить</button>
    </div>
</div>


</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modal');
    const closeModal = document.querySelector('.close');
    const confirmBooking = document.getElementById('confirm_booking');

    // Обработчик для кнопок "Забронировать"
    document.querySelectorAll('.open-modal').forEach(button => {
        button.addEventListener('click', function() {
            const roomId = this.getAttribute('data-room-id');
            const roomNumber = this.getAttribute('data-room-number');
            
            // Показываем модальное окно только при нажатии
            modal.style.display = 'block';
            
            // Передаем данные номера в кнопку подтверждения
            confirmBooking.setAttribute('data-room-id', roomId);
            confirmBooking.setAttribute('data-room-number', roomNumber);
        });
    });

    // Закрытие модального окна при клике на "X"
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Подтверждение бронирования
    confirmBooking.addEventListener('click', function() {
        const bookingDate = document.getElementById('booking_date').value;
        const roomId = this.getAttribute('data-room-id');
        const roomNumber = this.getAttribute('data-room-number');

        if (bookingDate) {
            fetch('book_room.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `room_id=${roomId}&booking_date=${bookingDate}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Бронирование успешно! Номер ' + roomNumber);
                    modal.style.display = 'none';
                } else {
                    alert('Ошибка бронирования: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка. Попробуйте снова.');
            });
        } else {
            alert('Пожалуйста, выберите дату для бронирования.');
        }
    });
});
</script>
