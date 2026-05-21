<?php include 'config.php'; ?>
<div class="section">
    <h2>Appointments</h2>
    <table class="table">
        <thead>
            <tr><th>Appointment ID</th><th>Patient</th><th>Doctor</th><th>Date/Time</th><th>Laboratory</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("
                SELECT a.*, p.fullname as patient_name, d.doctor_name 
                FROM appointments a 
                JOIN patients p ON a.patient_id = p.patient_id 
                JOIN doctors d ON a.doctor_id = d.doctor_id
                ORDER BY a.appointment_date DESC
            ");
            while ($row = $stmt->fetch()) {
                echo "<tr>
                    <td>{$row['appointment_id']}</td>
                    <td>{$row['patient_name']}</td>
                    <td>{$row['doctor_name']}</td>
                    <td>{$row['appointment_date']}</td>
                    <td>" . ($row['laboratory_required'] ? 'Yes' : 'No') . "</td>
                    <td><span class='status " . strtolower($row['status']) . "'>{$row['status']}</span></td>
                    <td>
                        <button class='btn' onclick='editAppointment({$row['appointment_id']})'>Edit</button>
                        <button class='btn danger' onclick='deleteAppointment({$row['appointment_id']})'>Cancel</button>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function editAppointment(id) {
    window.location.href = `edit_appointment.php?id=${id}`;
}
function deleteAppointment(id) {
    if (confirm('Cancel this appointment? It will be marked as Cancelled.')) {
        fetch(`delete_appointment.php?id=${id}`)
            .then(() => location.reload());
    }
}
</script>