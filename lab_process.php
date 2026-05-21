<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $laboratory_type = $_POST['laboratory_type'];
    $status = $_POST['status'];
    $result = $_POST['result'];
    
$stmt_pay = $pdo->prepare("UPDATE payments SET lab_fee = 300.00, total_amount = (consultation_fee + 300.00) WHERE appointment_id = ?");
$stmt_pay->execute([$appointment_id]);
    $appointment = $stmt_app->fetch();
    
    if ($appointment) {
        $appointment_id = $appointment['appointment_id'];
        
        $stmt = $pdo->prepare("INSERT INTO laboratory (appointment_id, laboratory_type, status, result) VALUES (?, ?, ?, ?)");
        $execute_result = $stmt->execute([$appointment_id, $laboratory_type, $status, $result]);
        
        if ($execute_result) {
           
            $stmt_pay = $pdo->prepare("UPDATE payments SET lab_fee = 300.00, total_amount = (consultation_fee + 300.00) WHERE patient_id = ?");
            $stmt_pay->execute([$patient_id]);
            
            header("Location: laboratory.php?success=1");
            exit;
        } else {
            header("Location: laboratory.php?error=insert_failed");
            exit;
        }
    } else {
        header("Location: laboratory.php?error=no_appointment");
        exit;
    }
}
?>