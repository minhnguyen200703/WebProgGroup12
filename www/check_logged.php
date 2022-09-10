<?php 
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
        header("Location: index.php"); 
        exit();
    };
?>