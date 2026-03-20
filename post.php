<?php
require_once 'includes/db.php';
require_once 'includes/config.php';
require_once 'script/comment_grab.php';

if(!isset($_SESSION['admin']) || $_SESSION['admin'] === false) {
    header("location: ./index.php");
    exit();
}

$query = "SELECT * FROM posts";
$stmt = $pdo->prepare($query);
$stmt->execute();
$results_posts = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

$pdo = null;
$stmt = null;

// SAME BACKGROUND AS INDEX
$bgImage = "images/background.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel</title>

<style>
:root {
    --text:#eee;
    --card: rgba(30,30,30,0.75);
    --border: rgba(255,255,255,0.1);
    --danger:#c0392b;
}

body {
    margin:0;
    font-family:Arial;
    color:var(--text);

    /* SAME BACKGROUND */
    background: url("<?= $bgImage ?>") no-repeat center center fixed;
    background-size: cover;
}

.container {
    max-width:900px;
    margin:auto;
    padding:20px;
}

.topbar {
    display:flex;
    justify-content:space-between;
    margin-bottom:15px;
}

.card {
    background:var(--card);
    backdrop-filter: blur(10px);
    padding:18px;
    margin-bottom:18px;
    border-radius:10px;
    border:1px solid var(--border);
}

.meta {
    font-size:.8em;
    color:#aaa;
    margin-bottom:10px;
}

input, textarea {
    width:100%;
    padding:8px;
    margin-top:5px;
    border-radius:5px;
    border:1px solid var(--border);
    background: rgba(0,0,0,0.4);
    color:var(--text);
}

textarea {
    min-height:220px;
    resize:vertical;
}

button {
    padding:6px 12px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    background:#222;
    color:white;
}
button:hover { background:#444; }

.delete { background:var(--danger); }

.hidden { display:none; }

.actions {
    margin-top:10px;
    display:flex;
    gap:5px;
    flex-wrap:wrap;
}

.comment {
    margin-top:10px;
    padding:10px;
    background: rgba(255,255,255,0.05);
    border-radius:6px;
}
</style>
</head>

<body>
<div class="container">

<div class="topbar">
    <form action="index.php">
        <button type="submit">← Home</button>
    </form>
</div>

<?php foreach($results_posts as $row): ?>
<div class="card">

    <!-- VIEW -->
    <div id="view-<?= $row['id'] ?>">
        <h3><?= htmlspecialchars($row["title"]) ?></h3>
        <div class="meta"><?= htmlspecialchars($row["created_time"]) ?></div>
        <p style="line-height:1.7;">
            <?= htmlspecialchars($row["content"]) ?>
        </p>

        <div class="actions">
            <button onclick="editPost(<?= $row['id'] ?>)">Edit</button>

            <form action="script/post_delete.php" method="post">
                <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                <button class="delete">Delete</button>
            </form>
        </div>
    </div>

    <!-- EDIT -->
    <div id="edit-<?= $row['id'] ?>" class="hidden">
        <form action="script/post_update.php" method="post">
            <input type="hidden" name="post_id" value="<?= $row['id'] ?>">

            <input type="text" name="title"
                value="<?= htmlspecialchars($row["title"]) ?>" required>

            <textarea name="content" required><?= htmlspecialchars($row["content"]) ?></textarea>

            <div class="actions">
                <button type="submit">Save</button>
                <button type="button" onclick="cancelEdit(<?= $row['id'] ?>)">Cancel</button>
            </div>
        </form>
    </div>

    <!-- COMMENTS -->
    <?php
    $comments = getCommentsByPostID($row["id"]);
    foreach($comments as $comment):
    ?>
        <div class="comment">
            <strong><?= htmlspecialchars($comment["name"]) ?>:</strong>
            <?= htmlspecialchars($comment["content"]) ?>

            <form action="script/comment_delete.php" method="post">
                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                <button class="delete">Delete</button>
            </form>
        </div>
    <?php endforeach; ?>

</div>
<?php endforeach; ?>

</div>

<script>
function editPost(id) {
    document.getElementById("view-"+id).classList.add("hidden");
    document.getElementById("edit-"+id).classList.remove("hidden");
}

function cancelEdit(id) {
    document.getElementById("edit-"+id).classList.add("hidden");
    document.getElementById("view-"+id).classList.remove("hidden");
}
</script>

</body>
</html>