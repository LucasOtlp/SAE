<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=SAE3;charset=utf8', 'adminer', 'Isanum64!');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed : " . $e->getMessage());
}
?>
