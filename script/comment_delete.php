<?php
//submits posts

require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/config.php";
if(!isset($_SESSION['admin']) || $_SESSION['admin'] === false) {
        header("location: ./index.php");
        exit();
    }


if($_SERVER["REQUEST_METHOD"] == "POST") {

    try{
        require_once __DIR__ . "/../includes/db.php";

        $query = "DELETE FROM comments WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$_POST["comment_id"]]);
        $pdo = null;
        $stmt = null;

        header("location: ../post.php");

        die(); //die if it has a connection

    } catch(PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("location: ../post.php");
    exit();
}