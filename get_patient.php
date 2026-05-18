<?php
include 'config.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT fullname FROM patients WHERE patient_id = ?");
    $stmt->execute([$patient_id]);
    $patient = $stmt->fetch();
    
    if ($patient) {
        echo json_encode(['fullname' => $patient['fullname']]);
    } else {
        echo json_encode(['fullname' => '']);
    }
}
?>