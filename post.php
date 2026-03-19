<?php
//Admin panel
    require_once 'includes/db.php';
    require_once 'includes/config.php';
    require_once 'comment_grab.php';

    if(!isset($_SESSION['admin']) || $_SESSION['admin'] === false) {
        header("location: ./index.php");
        exit();
    }


    $query = "SELECT * FROM posts";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //reverse order of posts so that the most recent post is at the top of the page
    $results_posts = array_reverse($results_posts);

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
    <form action="post_submit.php" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <br>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="15" required></textarea>
        <br>
        <button type="submit">Submit</button>
    </form>
    
    <?php
    //display all posts for admin to edit or delete
    foreach($results_posts as $row) {
                //display each post content
                echo "<p>" . 
                    "<form action='editor.php' method='post'>" . 
                    "<input type='hidden' name='post_id' value='" . htmlspecialchars($row["id"]) . "'>" .
                    "<button type='submit'>Edit</button>" . " " .
                    htmlspecialchars($row["title"]) . " " .
                    htmlspecialchars($row["created_time"]) . 
                    "</p></form>";

                
                //echo "<br>";

                //Display comments for each post
                
                $results_comments = getCommentsByPostID($row["id"]);
                if(empty($results_comments)){
                    echo "";
                } else {
                    foreach($results_comments as $comment) {
                        echo "<p>" . "<form action='post.php' method='post'>" . 
                    "<button type='submit'>Delete</button>" . " " .
                            htmlspecialchars($comment["name"]) . ": " 
                            . htmlspecialchars($comment["content"]) . "</p></form>";
                    }
                }


            }
            ?>
    
</body>
</html>