<?php
include 'config.php';
$id = $_GET['id'];
$status = $_GET['status'];
$pdo->prepare("UPDATE medicines SET status = ? WHERE medicine_id = ?")->execute([$status, $id]);
echo json_encode(['success'=>true]);
?>