<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Schedules</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-calendar-alt"></i> Doctor Schedules</h1>
            <a href="index.php" class="btn primary">← Back to Dashboard</a>
        </header>

        <main>
            <?php
            $doctors = $pdo->query("SELECT * FROM doctors ORDER BY doctor_id")->fetchAll();
            foreach ($doctors as $doctor): ?>
            <div class="card">
                <div class="doctor-header">
                    <h3><?php echo $doctor['doctor_name']; ?></h3>
                    <span class="specialization"><?php echo $doctor['specialization']; ?></span>
                </div>
                <div class="schedule-info">
                    <div><strong>Schedule:</strong> <?php echo $doctor['schedule']; ?></div>
                    <div><strong>Max Patients/Day:</strong> <?php echo $doctor['max_patients']; ?></div>
                </div>
                <div class="alternate-doctor">
                    <strong>Alternate:</strong> 
                    <?php 
                    $alternates = [
                        1 => 'Dr. John Reyes',
                        2 => 'Dr. Angela Cruz', 
                        3 => 'Dr. Maria Santos'
                    ];
                    echo $alternates[$doctor['doctor_id']];
                    ?>
                </div>
            </div>
            <?php endforeach; ?>
        </main>
    </div>
</body>
</html>