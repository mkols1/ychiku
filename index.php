<?php
require_once 'includes/db.php';
require_once 'includes/config.php';
require_once 'script/comment_grab.php';

$query = "SELECT * FROM posts";
$stmt = $pdo->prepare($query);
$stmt->execute();
$results_posts = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

$pdo = null;
$stmt = null;

// SET YOUR BACKGROUND IMAGE HERE
$bgImage = "images/background.jpg"; // change this path
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>滑走路通り</title>

<style>
    :root {
        --bg: #121212;
        --text: #eee;
        --card: rgba(30,30,30,0.75); /* transparent */
        --border: rgba(255,255,255,0.1);
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        color: var(--text);

        /* BACKGROUND IMAGE */
        background: url("<?= $bgImage ?>") no-repeat center center fixed;
        background-size: cover;
    }

    .layout {
        display: grid;
        grid-template-columns: 250px 1fr 250px;
        gap: 20px;
        max-width: 1400px;
        margin: auto;
        padding: 20px;
    }

    .sidebar {
        background: var(--card);
        backdrop-filter: blur(10px);
        padding: 15px;
        border-radius: 10px;
        height: fit-content;
        position: sticky;
        top: 20px;
        border: 1px solid var(--border);
    }

    .main {
        max-width: 800px;
        margin: auto;
    }

    .post {
    background: var(--card);
    backdrop-filter: blur(10px);
    padding: 24px;              /* slightly more overall space */
    margin-bottom: 20px;
    border-radius: 10px;
    border: 1px solid var(--border);
}

/* title spacing */
.post h3 {
    margin-bottom: 16px;
}

/* date spacing */
.meta {
    font-size: 0.85em;
    color: #aaa;
    margin-bottom: 16px;
}

/* main content spacing */
.post p {
    line-height: 1.7;
    padding: 8px 0;            /* THIS adds vertical padding */
    margin-bottom: 16px;
}

    .meta {
        font-size: 0.85em;
        color: #aaa;
        margin-bottom: 10px;
    }

    .comment {
        background: rgba(255,255,255,0.05);
        padding: 10px;
        border-radius: 6px;
        margin-top: 10px;
    }

    input, textarea {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid var(--border);
        background: rgba(0,0,0,0.4);
        color: var(--text);
        box-sizing: border-box;
    }

    button {
        margin-top: 8px;
        padding: 8px 12px;
        border: none;
        background: #222;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background: #444;
    }

    @media (max-width: 900px) {
        .layout {
            grid-template-columns: 1fr;
        }
        .sidebar {
            position: static;
        }
    }
</style>
</head>

<body>

<div class="layout">

    <!-- LEFT SIDEBAR -->
    <div class="sidebar">
        <h3>Left Sidebar</h3>
        <p>Add anything here:</p>
        <ul>
            <li>Links</li>
            <li>About</li>
            <li>Stats</li>
        </ul>
    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="post">
            <form action="script/verifylogin.php" method="post">
                <?php
                    if(!(isset($_SESSION['admin']) && $_SESSION['admin'] === true)) {
                        echo "<input type='password' name='adminpwd' placeholder='Password'>";
                    }
                ?>
                <button type="submit">Continue</button>
            </form>
        </div>

        <?php
        if(empty($results_posts)){
            echo "<p>No results found.</p>";
        } else {
            foreach($results_posts as $row) {

                echo "<div class='post'>";
                echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
                echo "<div class='meta'>" . htmlspecialchars($row["created_time"]) . "</div>";
                echo "<p>" . htmlspecialchars($row["content"]) . "</p>";

                $results_comments = getCommentsByPostID($row["id"]);

                if(empty($results_comments)){
                    echo "<p>No comments.</p>";
                } else {
                    foreach($results_comments as $comment) {
                        echo "<div class='comment'>";
                        echo "<div class='meta'>" . htmlspecialchars($comment["created_time"]) . "</div>";
                        echo "<strong>" . htmlspecialchars($comment["name"]) . ":</strong><br>";
                        echo htmlspecialchars($comment["content"]);
                        echo "</div>";
                    }
                }

                echo "<form action='script/comment_submit.php' method='post'>
                    <input type='hidden' name='post_id' value='" . htmlspecialchars($row["id"]) . "'>
                    <input type='text' name='name' placeholder='Name'>
                    <textarea name='content' rows='4' placeholder='Comment'></textarea>
                    <button type='submit'>Submit</button>
                </form>";

                echo "</div>";
            }
        }
        ?>
    </div>

    <!-- RIGHT SIDEBAR -->
    <div class="sidebar">
        <h3>Right Sidebar</h3>
        <p>Use for:</p>
        <ul>
            <li>Recent posts</li>
            <li>Announcements</li>
            <li>Tags</li>
        </ul>
    </div>

</div>

</body>
</html>