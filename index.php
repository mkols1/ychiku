<?php
    require_once 'includes/db.php';
    require_once 'includes/config.php';
    require_once 'comment_grab.php';

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>滑走路通り</title>
</head> 

<body>

    <form action="verifylogin.php" method="post">
        Post: 
        <?php
            if(isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            } else {
                echo "<input type='password' name='adminpwd' placeholder='Password'>";
            }
        ?>
        
        <button type="submit">Continue</button>
    </form>
    <br>
    <?php
        if(empty($results_posts)){
            echo "<p>No results found.</p>";
        } else {
            foreach($results_posts as $row) {
                //display each post content
                
                echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
                echo "<p>" . htmlspecialchars($row["created_time"]) . "<br>" .
                    htmlspecialchars($row["content"]) . "</p>";
                //echo "<br>";

                //Display comments for each post
                $results_comments = getCommentsByPostID($row["id"]);
                if(empty($results_comments)){
                    echo "<p>No comments found.</p>";
                } else {
                    foreach($results_comments as $comment) {
                        echo "<p>" . 
                            htmlspecialchars($comment["created_time"]) . "<br>" .
                            htmlspecialchars($comment["name"]) . ": " . htmlspecialchars($comment["content"]) . "<br></p>";
                    }
                }

                //input box for comments
                echo "<form action='comment_submit.php' method='post'>
                    <input type='hidden' name='post_id' value='" . htmlspecialchars($row["id"]) . "'>
                    <input type='text' name='name' placeholder='Name'>
                    <br>
                    <textarea name='content' rows='5' cols='80' placeholder='Comment'></textarea>
                    <button type='submit'>Submit</button>
                    </form>";


            }
        }
    ?>


</body>
    
</html>