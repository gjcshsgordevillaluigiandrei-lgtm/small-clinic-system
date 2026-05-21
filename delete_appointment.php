<?php
include 'config.php';
$id = $_GET['id'];
$pdo->prepare("UPDATE appointments SET status = 'Cancelled' WHERE appointment_id = ?")->execute([$id]);
header("Location: appointments.php");
?>