<?php
session_start();
include('db.php'); // Подключение к БД

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password'])) { // Проверка хешированного пароля
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_role'] = $user['role']; // Убедитесь, что в БД есть поле role

            // Перенаправление в зависимости от роли
            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: dashboard.php"); // Личный кабинет клиента
            }
            exit();
        } else {
            $error = "Ошибка: неверный пароль!";
        }
    } else {
        $error = "Ошибка: пользователь не найден!";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="container">
        <h2>Авторизация</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Войти</button>
        </form>
    </div>
    <!-- Добавьте эту кнопку в нужное место вашего HTML -->
<button onclick="window.location.href='index.php'">На главную</button>

</body>
</html>
