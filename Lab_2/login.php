<?php
require_once 'BD.php';
// Подключение к базе данных
$conn = connectToDatabase();

// Обработка данных из формы входа
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Поиск пользователя в базе данных
    $query = "SELECT * FROM Users WHERE username=?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die( print_r( sqlsrv_errors(), true));
    }

    // Проверка существования пользователя и проверка пароля
    if (sqlsrv_has_rows($stmt)) {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (password_verify($password, $row['password_hash'])) {
            // Успешный вход
            session_start();
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            // Неверный пароль
            echo "Неверное имя пользователя или пароль.";
        }
    } else {
        // Пользователь не найден
        echo "Неверное имя пользователя или пароль.";
    }
}

// Закрытие соединения с базой данных
sqlsrv_close($conn);
?>
