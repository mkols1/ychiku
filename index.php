<?php
    require_once 'includes/db.php';
    require_once 'includes/config.php';
    require_once 'script/comment_grab.php';

    $query = "SELECT * FROM posts";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results_posts = array_reverse($results_posts);

    $pdo = null;
    $stmt = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>滑走路通り</title>

<style>
    :root {
        --bg: #f5f5f5;
        --text: #111;
        --card: #fff;
        --border: #ccc;
    }

    body.dark {
        --bg: #121212;
        --text: #eee;
        --card: #1e1e1e;
        --border: #333;
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: var(--bg);
        color: var(--text);
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
        padding: 15px;
        border-radius: 10px;
        height: fit-content;
        position: sticky;
        top: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .main {
        max-width: 800px;
        margin: auto;
    }

    .post {
        background: var(--card);
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .meta {
        font-size: 0.85em;
        color: #888;
        margin-bottom: 10px;
    }

    .comment {
        background: rgba(0,0,0,0.05);
        padding: 10px;
        border-radius: 6px;
        margin-top: 10px;
    }

    body.dark .comment {
        background: rgba(255,255,255,0.05);
    }

    input, textarea {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text);
        box-sizing: border-box;
    }

    button {
        margin-top: 8px;
        padding: 8px 12px;
        border: none;
        background: #333;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background: #555;
    }

    .toggle {
        width: 100%;
        margin-bottom: 10px;
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
        <button class="toggle" onclick="toggleDark()">Toggle Dark Mode</button>

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

<script>
function toggleDark() {
    document.body.classList.toggle("dark");
    localStorage.setItem("darkMode", document.body.classList.contains("dark"));
}

// load preference
if(localStorage.getItem("darkMode") === "true") {
    document.body.classList.add("dark");
}
</script>

</body>
</html>