<?php
session_start();
// Удаление всех переменных сессии
session_unset();
// Удаление сессии из памяти
session_destroy();
// Перенаправление на главную страницу
header("Location: index.php");
exit();
?>
