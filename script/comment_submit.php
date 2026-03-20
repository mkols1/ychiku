<?php
//comment submission handler

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST["post_id"];
    $name = $_POST["name"];
    $content = $_POST["content"];
    if(empty($content)) {
        header("location: ../index.php");
        exit();
    }
    if(empty($name)) {
        $name = "Anonymous";
    }
    

    try{
        require_once __DIR__ . "/../includes/db.php";

        $query = "INSERT INTO comments (post_id, name, content) VALUES (?, ?, ?);";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$post_id, $name, $content]);
        $pdo = null;
        $stmt = null;

        header("location: ../index.php");

        die(); //die if it has a connection

    } catch(PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("location: ../index.php");
    exit();
}