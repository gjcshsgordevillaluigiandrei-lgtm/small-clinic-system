<?php
include 'config.php';

if (!isset($_GET['id'])) {
    die("No ID provided");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM laboratory WHERE lab_id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    die("Record not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Laboratory</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="edit_lab.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script defer src="edit_lab.js"></script>
</head>

<body>

<div class="topbar">
    <h1><i class="fas fa-vial"></i> Laboratory Management</h1>
    <a href="laboratory.php">← Back to Panel</a>
</div>

<div class="container">

    <div class="card">
        <h3>Edit Laboratory Record</h3>

        <form action="update_lab.php" method="POST">

            <input type="hidden" name="lab_id" value="<?= $data['lab_id'] ?>">

            <label>Patient ID</label>
            <input type="number" name="patient_id" value="<?= $data['patient_id'] ?>" required>

            <label>Test Type</label>
            <select name="laboratory_type">
                <option <?= $data['laboratory_type']=='X-ray'?'selected':'' ?>>X-ray</option>
                <option <?= $data['laboratory_type']=='Ultrasound'?'selected':'' ?>>Ultrasound</option>
                <option <?= $data['laboratory_type']=='CBC'?'selected':'' ?>>CBC</option>
                <option <?= $data['laboratory_type']=='Urinalysis'?'selected':'' ?>>Urinalysis</option>
                <option <?= $data['laboratory_type']=='Blood Chemistry'?'selected':'' ?>>Blood Chemistry</option>
                <option <?= $data['laboratory_type']=='ECG'?'selected':'' ?>>ECG</option>
            </select>

            <label>Status</label>
            <select name="status">
                <option <?= $data['status']=='Not Yet Taken'?'selected':'' ?>>Not Yet Taken</option>
                <option <?= $data['status']=='Ongoing'?'selected':'' ?>>Ongoing</option>
                <option <?= $data['status']=='Completed'?'selected':'' ?>>Completed</option>
            </select>

            <label>Results</label>
            <textarea name="result"><?= htmlspecialchars($data['result']) ?></textarea>

            <button type="submit" class="btn primary">Update</button>
            <a href="laboratory.php" class="btn danger">Cancel</a>

        </form>
    </div>

</div>

</body>
</html>