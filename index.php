<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Small Clinic Management System</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-clinic-medical"></i> Small Clinic Management System</h1>
            <nav>
                <a href="index.php" class="active">Dashboard</a>
                <a href="doctor_schedule.php">Doctors</a>
                <a href="laboratory.php">Laboratory</a>
                <a href="payments.php">Payments</a>
                <a href="clearance.php">Clearance</a>
            </nav>
        </header>

        <main>
            <!-- Success/Error Messages -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i> Operation completed successfully!
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i> An error occurred. Please try again.
                </div>
            <?php endif; ?>

            <!-- Dashboard Stats -->
            <div class="dashboard-stats">
                <?php
                $total_patients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
                $pending_appointments = $pdo->query("SELECT COUNT(*) FROM appointments WHERE status='Pending'")->fetchColumn();
                $cleared_patients = $pdo->query("SELECT COUNT(*) FROM payments WHERE payment_status='Paid'")->fetchColumn();
                $today_patients = $pdo->query("SELECT COUNT(*) FROM appointments WHERE DATE(appointment_date) = CURDATE() AND status='Completed'")->fetchColumn();
                ?>
                <div class="stat-card">
                    <h3><i class="fas fa-users"></i> Total Patients</h3>
                    <span class="stat-number"><?php echo $total_patients; ?></span>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-calendar-times"></i> Pending Appointments</h3>
                    <span class="stat-number"><?php echo $pending_appointments; ?></span>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-check-circle"></i> Cleared Today</h3>
                    <span class="stat-number"><?php echo $today_patients; ?></span>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-dollar-sign"></i> Paid Patients</h3>
                    <span class="stat-number"><?php echo $cleared_patients; ?></span>
                </div>
            </div>

            <!-- Quick Actions - ALL LINKS ADDED -->
            <div class="quick-actions">
                <a href="#patient-form" class="action-btn primary">
                    <i class="fas fa-user-plus"></i> Register Patient
                </a>
                <a href="doctor_schedule.php" class="action-btn">
                    <i class="fas fa-calendar-alt"></i> Doctor Schedules
                </a>
                <a href="#appointment-form" class="action-btn">
                    <i class="fas fa-calendar-plus"></i> New Appointment
                </a>
                <a href="laboratory.php" class="action-btn">
                    <i class="fas fa-vial"></i> Laboratory
                </a>
                <a href="payments.php" class="action-btn">
                    <i class="fas fa-money-bill-wave"></i> Payments
                </a>
                <a href="clearance.php" class="action-btn">
                    <i class="fas fa-check-circle"></i> Clearance
                </a>
            </div>

            <!-- Recent Patients Table -->
            <div class="card">
                <h3><i class="fas fa-list"></i> Recent Patients</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Registered</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("
                            SELECT p.*, COALESCE(py.payment_status, 'No Payment') as payment_status 
                            FROM patients p 
                            LEFT JOIN payments py ON p.patient_id = py.patient_id 
                            ORDER BY p.date_registered DESC 
                            LIMIT 10
                        ");
                        while ($row = $stmt->fetch()) {
                            $statusClass = strtolower(str_replace(' ', '-', $row['payment_status']));
                            echo "<tr>
                                <td>{$row['patient_id']}</td>
                                <td>{$row['fullname']}</td>
                                <td>{$row['age']}</td>
                                <td>{$row['gender']}</td>
                                <td>" . date('M j', strtotime($row['date_registered'])) . "</td>
                                <td><span class='status {$statusClass}'>{$row['payment_status']}</span></td>
                                <td>
                                    <a href='clearance.php?patient_id={$row['patient_id']}' class='btn primary'>View</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Patient Registration Modal -->
    <div id="patient-form" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><i class="fas fa-user-plus"></i> Patient Registration</h2>
            <form action="patient_register.php" method="POST">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="fullname" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Age *</label>
                        <input type="number" name="age" min="1" max="120" required>
                    </div>
                    <div class="form-group">
                        <label>Gender *</label>
                        <select name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Address *</label>
                    <textarea name="address" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Contact Number *</label>
                    <input type="tel" name="contact_number" pattern="[0-9]{10,11}" required>
                </div>
                <button type="submit" class="btn primary">
                    <i class="fas fa-save"></i> Register Patient
                </button>
            </form>
        </div>
    </div>

    <!-- Appointment Modal -->
    <div id="appointment-form" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><i class="fas fa-calendar-plus"></i> New Appointment</h2>
            <form action="appointment_process.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>Patient ID *</label>
                        <input type="number" name="patient_id" required>
                    </div>
                    <div class="form-group">
                        <label>Doctor *</label>
                        <select name="doctor_id" id="doctor_select" required onchange="checkAvailability()">
                            <option value="">Select Doctor</option>
                            <?php
                            $doctors = $pdo->query("SELECT * FROM doctors ORDER BY doctor_name");
                            while ($doctor = $doctors->fetch()) {
                                echo "<option value='{$doctor['doctor_id']}'>{$doctor['doctor_name']} ({$doctor['specialization']})</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date *</label>
                        <input type="date" name="appointment_date" id="appointment_date" required onchange="checkAvailability()">
                    </div>
                    <div class="form-group">
                        <label>Time *</label>
                        <input type="time" name="appointment_time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Laboratory Required?</label>
                    <select name="laboratory_required">
                        <option value="">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>
                <div id="availability-status"></div>
                <button type="submit" class="btn primary" id="book-btn" disabled>
                    <i class="fas fa-calendar-check"></i> Book Appointment
                </button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>