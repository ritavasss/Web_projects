<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Список заметок</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Список заметок</h2>
    <ul class="note-list">
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

        // Запрос на выборку заметок в обратном хронологическом порядке
        $sql = "SELECT * FROM notes ORDER BY created_at DESC";
        $stmt = $db->query($sql);
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($notes as $note) {
            echo "<li class='note'>";
            echo "<h3 class='note-title'>" . $note['title'] . "</h3>";
            echo "<p class='note-content'>" . $note['content'] . "</p>";
            echo "<p class='note-date'><em>Дата создания: " . $note['created_at'] . "</em></p>";
            echo "<a href='add_comment.php?note_id=" . $note['id'] . "' class='note-date'>Перейти к заметке</a>"; 
            echo "</li>";
        }
        ?>
    </ul>
    <a href="default.php" class="button">Добавить новую заметку</a>
</body>
</html>
