<?php
session_start();

// Проверка, была ли отправлена форма методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение lesson_id из формы
    $lesson_id = $_POST['lesson_id'];

    require_once 'BD.php';
    // Подключение к базе данных
    $conn = connectToDatabase();

    // SQL запрос для добавления лайка
    $query = "MERGE INTO Likes AS target
              USING (VALUES (?, 1)) AS source (lesson_id, count)
              ON target.lesson_id = source.lesson_id
              WHEN MATCHED THEN 
                UPDATE SET target.count = target.count + 1
              WHEN NOT MATCHED THEN
                INSERT (lesson_id, count) VALUES (source.lesson_id, source.count);";

    // Параметры запроса
    $params = array($lesson_id);

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
