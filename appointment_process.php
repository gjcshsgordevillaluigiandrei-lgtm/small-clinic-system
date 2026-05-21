<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];

    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $datetime = $appointment_date . ' ' . $appointment_time;

    // laboratory checkbox (no strict DB dependency)
    $lab_req = (isset($_POST['laboratory_required']) && $_POST['laboratory_required'] == 'Yes')
        ? 'Yes'
        : 'No';

    try {
        $pdo->beginTransaction();

        // insert appointment
        $stmt = $pdo->prepare("
            INSERT INTO appointments (patient_id, doctor_id, appointment_date, status)
            VALUES (?, ?, ?, 'Pending')
        ");
        $stmt->execute([$patient_id, $doctor_id, $datetime]);

        // if lab required → insert to laboratory table
        if ($lab_req === 'Yes') {
            $stmt = $pdo->prepare("
                INSERT INTO laboratory (patient_id, laboratory_type, status)
                VALUES (?, 'From Appointment', 'Not Yet Taken')
            ");
            $stmt->execute([$patient_id]);
        }

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