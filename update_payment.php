<?php
include 'config.php';
header('Content-Type: application/json');

if (isset($_GET['patient_id']) && isset($_GET['status'])) {
    $patient_id = $_GET['patient_id'];
    $status = $_GET['status'];
    
    $stmt = $pdo->prepare("UPDATE payments SET payment_status = ? WHERE patient_id = ?");
    $success = $stmt->execute([$status, $patient_id]);
    
    echo json_encode(['success' => $success]);
}
?>