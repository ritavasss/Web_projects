<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Заметки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Добавить новую заметку</h2>
    <form action="add_note.php" method="post">
        <label for="title">Заголовок:</label>
        <input type="text" id="title" name="title" required>
        
        <label for="content">Содержание:</label>
        <textarea id="content" name="content" required></textarea>
        
        <button type="submit">Добавить заметку</button>
    </form>

    <form action="show_note.php" method="post">
        <button type="submit">Перейти к списку заметок</button>
    </form>
</body>
</html>
