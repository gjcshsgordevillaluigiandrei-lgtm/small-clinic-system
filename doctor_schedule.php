<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Schedules</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-calendar-alt"></i> Doctor Schedules & Availability</h1>
            <a href="index.php" class="btn primary">← Back to Dashboard</a>
        </header>
        <main>
            <?php
            $doctors = $pdo->query("SELECT * FROM doctors ORDER BY doctor_id")->fetchAll();
            foreach ($doctors as $doctor):
                // Count today's appointments
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND DATE(appointment_date) = CURDATE() AND status != 'Cancelled'");
                $stmt->execute([$doctor['doctor_id']]);
                $today_count = $stmt->fetchColumn();
                $remaining = $doctor['max_patients'] - $today_count;
                $remaining_class = $remaining > 0 ? 'status completed' : 'status cancelled';
            ?>
            <div class="card">
                <div class="doctor-header">
                    <h3><?php echo htmlspecialchars($doctor['doctor_name']); ?></h3>
                    <span class="specialization"><?php echo htmlspecialchars($doctor['specialization']); ?></span>
                </div>
                <div class="schedule-info">
                    <div><strong>Schedule:</strong> <?php echo htmlspecialchars($doctor['schedule']); ?></div>
                    <div><strong>Max Patients per day:</strong> <?php echo $doctor['max_patients']; ?></div>
                    <div><strong>📅 Today's appointments:</strong> <?php echo $today_count; ?> / <?php echo $doctor['max_patients']; ?></div>
                    <div><strong>✅ Remaining slots today:</strong> <span class="<?php echo $remaining_class; ?>"><?php echo max(0, $remaining); ?></span></div>
                </div>
                <div class="alternate-doctor">
                    <strong>Alternate:</strong> 
                    <?php 
                    $alternates = [1 => 'Dr. John Reyes', 2 => 'Dr. Angela Cruz', 3 => 'Dr. Maria Santos'];
                    echo $alternates[$doctor['doctor_id']] ?? 'N/A';
                    ?>
                </div>
            </div>
            <?php endforeach; ?>
        </main>
    </div>
</body>
</html>