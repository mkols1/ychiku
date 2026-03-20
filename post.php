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
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel</title>

<style>
:root {
    --bg:#f5f5f5; --text:#111; --card:#fff; --border:#ccc; --danger:#c0392b;
}
body.dark {
    --bg:#121212; --text:#eee; --card:#1e1e1e; --border:#333;
}
body {
    margin:0; font-family:Arial;
    background:var(--bg); color:var(--text);
}
.container {
    max-width:900px; margin:auto; padding:20px;
}
.topbar {
    display:flex;
    justify-content:space-between;
    margin-bottom:15px;
}
.card {
    background:var(--card); padding:15px;
    margin-bottom:15px; border-radius:10px;
}
.meta { font-size:.8em; color:#888; }

input, textarea {
    width:100%; padding:6px; margin-top:5px;
    border-radius:5px; border:1px solid var(--border);
    background:transparent; color:var(--text);
}
textarea {
    min-height:200px; /* taller edit box */
    resize:vertical;
}

button {
    padding:6px 12px; border:none; border-radius:5px;
    cursor:pointer; background:#333; color:white;
}
button:hover { background:#555; }

.delete { background:var(--danger); }
.hidden { display:none; }

.actions {
    margin-top:10px;
    display:flex;
    gap:5px;
    flex-wrap:wrap;
}

.comment {
    margin-top:8px; padding:8px;
    background:rgba(0,0,0,.05);
    border-radius:5px;
}
body.dark .comment {
    background:rgba(255,255,255,.05);
}
</style>
</head>

<body>
<div class="container">

<div class="topbar">
    <form action="index.php">
        <button type="submit">← Home</button>
    </form>
    <button onclick="toggleDark()">Toggle Dark</button>
</div>

<?php foreach($results_posts as $row): ?>
<div class="card">

    <!-- VIEW MODE -->
    <div id="view-<?= $row['id'] ?>">
        <h3><?= htmlspecialchars($row["title"]) ?></h3>
        <div class="meta"><?= htmlspecialchars($row["created_time"]) ?></div>
        <p><?= htmlspecialchars($row["content"]) ?></p>

        <div class="actions">
            <button onclick="editPost(<?= $row['id'] ?>)">Edit</button>

            <form action="script/post_delete.php" method="post">
                <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                <button class="delete">Delete</button>
            </form>
        </div>
    </div>

    <!-- EDIT MODE -->
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

function toggleDark() {
    document.body.classList.toggle("dark");
    localStorage.setItem("darkMode", document.body.classList.contains("dark"));
}
if(localStorage.getItem("darkMode")==="true"){
    document.body.classList.add("dark");
}
</script>

</body>
</html>