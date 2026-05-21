<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laboratory Management</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container">

<header>
    <h1><i class="fas fa-vial"></i> Laboratory Management</h1>
    <a href="index.php" class="btn primary">← Back</a>
</header>

<main>

<!-- FORM -->
<div class="card">
    <h3>Assign Laboratory Test</h3>

    <form action="lab_process.php" method="POST">

        <label>Patient ID</label>
        <input type="number" name="patient_id" id="patient_id" required>

        <label>Patient Name</label>
        <input type="text" id="patient_name" readonly>

        <label>Test Type</label>
        <select name="laboratory_type" required>
            <option value="">Select</option>
            <option>X-ray</option>
            <option>Ultrasound</option>
            <option>CBC</option>
            <option>Urinalysis</option>
            <option>Blood Chemistry</option>
            <option>ECG</option>
        </select>

        <label>Status</label>
        <select name="status">
            <option>Not Yet Taken</option>
            <option>Ongoing</option>
            <option>Completed</option>
        </select>

        <label>Results</label>
        <textarea name="result"></textarea>

        <button class="btn primary">Save</button>
    </form>
</div>

<!-- TABLE -->
<div class="card">
<h3>Laboratory Records</h3>

<table class="table">
<thead>
<tr>
    <th>ID</th>
    <th>Patient</th>
    <th>Test</th>
    <th>Status</th>
    <th>Result</th>
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
$date = $row['created_at'] ?? 'N/A';
?>

<tr>
    <td><?= $row['lab_id'] ?></td>
    <td><?= htmlspecialchars($row['fullname']) ?></td>
    <td><?= htmlspecialchars($row['laboratory_type']) ?></td>

    <td>
        <span class="status <?= $statusClass ?>">
            <?= htmlspecialchars($row['status']) ?>
        </span>
    </td>

    <td><?= htmlspecialchars($row['result']) ?></td>

    <td><?= $date ?></td>

    <td>
        <button class="btn" onclick="editLab(<?= $row['lab_id'] ?>)">Edit</button>
        <button class="btn danger" onclick="deleteLab(<?= $row['lab_id'] ?>)">Delete</button>
    </td>
</tr>

<?php } ?>

</tbody>
</table>
</div>

</main>
</div>

<script>
// auto patient name
document.getElementById('patient_id').addEventListener('blur', function(){
    fetch('get_patient.php?id=' + this.value)
    .then(res => res.json())
    .then(data => {
        document.getElementById('patient_name').value = data.fullname || '';
    });
});

// edit
function editLab(id){
    window.location.href = "edit_lab.php?id=" + id;
}

// delete (FIXED)
function deleteLab(id){
    if(confirm("Delete this record?")){
        window.location.href = "delete_lab.php?id=" + id;
    }
}
</script>

</body>
</html>