<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinic_management";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function getPatientName($pdo, $id) {
    $stmt = $pdo->prepare("SELECT fullname FROM patients WHERE patient_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}

function recalcTotal($pdo, $patient_id) {
    $pdo->prepare("UPDATE payments SET total_amount = consultation_fee + laboratory_fee WHERE patient_id = ?")->execute([$patient_id]);
}

function doctorAvailable($pdo, $doctor_id, $date) {
    $stmt = $pdo->prepare("SELECT max_patients FROM doctors WHERE doctor_id = ?");
    $stmt->execute([$doctor_id]);
    $max = $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND DATE(appointment_date) = ? AND status != 'Cancelled'");
    $stmt->execute([$doctor_id, $date]);
    $current = $stmt->fetchColumn();
    return ['available' => $current < $max, 'remaining' => $max - $current, 'max_patients' => $max, 'current_count' => $current];
}
?>