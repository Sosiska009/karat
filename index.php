<?php
session_start();
include('db.php');

// Получаем user_id из сессии, если пользователь залогинен
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Если пользователь залогинен, получаем его роль из базы данных
if ($user_id) {
    $sql = "SELECT role FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $user_role = $user['role']; // Получаем роль пользователя (например, 'admin' или 'user')
    } else {
        $user_role = null;
    }
} else {
    $user_role = null; // Если пользователь не залогинен
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style1.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>База отдыха "Карат"</title>
    
      
</head>
<body>
    <header>
        <div class="container">
            <div class="header__inner">
                <div class="logo">КАРАТ</div>
                <nav>
                    <ul>
                        <li><a href="/register.php">Регистрация</a></li>
                        <li><a href="/login.php">Войти</a></li>
                        <li><a href="<?php echo $user_role == 'admin' ? '/admin.php' : '/dashboard.php'; ?>">Кабинет</a></li>
                    </ul>
                </nav>
                <div class="contact">+7-(914)-303-51-41</div>
            </div>
        </div>
    </header>
    
    <section class="about">
        <h2>О нашей базе отдыха</h2>
        <div class="about-content">
            <img src="/media/karat.jpg" alt="База отдыха">
            <p>База отдыха "Карат" расположена в живописном уголке природы, где можно полностью насладиться уединением и красотой окружающего мира.У нас вы можете арендовать уютную беседку и провести время на свежем воздухе, наслаждаясь пением птиц и шумом леса. </p>
        </div>
    </section>
    
    <section class="rooms">
    <div class="container">
        <div class="rooms__inner">
            <!-- Номер Стандарт -->
            <div class="room-category">
                <div class="room-img"><img src="/media/nom/standart.png" alt="Номер стандарт"></div>
                <h3>Номера категории "Стандарт"</h3>
                <p>От 1600 рублей</p>
                <!-- Форма с перенаправлением на страницу бронирования -->
                <form action="booking.php" method="GET">
                    <input type="hidden" name="category" value="Стандарт">
                    <button type="submit">Забронировать</button>
                </form>
            </div>
            <!-- Номер Комфорт -->
            <div class="room-category">
                <div class="room-img"><img src="/media/nom/komfort.png" alt="Номер комфорт"></div>
                <h3>Номера категории "Комфорт"</h3>
                <p>От 3400 рублей</p>
                <!-- Форма с перенаправлением на страницу бронирования -->
                <form action="booking.php" method="GET">
                    <input type="hidden" name="category" value="Комфорт">
                    <button  type="submit">Забронировать</button>
                </form>
            </div>
        </div>
    </div>
</section>

    
    <section class="services">
        <div class="container">
            <div class="services__inner">
                <div class="service">
                    <img src="/media/serv/mangal.png" alt="Мангал">
                    <p>Мангал - 100 руб/час</p>
                </div>
                <div class="service">
                    <img src="/media/serv/ydoch.png" alt="Удочки">
                    <p>Удочки - 150 руб/час</p>
                </div>
                <div class="service">
                    <img src="/media/serv/besed.png" alt="Беседка">
                    <p>Беседка - 400 руб/час</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="restaurant">
        <div class="container">
            <div class="restaurant__inner">
                <div class="text">
                    <h2>Ресторан</h2>
                    <p>Ресторан на базе отдыха "Карат" предлагает уютную атмосферу и изысканное меню, в основном состоящее из блюд из дикого мяса и рыбы. Гости могут насладиться неповторимым вкусом свежих продуктов, приготовленных по особым рецептам.</p>
                    <p>В ресторане имеется банкетный зал на 12 человек, который подойдет для проведения мероприятий. Также доступен обычный зал на 24 места, где можно насладиться вкусной едой в компании друзей или семьи.</p>
                    <p>Благодаря разнообразию блюд из дикого мяса и рыбы, каждый гость сможет выбрать что-то по своему вкусу и насладиться уникальными кулинарными шедеврами. Ресторан на базе отдыха "Карат" приглашает всех ценителей качественной еды и приятной атмосферы!</p>
                    <a href="/menu.docx" target="_blank">
                        <button>Полное меню</button>
                    </a>
                </div>
                <div class="images">
                    <img src="/media/rest/1.png" alt="Банкетный зал">
                    <img src="/media/rest/2.png" alt="Зал на 24 места">
                    <img src="/media/rest/3.png" alt="Блюдо из рыбы">
                </div>
            </div>
        </div>
    </section>
    
    <footer>
        <p>Контакты: г. Хабаровск, ул. Лазурная 1, п. Быличка</p>
        <p>Работаем 24/7</p>
    </footer>

</body>
</html>
