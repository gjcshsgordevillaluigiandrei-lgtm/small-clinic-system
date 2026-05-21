<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patient_id = $_POST['patient_id'];
    $medicine_name = $_POST['medicine_name'];
    $dosage = $_POST['dosage'] ?? null;
    $frequency = $_POST['frequency'] ?? null;
    $duration = $_POST['duration'] ?? null;
    $status = $_POST['status'] ?? 'Not Taken';

    try {
        $sql = "INSERT INTO medicines 
                (patient_id, medicine_name, dosage, frequency, duration, status)
                VALUES 
                (:patient_id, :medicine_name, :dosage, :frequency, :duration, :status)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':patient_id' => $patient_id,
            ':medicine_name' => $medicine_name,
            ':dosage' => $dosage,
            ':frequency' => $frequency,
            ':duration' => $duration,
            ':status' => $status
        ]);

        header("Location: medicine.php");
        exit();

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>