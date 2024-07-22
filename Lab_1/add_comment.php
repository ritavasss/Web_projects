<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Заметка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Заметка</h2>

    <?php
    // Подключение к базе данных
    $host = "localhost"; // хост, на котором запущен PostgreSQL
    $dbname = "Web_1"; // имя базы данных
    $username = "postgres"; // имя пользователя для доступа к базе данных
    $password = "18174213"; // пароль для доступа к базе данных

    try {
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }

    // Получаем идентификатор заметки из запроса
    $note_id = $_GET["note_id"];

    // Запрос на выборку заметки по идентификатору
    $sql = "SELECT * FROM notes WHERE id = :note_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":note_id", $note_id);
    $stmt->execute();
    $note = $stmt->fetch(PDO::FETCH_ASSOC);

    // Выводим заметку
    if ($note) {
        echo "<div class='note'>";
        echo "<h3 class='note-title'>" . $note['title'] . "</h3>";
        echo "<p class='note-content'>" . $note['content'] . "</p>";
        echo "<p class='note-date'><em>Дата создания: " . $note['created_at'] . "</em></p>";
        echo "</div>";

        // Запрос на выборку комментариев к данной заметке
        $sql_comments = "SELECT * FROM comments WHERE note_id = :note_id ORDER BY created_at DESC";
        $stmt_comments = $db->prepare($sql_comments);
        $stmt_comments->bindParam(":note_id", $note_id);
        $stmt_comments->execute();
        $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

        // Выводим комментарии
        if ($comments) {
            echo "<h3>Комментарии:</h3>";
            echo "<ul class='comments-list'>";
            foreach ($comments as $comment) {
                echo "<li class='comment'>";
                echo "<p class='comment-content'>" . $comment['content'] . "</p>";
                echo "<p class='comment-date'><em>Дата добавления: " . $comment['created_at'] . "</em></p>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Пока нет комментариев.</p>";
        }

        // Форма для добавления комментария
        echo "<h3>Добавить комментарий:</h3>";
        echo "<form action='comment.php' method='post'>";
        echo "<input type='hidden' name='note_id' value='$note_id'>";
        echo "<label for='comment_content'>Текст комментария:</label><br>";
        echo "<textarea id='comment_content' name='comment_content' required></textarea><br>";
        echo "<button type='submit'>Отправить комментарий</button>";
        echo "</form>";
    } else {
        echo "<p>Заметка не найдена.</p>";
    }
    ?>
    <a href="show_note.php" class="button">Перейти к списку заметок</a>
</body>
</html>
