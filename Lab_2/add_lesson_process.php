<?php
session_start();

// Проверка, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $lesson_title = $_POST['lesson_title'];
    $lesson_description = $_POST['lesson_description'];
    $category_id = $_POST['category_id'];

    require_once 'BD.php';
    // Подключение к базе данных
    $conn = connectToDatabase();

    // Получение user_id из базы данных по логину, хранящемуся в сессии
    $login = $_SESSION['username'];
    $user_query = "SELECT user_id FROM Users WHERE username = ?";
    $user_stmt = sqlsrv_query($conn, $user_query, array($login));

    if ($user_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($user_stmt, SQLSRV_FETCH_ASSOC);
    $user_id = $row['user_id'];

    // SQL запрос для добавления урока
    $query = "INSERT INTO Lessons (lesson_title, lesson_description, category_id, user_id) VALUES (?, ?, ?, ?)";

    // Параметры запроса
    $params = array($lesson_title, $lesson_description, $category_id, $user_id);

    // Выполнение запроса
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        header("Location: index.php");
        exit();
    }
    // Закрытие соединения с базой данных
    sqlsrv_close($conn);
}
?>
