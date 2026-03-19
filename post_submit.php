<?php

if($_SESSION['admin'] === false) {
        header("location: ./index.php");
        exit();
    }


if($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    

    try{
        require_once "includes/db.php";

        $query = "INSERT INTO posts (title, content) VALUES (?, ?);";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$title, $content]);
        $pdo = null;
        $stmt = null;

        header("location: ./index.php");

        die(); //die if it has a connection

    } catch(PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("location: ./index.php");
    exit();
}