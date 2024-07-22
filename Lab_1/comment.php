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

// Получаем данные из формы
$note_id = $_POST["note_id"];
$comment_content = $_POST["comment_content"];

// Подготовка SQL-запроса для вставки нового комментария
$sql = "INSERT INTO comments (note_id, content) VALUES (:note_id, :content)";
$stmt = $db->prepare($sql);

// Привязка параметров и выполнение запроса
$stmt->bindParam(":note_id", $note_id);
$stmt->bindParam(":content", $comment_content);

try {
    $stmt->execute();
    // После успешного добавления комментария перенаправляем пользователя обратно на страницу заметки
    header("Location: show_note.php?note_id=$note_id");
    exit();
} catch (PDOException $e) {
    die("Ошибка при добавлении комментария: " . $e->getMessage());
}
?>
