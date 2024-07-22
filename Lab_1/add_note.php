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

// Обработка данных из формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];

    // Подготовка SQL-запроса для вставки новой заметки
    $sql = "INSERT INTO notes (title, content) VALUES (:title, :content)";
    $stmt = $db->prepare($sql);

    // Привязка параметров и выполнение запроса
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":content", $content);

    try {
        $stmt->execute();
        header("Location: show_note.php");
    } catch (PDOException $e) {
        die("Ошибка при добавлении заметки: " . $e->getMessage());
    }
}
?>
