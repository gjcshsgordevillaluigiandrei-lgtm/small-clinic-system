<?php
include 'config.php';
header('Content-Type: application/json');

if (!isset($_GET['type']) || !isset($_GET['patient_id']) || !isset($_GET['value'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$type = $_GET['type'];
$patient_id = (int)$_GET['patient_id'];
$value = $_GET['value'] === 'true' ? 1 : 0;

try {
    switch ($type) {
        case 'consult':
            // Update the most recent appointment status (or all if you prefer)
            $status = $value ? 'Completed' : 'Pending';
            $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE patient_id = ? AND status != 'Cancelled' ORDER BY appointment_date DESC LIMIT 1");
            $stmt->execute([$status, $patient_id]);
            break;

        case 'lab':
            $status = $value ? 'Completed' : 'Not Yet Taken';
            $stmt = $pdo->prepare("UPDATE laboratory SET status = ? WHERE patient_id = ? ORDER BY lab_id DESC LIMIT 1");
            $stmt->execute([$status, $patient_id]);
            break;

        case 'medicine':
            $status = $value ? 'Taken' : 'Not Taken';
            $stmt = $pdo->prepare("UPDATE medicines SET status = ? WHERE patient_id = ? ORDER BY medicine_id DESC LIMIT 1");
            $stmt->execute([$status, $patient_id]);
            break;

        case 'payment':
            $status = $value ? 'Paid' : 'Unpaid';
            $stmt = $pdo->prepare("UPDATE payments SET payment_status = ? WHERE patient_id = ?");
            $stmt->execute([$status, $patient_id]);
            // Recalculate total amount after payment status change
            if ($value) {
                recalcTotal($pdo, $patient_id);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid type']);
            exit;
    }
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>