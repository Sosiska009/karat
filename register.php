<?php
session_start();
include('db.php'); // Подключение к базе данных

// Проверка, если форма отправлена
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Проверка, существует ли уже пользователь с таким email
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "Пользователь с таким email уже существует.";
    } else {
        // Хеширование пароля
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Вставка нового пользователя в таблицу
        $sql = "INSERT INTO users (first_name, last_name, email, password, phone) 
                VALUES ('$first_name', '$last_name', '$email', '$hashed_password', '$phone')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Регистрация успешна!";
        } else {
            echo "Ошибка: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="container">
        <h2>Регистрация</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="register.php" method="POST">
            <label for="first_name">Имя:</label>
            <input type="text" id="first_name" name="first_name" required>
            <label for="last_name">Фамилия:</label>
            <input type="text" id="last_name" name="last_name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            <label for="confirm_password">Подтвердите пароль:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
<!-- Добавьте эту кнопку в нужное место вашего HTML -->
<button onclick="window.location.href='index.php'">На главную</button>

</body>
</html>

