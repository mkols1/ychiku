<?php
/*
This file is for editing posts behind the admin panel.
*/

    require_once 'includes/db.php';
    require_once 'includes/config.php';

    if(!isset($_SESSION['admin']) || $_SESSION['admin'] === false) {
        header("location: ./index.php");
        exit();
    }

    $query = "SELECT * FROM posts WHERE id = ? LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_POST['post_id']]);
    $results_posts = $stmt->fetch(PDO::FETCH_ASSOC);


    $pdo = null;
    $stmt = null;
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
    <form action="post.php">
        <button type="submit">Back to Admin Panel</button>
    </form>




    <form action="script/post_update.php" method="post">
        <input type="hidden" name="post_id" value="<?php echo $results_posts['id']; ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required <?php echo "value=" ."'$results_posts[title]'"; ?>>
        <br>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="15" required><?php echo htmlspecialchars($results_posts['content']); ?></textarea>
        <br>
        <button type="submit">Submit</button>
    </form>

    
</body>
</html>