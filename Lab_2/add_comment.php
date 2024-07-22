<?php
session_start();

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $comment_text = $_POST['comment_text'];
    $lesson_id = $_POST['lesson_id'];

    require_once 'BD.php';
    // Подключение к базе данных
    $conn = connectToDatabase();
    
    // Получение user_id из базы данных по логину, хранящемуся в сессии
    $login = $_SESSION['username'];
    $user_query = "SELECT user_id FROM Users WHERE username = ?";
    $user_stmt = sqlsrv_query($conn, $user_query, array($login));
    $row = sqlsrv_fetch_array($user_stmt, SQLSRV_FETCH_ASSOC);
    $user_id = $row['user_id'];

    // SQL запрос для добавления комментария
    $query = "INSERT INTO Comments (comment_text, lesson_id, user_id, timestamp) VALUES (?, ?, ?, GETDATE())";

    // Параметры запроса
    $params = array($comment_text, $lesson_id, $user_id);

    // Выполнение запроса
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        // Перенаправляем пользователя обратно на страницу с уроками
        $category_name = $_POST['category'];
        header("Location: category.php?category=" . urlencode($category_name));
        exit();
    }

    // Закрытие соединения с базой данных
    sqlsrv_close($conn);
}
?>
