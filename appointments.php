<?php include 'config.php'; ?>
<div class="section">
    <h2>Appointments</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date/Time</th>
                <th>Laboratory</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("
                SELECT a.*, p.fullname as patient_name, d.doctor_name 
                FROM appointments a 
                JOIN patients p ON a.patient_id = p.patient_id 
                JOIN doctors d ON a.doctor_id = d.doctor_id
            ");
            while ($row = $stmt->fetch()) {
                echo "<tr>
                    <td>{$row['appointment_id']}</td>
                    <td>{$row['patient_name']}</td>
                    <td>{$row['doctor_name']}</td>
                    <td>{$row['appointment_date']}</td>
                    <td>" . ($row['laboratory_required'] ? 'Yes' : 'No') . "</td>
                    <td><span class='status {$row['status']}'>{$row['status']}</span></td>
                    <td>
                        <button class='btn'>Edit</button>
                        <button class='btn'>Delete</button>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>