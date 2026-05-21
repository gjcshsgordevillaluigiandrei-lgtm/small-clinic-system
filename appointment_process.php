<?php
include 'config.php';
if ($_POST) {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $datetime = $_POST['appointment_date'] . ' ' . $_POST['appointment_time'];
    $lab_req = isset($_POST['laboratory_required']) && $_POST['laboratory_required'] == 'Yes' ? 'Yes' : 'No';
    
    $check = doctorAvailable($pdo, $doctor_id, $_POST['appointment_date']);
    if (!$check['available']) {
        header("Location: index.php?error=Doctor fully booked on this date");
        exit;
    }
    
    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, laboratory_required, status) VALUES (?, ?, ?, ?, 'Pending')");
    $stmt->execute([$patient_id, $doctor_id, $datetime, $lab_req]);
    
    if ($lab_req == 'Yes') {
        $pdo->prepare("INSERT INTO laboratory (patient_id, laboratory_type, status) VALUES (?, 'Pending - from appointment', 'Not Yet Taken')")->execute([$patient_id]);
    }
    
    header("Location: index.php?success=1");
}
?>