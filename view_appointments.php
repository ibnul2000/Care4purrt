<?php
session_start();
include('db.php');

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch appointments for the logged-in doctor
$sql_appointments = "SELECT * FROM appointments WHERE doctor_username = '$username' ORDER BY appointment_date, appointment_time";
$result_appointments = mysqli_query($conn, $sql_appointments);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Appointments - Care4Purrt</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #f9f9f9, #e0f7fa);
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    padding: 30px 10px;
}

/* Container */
.container {
    max-width: 1000px;
    margin: auto;
}

/* Page Title */
h2 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 700;
    color: #007bff;
}

/* Appointment Cards */
.appointment-card {
    background: white;
    border-radius: 20px;
    padding: 25px 20px;
    margin-bottom: 20px;
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s forwards;
}

.appointment-card img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
}

.appointment-info {
    flex: 1;
}

.appointment-info h5 {
    margin: 0 0 5px 0;
    font-weight: 600;
}

.appointment-info p {
    margin: 2px 0;
    font-size: 0.95rem;
    color: #555;
}

/* Buttons */
.btn-back {
    background: linear-gradient(135deg, #FF6F91, #FF9671);
    border: none;
    color: white;
    border-radius: 50px;
    padding: 10px 25px;
    font-size: 1rem;
    font-weight: 600;
    transition: 0.3s;
}
.btn-back:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
}

/* No appointment message */
.no-appointments {
    text-align: center;
    font-size: 1.1rem;
    color: #777;
    margin-top: 50px;
}

/* Animations */
@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
</head>
<body>

<div class="container">
    <h2>My Appointments</h2>

    <?php if (mysqli_num_rows($result_appointments) > 0) { ?>
        <?php while ($appointment = mysqli_fetch_assoc($result_appointments)) { ?>
            <div class="appointment-card">
                <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Pet Icon">
                <div class="appointment-info">
                    <h5><?php echo ucfirst($appointment['pet_owner_username']); ?></h5>
                    <p><i class="fa fa-paw me-2"></i>Type: <?php echo $appointment['pet_type']; ?></p>
                    <p><i class="fa fa-bone me-2"></i>Age: <?php echo $appointment['pet_age']; ?></p>
                    <p><i class="fa fa-heartbeat me-2"></i>Problem: <?php echo $appointment['pet_problem']; ?></p>
                    <p><i class="fa fa-calendar me-2"></i>Date: <?php echo $appointment['appointment_date']; ?></p>
                    <p><i class="fa fa-clock me-2"></i>Time: <?php echo $appointment['appointment_time']; ?></p>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p class="no-appointments">You currently have no appointments.</p>
    <?php } ?>

    <div class="text-center mt-4">
        <a href="doctor_dashboard.php" class="btn btn-back">
            <i class="fas fa-home me-2"></i> Go Back to Dashboard
        </a>
    </div>
</div>

</body>
</html>
