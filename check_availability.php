<?php
include 'config.php';
header('Content-Type: application/json');

if (isset($_GET['doctor_id']) && isset($_GET['date'])) {
    $doctor_id = $_GET['doctor_id'];
    $date = $_GET['date'];
    
    $stmt = $pdo->prepare("SELECT max_patients FROM doctors WHERE doctor_id = ?");
    $stmt->execute([$doctor_id]);
    $doctor = $stmt->fetch();
    
    if (!$doctor) {
        echo json_encode(['available' => false, 'message' => 'Doctor not found']);
        exit;
    }
    
    $max_patients = $doctor['max_patients'];
    
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM appointments 
        WHERE doctor_id = ? AND DATE(appointment_date) = ? AND status != 'Cancelled'
    ");
    $stmt->execute([$doctor_id, $date]);
    $count = $stmt->fetch()['count'];
    
    $available = $count < $max_patients;
    
    echo json_encode([
        'available' => $available,
        'current_count' => $count,
        'max_patients' => $max_patients,
        'remaining' => $max_patients - $count
    ]);
} else {
    echo json_encode(['available' => false, 'message' => 'Missing parameters']);
}
?>