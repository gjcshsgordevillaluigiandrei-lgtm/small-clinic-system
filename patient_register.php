<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullname = $_POST['fullname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];

    try {
        $pdo->beginTransaction();

        // Insert patient
        $stmt = $pdo->prepare("
            INSERT INTO patients (fullname, age, gender, address, contact_number)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$fullname, $age, $gender, $address, $contact_number]);

        // Get last inserted patient ID
        $patient_id = $pdo->lastInsertId();

        // Insert payment (DEFAULT consultation fee = 500)
        $stmt = $pdo->prepare("
            INSERT INTO payments (patient_id, total_amount)
            VALUES (?, ?)
        ");
        $stmt->execute([$patient_id, 500.00]);

        $pdo->commit();

        header("Location: index.php?success=1");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: index.php?error=1");
        exit;
    }
}
?>
