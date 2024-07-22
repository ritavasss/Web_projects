<?php
require_once 'BD.php';
// Подключение к базе данных
$conn = connectToDatabase();

// Обработка данных из формы регистрации
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Проверка наличия такого же пользователя в базе данных
    $check_query = "SELECT * FROM Users WHERE username=? OR email=?";
    $params = array($username, $email);
    $stmt = sqlsrv_query($conn, $check_query, $params);

    if ($stmt === false) {
        die( print_r( sqlsrv_errors(), true));
    }
    if (sqlsrv_has_rows($stmt)) {
        // Если пользователь уже существует, выведите сообщение об ошибке
        echo "Пользователь с таким именем пользователя уже существует.";
    } else {
        // Хэширование пароля
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Вставка данных в базу данных
        $insert_user_query = "INSERT INTO Users (username, first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?, ?)";
        $params_user = array($username, $firstname, $lastname, $email, $hashed_password);
        $stmt_user = sqlsrv_query($conn, $insert_user_query, $params_user);
        if ($stmt_user === false) {
            die( print_r( sqlsrv_errors(), true));
        }

        header("Location: login.html");
        exit();
    }
}

// Закрытие соединения с базой данных
sqlsrv_close($conn);
?>
