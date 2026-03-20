<?php

/*
This verifies the admin login pwd
*/
    require_once __DIR__ . "/../includes/db.php";
    require_once __DIR__ . "/../includes/config.php";
    
    sleep(1);

    
    
    if(isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
        header("location: ../post.php");
        exit();
    }
    
    if($_SERVER["REQUEST_METHOD"] !== "POST" && (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true)){
        header("location: ../index.php");
        exit();
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
    $pwd = $_POST["adminpwd"];

    $query = "SELECT * FROM cred";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    if($results && password_verify($pwd, $results["hashed"])) {
        $_SESSION['admin'] = true;
        header("location: ../post.php");
        exit();
    } else {
        header("location: ../index.php");
        exit();
    }
    
    }


    header("location: ../index.php");
    exit();
    
