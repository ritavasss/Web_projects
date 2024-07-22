<?php
session_start();
require_once 'BD.php';
// Подключение к базе данных
$conn = connectToDatabase();

// Запрос категорий из базы данных
$query = "SELECT * FROM Categories";
$result = sqlsrv_query($conn, $query);

// Проверка результата запроса
if ($result === false) {
    die( print_r( sqlsrv_errors(), true));
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hobby Tutorials</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Hobby Tutorials</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo '<li><a href="logout.php">Выход</a></li>';
                        echo '<li><a href="add_lesson.php">Добавить урок</a></li>';
                    } else {
                        echo '<li><a href="registration.html">Регистрация</a></li>';
                        echo '<li><a href="login.html">Вход</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>
    <section class="welcome">
        <div class="container">
            <h2>Добро пожаловать на Hobby Tutorials</h2>
            <I><p>Открывайте для себя новые увлечения и совершенствуйте навыки с нашими уроками.</p></I>
        </div>
    </section>
    <section class="popular-categories">
        <div class="container">
            <h2>Категории</h2>
            <ul>
                <?php
                // Вывод списка категорий
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    echo '<a href="category.php?category=' . urlencode($row['category_name']) . '" class="note-content">' . $row['category_name'] . '</a><br>';
                }
                ?>
            </ul>
            <aside class="image">
                <img src="image.png" alt="Main picture">
            </aside>
        </div>
    </section>
</body>
</html>
