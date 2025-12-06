<?php
session_start();

// Check if the user is logged in and is a pet owner
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
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
    <title>Pet Owner Dashboard - Care4Purrt</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #14283bff;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100%;
            background-color: #1f2a36;
            padding-top: 30px;
            color: #fff;
            box-shadow: 4px 0 20px rgba(0,0,0,0.2);
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 1.7rem;
            font-weight: bold;
        }

        .sidebar-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            padding: 18px 22px;
            text-decoration: none;
            font-size: 1.15rem;
            font-weight: 600;
            border-radius: 12px;
            margin: 12px 15px;
            transition: 0.3s ease-in-out;
            background-color: #2d3b4a;
        }

        .sidebar-btn:hover {
            background-color: #445364;
            transform: translateX(10px);
        }

        .logout-btn {
            background-color: #b32323;
        }

        .logout-btn:hover {
            background-color: #d93434;
            transform: translateX(10px);
        }

        /* Content */
        .content {
            margin-left: 240px;
            padding: 30px 40px;
        }

        /* Hero Section */
        .hero {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 350px;
        }
        .hero img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.7);
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
        }
        .hero-text p {
            font-size: 1.2rem;
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
            height: 220px;
            object-fit: cover;
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
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3><?php echo htmlspecialchars($username); ?></h3>

        <a href="landing.php" class="sidebar-btn">
            <i class="fas fa-home"></i> Homepage
        </a>

        <a href="create_profile.php" class="sidebar-btn">
            <i class="fas fa-user-circle"></i> Pet Owner Profile
        </a>

        <a href="logout.php" class="sidebar-btn logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <!-- Main Content -->
    <div class="content">

        <!-- Hero Section -->
        <div class="hero">
            <img src="https://i.pinimg.com/1200x/03/39/9c/03399c4203de344d134f0d78c1a35840.jpg" alt="">
            <div class="hero-text">
                <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                <p>Manage your pets, appointments, and health records easily.</p>
            </div>
        </div>

        <!-- Dashboard Cards (ALL CARDS RESTORED) -->
        <div class="row row-cols-1 row-cols-md-2 g-4 dashboard">

            <!-- CARD 1: Manage Pets -->
            <div class="col">
                <div class="card">
                    <img src="https://i.pinimg.com/1200x/7e/51/e1/7e51e19ddde7826c65d6de931b321532.jpg" class="card-img-top" alt="Manage Pets">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-paw me-2"></i>Manage Pets</h5>
                        <p class="card-text">View, add, edit, or delete your pet profiles.</p>
                        <a href="viewpets.php" class="btn btn-primary w-100">View Pets</a>
                    </div>
                </div>
            </div>

            <!-- CARD 2: Book Appointment -->
            <div class="col">
                <div class="card">
                    <img src="https://i.pinimg.com/736x/5a/9d/60/5a9d604fbebd15068590f97a54e95d15.jpg" class="card-img-top" alt="Book Appointment">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i>Book Appointment</h5>
                        <p class="card-text">Book, update, or cancel appointments with your pet's doctor.</p>
                        <a href="book_appointment.php" class="btn btn-success w-100">Book Now</a>
                    </div>
                </div>
            </div>

            <!-- CARD 3: Health Records -->
            <div class="col">
                <div class="card">
                    <img src="https://i.pinimg.com/736x/5f/57/e1/5f57e11979e9fe2e6d73e7be01fff05f.jpg" class="card-img-top" alt="Health Records">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-notes-medical me-2"></i>Health Records</h5>
                        <p class="card-text">Check your pet's medical history and records.</p>
                        <a href="view_health_records.php" class="btn btn-info w-100">View Records</a>
                    </div>
                </div>
            </div>

            <!-- CARD 4: Log Mood -->
            <div class="col">
                <div class="card">
                    <img src="https://i.pinimg.com/1200x/50/75/12/507512e6831f8bba49402ea56ed468fd.jpg" class="card-img-top" alt="Log Mood">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-smile-beam me-2"></i>Log Pet Mood</h5>
                        <p class="card-text">Track your pet's mood and behavior patterns.</p>
                        <a href="log_mood.php" class="btn btn-warning w-100">Log Mood</a>
                    </div>
                </div>
            </div>

            <!-- CARD 5: Nutrition Plan -->
            <div class="col">
                <div class="card">
                    <img src="https://i.pinimg.com/736x/f5/db/4b/f5db4b145c3cbf13b724aab806838e45.jpg" class="card-img-top" alt="Nutrition Plan">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-utensils me-2"></i>CareTailor: Nutrition Plan</h5>
                        <p class="card-text">Personalized nutrition and exercise plan for your pet.</p>
                        <a href="get_nutrition_plan.php" class="btn btn-primary w-100">Get Plan</a>
                    </div>
                </div>
            </div>

            <!-- CARD 6: Travel Readiness -->
            <div class="col">
                <div class="card">
                    <img src="https://i.pinimg.com/736x/61/74/d1/6174d1552807f1673952011be51526d5.jpg" class="card-img-top" alt="Travel Readiness">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-plane me-2"></i>PurrPort: Travel Readiness</h5>
                        <p class="card-text">Check if your pet is ready for travel and obtain passport.</p>
                        <a href="view_travel_readiness.php" class="btn btn-success w-100">Check Readiness</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="footer mt-5">
            &copy; 2025 Care4Purrt. All rights reserved.
        </div>

    </div>

</body>
</html>

<!-- Back Button -->
<div class="text-center mt-4">
    <a href="landing.php" class="btn btn-warning btn-lg">
        <i class="fas fa-home me-2"></i>Go Back to Homepage
    </a>
</div>
