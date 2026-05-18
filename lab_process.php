<?php
include 'config.php';

if ($_POST) {
    $patient_id = $_POST['patient_id'];
    $laboratory_type = $_POST['laboratory_type'];
    $status = $_POST['status'];
    $result = $_POST['result'];
    
    $stmt = $pdo->prepare("
        INSERT INTO laboratory (patient_id, laboratory_type, status, result) 
        VALUES (?, ?, ?, ?)
    ");
    $result = $stmt->execute([$patient_id, $laboratory_type, $status, $result]);
    
    if ($result) {
        // Update payment lab fee
        $pdo->query("UPDATE payments SET laboratory_fee = laboratory_fee + 300 WHERE patient_id = $patient_id");
        $pdo->query("UPDATE payments SET total_amount = consultation_fee + laboratory_fee WHERE patient_id = $patient_id");
        header("Location: laboratory.php?success=1");
    } else {
        header("Location: laboratory.php?error=1");
    }
}
?>