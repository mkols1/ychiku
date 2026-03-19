<?php
    
    function getCommentsByPostID($post_id) {
        require 'includes/db.php';
        require_once 'includes/config.php';
        $query = "SELECT * FROM comments WHERE post_id = :post_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['post_id' => $post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }