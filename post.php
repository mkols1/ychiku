<?php
    require_once 'includes/db.php';
    require_once 'includes/config.php';

    if(!isset($_SESSION['admin']) || $_SESSION['admin'] === false) {
        header("location: ./index.php");
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="index.php">
        <button type="submit">Back to Home</button>
    </form>
    <form action="post_submit.php" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <br>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="15" required></textarea>
        <br>
        <button type="submit">Submit</button>
    </form>

    
</body>
</html>