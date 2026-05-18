<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Clearance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-check-circle"></i> Patient Clearance</h1>
            <a href="index.php" class="btn primary">← Back to Dashboard</a>
        </header>

        <main>
            <div class="card">
                <h3>Search Patient for Clearance</h3>
                <form method="GET">
                    <div class="form-group">
                        <label>Patient ID</label>
                        <input type="number" name="patient_id" required>
                        <button type="submit" class="btn primary">Check Status</button>
                    </div>
                </form>
            </div>

            <?php if (isset($_GET['patient_id'])): 
                $patient_id = $_GET['patient_id'];
                $patient = $pdo->prepare("SELECT * FROM patients WHERE patient_id = ?");
                $patient->execute([$patient_id]);
                $patient_data = $patient->fetch();
                
                if ($patient_data):
                    // Check all requirements
                    $payment = $pdo->prepare("SELECT * FROM payments WHERE patient_id = ?");
                    $payment->execute([$patient_id]);
                    $payment_data = $payment->fetch();
                    
                    $appointment = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ? AND status = 'Completed'");
                    $appointment->execute([$patient_id]);
                    $has_completed_appointment = $appointment->rowCount() > 0;
                    
                    $lab = $pdo->prepare("SELECT * FROM laboratory WHERE patient_id = ? AND status = 'Completed'");
                    $lab->execute([$patient_id]);
                    $has_completed_lab = $lab->rowCount() > 0;
                    
                    $medicine = $pdo->prepare("SELECT * FROM medicines WHERE patient_id = ?");
                    $medicine->execute([$patient_id]);
                    $has_medicine = $medicine->rowCount() > 0;
                    
                    $is_cleared = $has_completed_appointment && $has_completed_lab && $has_medicine && $payment_data['payment_status'] == 'Paid';
            ?>

            <div class="card">
                <h3>Patient: <?php echo $patient_data['fullname']; ?></h3>
                
                <!-- Clearance Checklist -->
                <div class="checklist-section">
                    <div class="checklist-item">
                        <label>Consultation Done</label>
                        <input type="checkbox" <?php echo $has_completed_appointment ? 'checked' : ''; ?> disabled>
                    </div>
                    <div class="checklist-item">
                        <label>Laboratory Completed</label>
                        <input type="checkbox" <?php echo $has_completed_lab ? 'checked' : ''; ?> disabled>
                    </div>
                    <div class="checklist-item">
                        <label>Medicine Released</label>
                        <input type="checkbox" <?php echo $has_medicine ? 'checked' : ''; ?> disabled>
                    </div>
                    <div class="checklist-item">
                        <label>Payment Completed</label>
                        <input type="checkbox" <?php echo $payment_data['payment_status'] == 'Paid' ? 'checked' : ''; ?> disabled>
                    </div>
                </div>

                <div class="clearance-status">
                    <h4>Final Status: 
                        <span class="status <?php echo $is_cleared ? 'completed' : 'pending'; ?>">
                            <?php echo $is_cleared ? 'CLEARED ✅' : 'NOT CLEARED ❌'; ?>
                        </span>
                    </h4>
                    <?php if ($is_cleared): ?>
                        <button class="btn primary" onclick="printClearance(<?php echo $patient_id; ?>)">
                            <i class="fas fa-print"></i> Print Clearance
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <?php else: ?>
            <div class="card">
                <p class="error">Patient ID not found!</p>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>

    <script>
        function printClearance(patientId) {
            window.open(`print_clearance.php?patient_id=${patientId}`, '_blank');
        }
    </script>
</body>
</html>