<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Clearance</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-check-circle"></i> Patient Clearance & Checklist</h1>
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
                $patient_id = (int)$_GET['patient_id'];
                $patient = $pdo->prepare("SELECT * FROM patients WHERE patient_id = ?");
                $patient->execute([$patient_id]);
                $patient_data = $patient->fetch();
                
                if ($patient_data):
                    // Fetch each requirement status
                    $consult_stmt = $pdo->prepare("SELECT status FROM appointments WHERE patient_id = ? AND status='Completed' LIMIT 1");
                    $consult_stmt->execute([$patient_id]);
                    $consult_done = $consult_stmt->rowCount() > 0;

                    $lab_stmt = $pdo->prepare("SELECT status FROM laboratory WHERE patient_id = ? AND status='Completed' LIMIT 1");
                    $lab_stmt->execute([$patient_id]);
                    $lab_done = $lab_stmt->rowCount() > 0;

                    $med_stmt = $pdo->prepare("SELECT status FROM medicines WHERE patient_id = ? AND status='Taken' LIMIT 1");
                    $med_stmt->execute([$patient_id]);
                    $med_done = $med_stmt->rowCount() > 0;

                    $pay_stmt = $pdo->prepare("SELECT payment_status FROM payments WHERE patient_id = ?");
                    $pay_stmt->execute([$patient_id]);
                    $pay = $pay_stmt->fetch();
                    $pay_done = ($pay && $pay['payment_status'] == 'Paid');

                    $is_cleared = $consult_done && $lab_done && $med_done && $pay_done;
            ?>
            <div class="card">
                <h3>Patient: <?php echo htmlspecialchars($patient_data['fullname']); ?> (ID: <?php echo $patient_id; ?>)</h3>
                
                <!-- Interactive Checklist -->
                <div class="checklist-section">
                    <h3><i class="fas fa-clipboard-list"></i> Admin Checklist (Click to toggle)</h3>
                    <div class="checklist-item">
                        <label>🩺 Consultation Completed</label>
                        <input type="checkbox" id="chk_consult" <?php echo $consult_done ? 'checked' : ''; ?> onchange="toggleItem('consult', <?php echo $patient_id; ?>, this.checked)">
                    </div>
                    <div class="checklist-item">
                        <label>🔬 Laboratory Completed</label>
                        <input type="checkbox" id="chk_lab" <?php echo $lab_done ? 'checked' : ''; ?> onchange="toggleItem('lab', <?php echo $patient_id; ?>, this.checked)">
                    </div>
                    <div class="checklist-item">
                        <label>💊 Medicine Taken</label>
                        <input type="checkbox" id="chk_med" <?php echo $med_done ? 'checked' : ''; ?> onchange="toggleItem('medicine', <?php echo $patient_id; ?>, this.checked)">
                    </div>
                    <div class="checklist-item">
                        <label>💰 Payment Completed</label>
                        <input type="checkbox" id="chk_pay" <?php echo $pay_done ? 'checked' : ''; ?> onchange="toggleItem('payment', <?php echo $patient_id; ?>, this.checked)">
                    </div>
                </div>

                <div class="clearance-status">
                    <h4>Final Clearance Status: 
                        <span class="status <?php echo $is_cleared ? 'completed' : 'cancelled'; ?>">
                            <?php echo $is_cleared ? '✅ CLEARED' : '❌ NOT CLEARED'; ?>
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
            <div class="card"><p class="error">Patient ID not found!</p></div>
            <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>

    <script>
        function toggleItem(type, patientId, checked) {
            fetch(`update_clearance_item.php?type=${type}&patient_id=${patientId}&value=${checked}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert('Error updating status');
                });
        }
        function printClearance(patientId) {
            window.open(`print_clearance.php?patient_id=${patientId}`, '_blank');
        }
    </script>
</body>
</html>