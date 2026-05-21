<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $patient_id = $_POST['patient_id'];
    $laboratory_type = $_POST['laboratory_type'];
    $status = $_POST['status'];
    $result = $_POST['result'];

    try {

        // Insert lab record
        $stmt = $pdo->prepare("
            INSERT INTO laboratory (patient_id, laboratory_type, status, result)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$patient_id, $laboratory_type, $status, $result]);

        // Update payment (NO appointment_id here)
        $stmt_pay = $pdo->prepare("
            UPDATE payments 
            SET lab_fee = 300.00,
                total_amount = consultation_fee + 300.00
            WHERE patient_id = ?
        ");
        $stmt_pay->execute([$patient_id]);

        header("Location: laboratory.php?success=1");
        exit;

    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}
?>