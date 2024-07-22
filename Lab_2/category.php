<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hobby Tutorials - Категория</title>
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
                    session_start();
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
    <section class="category">
        <div class="container">
            <h2><?php echo $_GET['category']; ?></h2>
            <?php
            require_once 'BD.php';
            // Подключение к базе данных
            $conn = connectToDatabase();

            // Получение категории из URL
            $category_name = $_GET['category'];

            // SQL запрос для выбора уроков для выбранной категории с именем пользователя
            $query = "SELECT Lessons.lesson_id, Lessons.lesson_title, Lessons.lesson_description, Users.username, COALESCE(Likes.count, 0) AS like_count
            FROM Lessons 
            INNER JOIN Categories ON Lessons.category_id = Categories.category_id 
            INNER JOIN Users ON Lessons.user_id = Users.user_id 
            LEFT JOIN Likes ON Lessons.lesson_id = Likes.lesson_id
            WHERE Categories.category_name = ?";
            // Параметры запроса
            $params = array($category_name);

            // Выполнение запроса
            $stmt = sqlsrv_query($conn, $query, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            // Вывод уроков
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<div class='note'>";
                echo "<h3 class='note-title'>" . $row['lesson_title'] . '</h3>';
                echo "<p class='note-content'>" . $row['lesson_description'] . '</p>';
                echo "<I><p class='note-date'>Автор: " . $row['username'] . '</p></I>';

               // Форма для добавления лайка
                echo '<form method="post" action="add_like.php">';
                echo '<input type="hidden" name="lesson_id" value="' . $row['lesson_id'] . '">';
                echo '<input type="hidden" name="category" value="' . $_GET['category'] . '">'; 
                echo '<div style="display: flex; align-items: center;">';
                echo '<p style="margin-left: 95%;">' . $row['like_count'] . '</p>';
                if (isset($_SESSION['username'])) {
                    echo '<button type="submit" style="background: none; border: none; text-align: left;">';
                    echo '<img src="Like.png" alt="Лайк" width="20" height="20" style="float: right;">';
                    echo '</button>';
                }
                else  echo '<img src="Like.png" alt="Лайк" width="20" height="20" style="border: none; text-align: left; float: right; margin-left: 5px">';
                echo '</div>';
                echo '</form>';

                 // SQL запрос для выбора комментариев для текущего урока
                $comment_query = "SELECT Comments.comment_text, Users.username, Comments.timestamp
                                  FROM Comments 
                                  INNER JOIN Users ON Comments.user_id = Users.user_id 
                                  WHERE Comments.lesson_id = ?";
                $comment_params = array($row['lesson_id']);
                $comment_stmt = sqlsrv_query($conn, $comment_query, $comment_params);

                // Вывод списка комментариев
                echo '<div class="comments">';
                while ($comment_row = sqlsrv_fetch_array($comment_stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<p class='note-date'>" . $comment_row['username'] . ' (' . $comment_row['timestamp']->format('Y-m-d H:i:s') . '): ' . $comment_row['comment_text'] . '</p>';
                }
                echo '</div>'; // Конец блока комментариев

                if (isset($_SESSION['username'])) {
                    // Форма для добавления комментария
                    echo '<form method="post" action="add_comment.php">';
                    echo '<input type="hidden" name="lesson_id" value="' . $row['lesson_id'] . '">';
                    echo '<input type="hidden" name="category" value="' . $_GET['category'] . '">'; 
                    echo '<textarea name="comment_text" placeholder="Оставьте ваш комментарий" required 
                                            style="font-family: Arial, sans-serif; font-size: 12px"></textarea><br>';
                    echo '<button type="submit" style=" background-color: #333; color: #fff; border: none; padding: 
                                                10px 20px; border-radius: 5px; cursor: pointer;">Отправить</button>';
                    echo '</form>';
                }
                    echo '</div>';
            }

            // Закрытие соединения с базой данных
            sqlsrv_close($conn);
            ?>
        </div>
    </section>
</body>
</html>
