<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Medicine Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header><h1>💊 Medicines & Checklist</h1><a href="index.php" class="btn primary">Back</a></header>
    <main>
        <div class="card">
            <h3>Assign Medicine to Patient</h3>
            <form action="medicine_process.php" method="POST">
                <div class="form-row">
                    <div class="form-group"><label>Patient ID</label><input type="number" name="patient_id" required></div>
                    <div class="form-group"><label>Patient Name</label><input type="text" id="patient_name" readonly></div>
                </div>
                <div class="form-group"><label>Medicine Name</label><input type="text" name="medicine_name" required></div>
                <div class="form-row">
                    <div class="form-group"><label>Dosage</label><input type="text" name="dosage" placeholder="e.g., 500mg"></div>
                    <div class="form-group"><label>Frequency</label><input type="text" name="frequency" placeholder="e.g., Twice a day"></div>
                </div>
                <div class="form-group"><label>Duration</label><input type="text" name="duration" placeholder="e.g., 7 days"></div>
                <div class="form-group"><label>Status (Taken?)</label>
                    <select name="status"><option value="Not Taken">Not Taken</option><option value="Taken">Taken</option></select>
                </div>
                <button type="submit" class="btn primary">Save Medicine</button>
            </form>
        </div>
        
        <div class="card">
            <h3>📋 Medicine Records (Admin Checklist)</h3>
            <table class="table">
                <thead><tr><th>Patient</th><th>Medicine</th><th>Dosage</th><th>Status</th><th>Mark Taken</th></tr></thead>
                <tbody>
                <?php
                $meds = $pdo->query("SELECT m.*, p.fullname FROM medicines m JOIN patients p ON m.patient_id = p.patient_id ORDER BY m.prescription_date DESC");
                foreach($meds as $m) {
                    echo "<tr>
                        <td>{$m['fullname']}</td>
                        <td>{$m['medicine_name']}</td>
                        <td>{$m['dosage']} - {$m['frequency']}</td>
                        <td><span class='status ".strtolower($m['status'])."'>{$m['status']}</span></td>
                        <td><button class='btn' onclick='markTaken({$m['medicine_id']})'>✓ Mark Taken</button></td>
                    </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<script>
function markTaken(id) {
    fetch(`update_medicine_status.php?id=${id}&status=Taken`).then(() => location.reload());
}
document.getElementById('patient_id')?.addEventListener('blur', function() {
    fetch(`get_patient.php?id=${this.value}`).then(r=>r.json()).then(d=>document.getElementById('patient_name').value = d.fullname);
});
</script>
</body>
</html>