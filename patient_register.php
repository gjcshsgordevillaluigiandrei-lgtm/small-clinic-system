<?php
include 'config.php';

if ($_POST) {
    $fullname = $_POST['fullname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    
    $stmt = $pdo->prepare("INSERT INTO patients (fullname, age, gender, address, contact_number) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute([$fullname, $age, $gender, $address, $contact_number]);
    
    if ($result) {
        // Create default payment record
        $patient_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO payments (patient_id, total_amount) VALUES (?, 500.00)");
        $stmt->execute([$patient_id]);
        
        header("Location: index.php?success=1");
    } else {
        header("Location: index.php?error=1");
    }
}
?>