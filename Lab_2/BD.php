<?php
function connectToDatabase() {
    // Параметры подключения
    $serverName = "RITA06\SQLEXPRESS02"; // Имя сервера
    $database = "HobbyTutorials"; // Имя базы данных
    $uid = ""; // Имя пользователя БД
    $pwd = ""; // Пароль пользователя БД

    $connectionOptions = [
        "Database" => $database,
        "Uid" => $uid,
        "PWD" => $pwd,
        "CharacterSet" => "UTF-8"
    ];

    // Подключение к базе данных
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    // Проверка соединения
    if ($conn === false) {
        die( print_r( sqlsrv_errors(), true));
    }

    return $conn;
}
?>
