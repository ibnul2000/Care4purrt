<?php
session_start();
include('db.php');

// Check if the user is logged in and is a pet owner
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php"); // Redirect to login if not logged in or wrong role
    exit();
}

$username = $_SESSION['username'];

// Fetch pets for the logged-in pet owner
$sql = "SELECT * FROM pets WHERE owner_name = '$username'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Health Records - Care4Purrt</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 900px;
            margin-top: 50px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
        }

        .card-body {
            background-color: #f8f9fa;
        }

        .table th {
            background-color: #f1f1f1;
            color: #007bff;
        }

        .btn-custom {
            padding: 10px 20px;
            font-size: 1.1rem;
            border-radius: 25px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .card-header, .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Health Records History</h2>

        <?php while ($row = mysqli_fetch_assoc($result)) { 
            // Fetch the pet's health record history
            $pet_name = $row['name'];  // Using 'name' instead of 'pet_name'

            // Query to get the pet mood logs for the specific pet by name
            $sql_moods = "SELECT * FROM pet_moods WHERE name = '$pet_name' ORDER BY created_at DESC"; // Use 'name' as reference now
            $result_moods = mysqli_query($conn, $sql_moods);

            // Query to get the doctor's suggestions for the pet
            $sql_health = "SELECT * FROM pets WHERE name = '$pet_name'"; // Use 'name' here as well
            $result_health = mysqli_query($conn, $sql_health);
            $health_data = mysqli_fetch_assoc($result_health);
        ?>

            <div class="card mb-3">
                <div class="card-header"><?php echo $row['name']; ?> (Age: <?php echo $row['pet_age']; ?>)</div>
                <div class="card-body">
                    <h5>Health Status</h5>
                    <p><?php echo $health_data['health_status']; ?></p>

                    <h5>Nutrition and Diet Plan</h5>
                    <p><?php echo $health_data['diet_plan']; ?></p>

                    <h5>Other Recommendations</h5>
                    <p><?php echo $health_data['other_recommendations']; ?></p>

                    <h5>Doctor's Information</h5>
                    <p><strong>Doctor's Name:</strong> <?php echo $health_data['doctor_name']; ?></p>
                    <p><strong>Doctor's Specialty:</strong> <?php echo $health_data['doctor_specialty']; ?></p>

                    <h5>Pet Mood History</h5>
                    <?php if (mysqli_num_rows($result_moods) > 0) { ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Energy Level</th>
                                    <th>Appetite</th>
                                    <th>Social Interaction</th>
                                    <th>Play Behavior</th>
                                    <th>Sleep & Rest</th>
                                    <th>Vocalization</th>
                                    <th>Other Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($mood = mysqli_fetch_assoc($result_moods)) { ?>
                                    <tr>
                                        <td><?php echo $mood['energy_level']; ?></td>
                                        <td><?php echo $mood['appetite']; ?></td>
                                        <td><?php echo $mood['social_interaction']; ?></td>
                                        <td><?php echo $mood['play_behavior']; ?></td>
                                        <td><?php echo $mood['sleep_rest']; ?></td>
                                        <td><?php echo $mood['vocalization']; ?></td>
                                        <td><?php echo $mood['other_info']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p>No mood logs available for this pet.</p>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 Care4Purrt. All rights reserved.</p>
    </div>

</body>
</html>
