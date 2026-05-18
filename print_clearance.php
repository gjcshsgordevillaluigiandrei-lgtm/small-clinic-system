<?php include 'config.php'; 
if (!isset($_GET['patient_id'])) exit;

$patient_id = $_GET['patient_id'];
$stmt = $pdo->prepare("SELECT * FROM patients WHERE patient_id = ?");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Patient Clearance - <?php echo $patient['fullname']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 2rem; }
        .header { text-align: center; border-bottom: 2px solid #3498db; padding-bottom: 1rem; margin-bottom: 2rem; }
        .patient-info { margin-bottom: 2rem; }
        .checklist { display: grid; grid-template-columns: 1fr auto; gap: 1rem; margin: 1rem 0; }
        .cleared { color: green; font-size: 1.5rem; font-weight: bold; }
        @media print { body { margin: 0; padding: 1rem; } }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>🏥 SMALL CLINIC MANAGEMENT SYSTEM</h1>
        <h2>PATIENT CLEARANCE CERTIFICATE</h2>
    </div>
    
    <div class="patient-info">
        <h3>Patient Information</h3>
        <p><strong>Name:</strong> <?php echo $patient['fullname']; ?></p>
        <p><strong>ID:</strong> <?php echo $patient['patient_id']; ?></p>
        <p><strong>Age:</strong> <?php echo $patient['age']; ?> | <strong>Gender:</strong> <?php echo $patient['gender']; ?></p>
    </div>
    
    <div class="checklist-section">
        <h3>Clearance Checklist ✅</h3>
        <div class="checklist">
            <span>Consultation Completed</span> <span>✓</span>
        </div>
        <div class="checklist">
            <span>Laboratory Tests Completed</span> <span>✓</span>
        </div>
        <div class="checklist">
            <span>Medicines Released</span> <span>✓</span>
        </div>
        <div class="checklist">
            <span>Payment Fully Settled</span> <span>✓</span>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 3rem;">
        <div class="cleared">PATIENT CLEARED FOR DISCHARGE</div>
        <p>Date: <?php echo date('F d, Y'); ?></p>
        <p>Clinic Staff Signature: ___________________</p>
    </div>
</body>
</html>