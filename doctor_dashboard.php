<?php
session_start();

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Care4Purrt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100%;
            background-color: #343a40;
            padding-top: 30px;
            color: #fff;
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .sidebar a {
            display: block;
            color: #fff;
            padding: 15px 20px;
            text-decoration: none;
            font-size: 1rem;
            border-radius: 8px;
            margin: 5px 10px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
        }

        /* Content */
        .content {
            margin-left: 220px;
            padding: 30px 40px;
        }

        /* Hero Section */
        .hero {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 40px;
            height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.6);
        }
        .hero-text {
            position: relative;
            color: #fff;
            text-align: center;
            z-index: 2;
        }
        .hero-text h1 {
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
        }
        .hero-text p {
            font-size: 1.2rem;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }

        /* Dashboard Cards */
        .dashboard .card {
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .dashboard .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card-title {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .card-text {
            font-size: 0.95rem;
        }
        .btn-custom {
            width: 100%;
            border-radius: 25px;
            font-size: 1rem;
        }

        /* Footer */
        .footer {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3><?php echo htmlspecialchars($username); ?></h3>
    <a href="view_appointments.php"><i class="fas fa-calendar-alt me-2"></i>View Appointments</a>
    <a href="log_pet_mood.php"><i class="fas fa-smile-beam me-2"></i>Log Pet Mood</a>
    <a href="update_health_status.php"><i class="fas fa-heartbeat me-2"></i>Update Health Status</a>
    <a href="doctor_profile.php"><i class="fas fa-user-md me-2"></i>Doctor Profile</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <!-- Hero Section -->
    <div class="hero">
        <img src="https://i.pinimg.com/1200x/21/c8/87/21c8872fa9cba621ffc3c932631a3637.jpg" alt="Doctor Dashboard">
        <div class="hero-text">
            <h1>Welcome, Doctor <?php echo htmlspecialchars($username); ?>!</h1>
            <p>Manage appointments, log pet moods, and update health statuses.</p>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row row-cols-1 row-cols-md-2 g-4 dashboard">
        <div class="col">
            <div class="card">
                <img src="https://i.pinimg.com/736x/45/2c/db/452cdb77bfa22e9ecca2400f77894efc.jpg" class="card-img-top" alt="Log Mood">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-smile-beam me-2"></i>Log Pet Mood</h5>
                    <p class="card-text">Track the mood and behavior of pets during your treatment.</p>
                    <a href="log_pet_mood.php" class="btn btn-warning btn-custom">Log Mood</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <img src="https://i.pinimg.com/736x/7f/1e/4b/7f1e4bd76dc469dd82c91d2588ec8d7f.jpg" class="card-img-top" alt="Update Health Status">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-heartbeat me-2"></i>Update Health Status</h5>
                    <p class="card-text">Update the health status of pets after visits or checkups.</p>
                    <a href="update_health_status.php" class="btn btn-danger btn-custom">Update Status</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <img src="https://i.pinimg.com/736x/a5/88/e4/a588e4759c8760468999b92fec3e3b35.jpg" class="card-img-top" alt="View Appointments">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="fas fa-calendar-alt me-2"></i>View Appointments</h5>
                    <p class="card-text">Check upcoming and past appointments for your patients.</p>
                    <a href="view_appointments.php" class="btn btn-info btn-custom">View Appointments</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer mt-5">
        &copy; 2025 Care4Purrt. All rights reserved.
    </div>
</div>
<a href="doctor_profile.php"><i class="fas fa-user-md me-2"></i>Doctor Profile</a>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!-- Go Back to Landing Page -->
<div class="text-center mt-4">
    <a href="landing.php" class="btn btn-warning btn-lg">
        <i class="fas fa-home me-2"></i>Go Back to Homepage
    </a>
</div>
