<?php
session_start();
require_once 'BD.php';
// Подключение к базе данных
$conn = connectToDatabase();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Добавить урок</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Hobby Tutorials</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="logout.php">Выход</a></li>
                    <li><a href="add_lesson.php">Добавить урок</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <section class="add-lesson">
        <div class="container">
            <h2>Добавить новый урок</h2>
            <form action="add_lesson_process.php" method="post">
                <label for="lesson_title">Заголовок урока:</label>
                <input type="text" id="lesson_title" name="lesson_title" required>
                <label for="lesson_description">Описание урока:</label>
                <textarea id="lesson_description" name="lesson_description"></textarea>
                <label for="category_id">Категория:</label>
                <select id="category_id" name="category_id">
                    <?php
                    // Вывод списка категорий в форму
                    $result = sqlsrv_query($conn, "SELECT * FROM Categories");
                    if ($result !== false) {
                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                            echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                        }
                    }
                    ?>
                </select><br>
                <input type="submit" value="Добавить урок">
            </form>
        </div>
    </section>
</body>
</html>
