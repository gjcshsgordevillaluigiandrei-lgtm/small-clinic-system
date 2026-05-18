<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Management</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-vial"></i> Laboratory Management</h1>
            <a href="index.php" class="btn primary">← Back to Dashboard</a>
        </header>

        <main>
            <!-- Add Lab Test Form -->
            <div class="card">
                <h3><i class="fas fa-plus"></i> Assign Laboratory Test</h3>
                <form action="lab_process.php" method="POST" id="labForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Patient ID</label>
                            <input type="number" name="patient_id" id="patient_id" required>
                            <small>Enter Patient ID to auto-fill name</small>
                        </div>
                        <div class="form-group">
                            <label>Patient Name</label>
                            <input type="text" id="patient_name" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Laboratory Test</label>
                        <select name="laboratory_type" required>
                            <option value="">Select Test</option>
                            <option value="X-ray">X-ray</option>
                            <option value="Ultrasound">Ultrasound</option>
                            <option value="CBC">CBC (Complete Blood Count)</option>
                            <option value="Urinalysis">Urinalysis</option>
                            <option value="Blood Chemistry">Blood Chemistry</option>
                            <option value="ECG">ECG</option>
                            <option value="Physical Exam">Physical Examination</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="Not Yet Taken">Not Yet Taken</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Results (Optional)</label>
                        <textarea name="result" rows="4" placeholder="Enter test results..."></textarea>
                    </div>
                    <button type="submit" class="btn primary">Assign Test</button>
                </form>
            </div>

            <!-- Laboratory Records Table -->
            <div class="card">
                <h3><i class="fas fa-list"></i> Laboratory Records</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lab ID</th>
                            <th>Patient</th>
                            <th>Test Type</th>
                            <th>Status</th>
                            <th>Results</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("
                            SELECT l.*, p.fullname 
                            FROM laboratory l 
                            JOIN patients p ON l.patient_id = p.patient_id 
                            ORDER BY l.lab_id DESC
                        ");
                        while ($row = $stmt->fetch()) {
                            $statusClass = strtolower(str_replace(' ', '-', $row['status']));
                            echo "<tr>
                                <td>{$row['lab_id']}</td>
                                <td>{$row['fullname']}</td>
                                <td>{$row['laboratory_type']}</td>
                                <td><span class='status {$statusClass}'>{$row['status']}</span></td>
                                <td>" . (strlen($row['result']) > 50 ? substr($row['result'], 0, 50) . '...' : $row['result']) . "</td>
                                <td>{$row['lab_id']}</td>
                                <td>
                                    <button class='btn' onclick='editLab({$row['lab_id']})'>Edit</button>
                                    <button class='btn danger' onclick='deleteLab({$row['lab_id']})'>Delete</button>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        // Auto-fill patient name
        document.getElementById('patient_id').addEventListener('blur', function() {
            const patientId = this.value;
            if (patientId) {
                fetch(`get_patient.php?id=${patientId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.fullname) {
                            document.getElementById('patient_name').value = data.fullname;
                        } else {
                            alert('Patient not found');
                            document.getElementById('patient_name').value = '';
                        }
                    });
            }
        });
    </script>
</body>
</html>