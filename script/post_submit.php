<?php
//submits posts

require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/config.php";
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: ../index.php");
        exit();
    }


if($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    

    try{
        require_once __DIR__ . "/../includes/db.php";

        $query = "INSERT INTO posts (title, content) VALUES (?, ?);";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$title, $content]);
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