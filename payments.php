<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments Management</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                            <th>Consultation Fee</th>
                            <th>Lab Fee</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $stmt = $pdo->query("
                            SELECT 
                                py.payment_id,
                                py.patient_id,
                                p.fullname,
                                py.consultation_fee,
                                py.laboratory_fee,
                                py.total_amount,
                                py.payment_status
                            FROM payments py
                            JOIN patients p ON py.patient_id = p.patient_id
                            ORDER BY py.payment_id DESC
                        ");

                        while ($row = $stmt->fetch()) {

                            $statusClass = strtolower($row['payment_status']);
                        ?>
                            <tr>
                                <td><?= $row['patient_id'] ?></td>
                                <td><?= htmlspecialchars($row['fullname']) ?></td>

                                <td>₱<?= number_format($row['consultation_fee'] ?? 0, 2) ?></td>

                                <td>₱<?= number_format($row['laboratory_fee'] ?? 0, 2) ?></td>

                                <td>
                                    ₱<strong>
                                        <?= number_format($row['total_amount'] ?? 0, 2) ?>
                                    </strong>
                                </td>

                                <td>
                                    <span class="status <?= $statusClass ?>">
                                        <?= $row['payment_status'] ?>
                                    </span>
                                </td>

                                <td>
                                    <select onchange="updatePayment(<?= $row['payment_id'] ?>, this.value)">
                                        <option value="Unpaid" <?= ($row['payment_status'] == 'Unpaid') ? 'selected' : '' ?>>
                                            Unpaid
                                        </option>
                                        <option value="Partial" <?= ($row['payment_status'] == 'Partial') ? 'selected' : '' ?>>
                                            Partial
                                        </option>
                                        <option value="Paid" <?= ($row['payment_status'] == 'Paid') ? 'selected' : '' ?>>
                                            Paid
                                        </option>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </main>

    </div>

    <script>
        function updatePayment(paymentId, status) {
            fetch(`update_payment.php?payment_id=${paymentId}&status=${status}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert("Update failed!");
                    }
                })
                .catch(err => console.error(err));
        }
    </script>

</body>
</html>