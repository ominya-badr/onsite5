<?php 
include '../includes/db.php';
if(isset($_GET['id'])) {
    $stmt =$pdo->prepare('DELETE FROM teams WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    header('location: teams.php');
}
?>