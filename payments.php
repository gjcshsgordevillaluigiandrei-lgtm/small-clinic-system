<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-money-bill-wave"></i> Payments Management</h1>
            <a href="index.php" class="btn primary">← Back to Dashboard</a>
        </header>

        <main>
            <div class="card">
                <h3>Payment Records</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Patient Name</th>
                            <th>Consultation</th>
                            <th>Lab Fee</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("
                            SELECT p.patient_id, p.fullname, py.* 
                            FROM payments py 
                            JOIN patients p ON py.patient_id = p.patient_id
                            ORDER BY py.payment_id DESC
                        ");
                        while ($row = $stmt->fetch()) {
                            $statusClass = strtolower($row['payment_status']);
                            echo "<tr>
                                <td>{$row['patient_id']}</td>
                                <td>{$row['fullname']}</td>
                                <td>₱" . number_format($row['consultation_fee'], 2) . "</td>
                                <td>₱" . number_format($row['laboratory_fee'], 2) . "</td>
                                <td>₱<strong>" . number_format($row['total_amount'], 2) . "</strong></td>
                                <td><span class='status {$statusClass}'>{$row['payment_status']}</span></td>
                                <td>
                                    <select onchange='updatePayment({$row['patient_id']}, this.value)'>
                                        <option value='Unpaid'" . ($row['payment_status']=='Unpaid'?' selected':'') . ">Unpaid</option>
                                        <option value='Partial'" . ($row['payment_status']=='Partial'?' selected':'') . ">Partial</option>
                                        <option value='Paid'" . ($row['payment_status']=='Paid'?' selected':'') . ">Paid</option>
                                    </select>
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
        function updatePayment(patientId, status) {
            fetch(`update_payment.php?patient_id=${patientId}&status=${status}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    </script>
</body>
</html>