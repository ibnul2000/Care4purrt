<?php
session_start();
include('db.php');

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Handle Health Status Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_health_status'])) {
    $pet_id = mysqli_real_escape_string($conn, $_POST['pet_id']);
    $health_status = mysqli_real_escape_string($conn, $_POST['health_status']);
    $diet_plan = mysqli_real_escape_string($conn, $_POST['diet_plan']);
    $other_recommendations = mysqli_real_escape_string($conn, $_POST['other_recommendations']);
    $doctor_name = mysqli_real_escape_string($conn, $_POST['doctor_name']);
    $doctor_specialty = mysqli_real_escape_string($conn, $_POST['doctor_specialty']);

    $sql = "UPDATE pets 
            SET health_status='$health_status', diet_plan='$diet_plan', other_recommendations='$other_recommendations',
                doctor_name='$doctor_name', doctor_specialty='$doctor_specialty'
            WHERE id='$pet_id'";

    if (mysqli_query($conn, $sql)) {
        $success_message = "Health status and recommendations updated successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Fetch all pets (for select dropdown)
$sql = "SELECT id, name, age FROM pets WHERE owner_name IN (SELECT username FROM users WHERE role='owner')";
$result = mysqli_query($conn, $sql);

// Fetch all updated health records
$sql_list = "SELECT id, name, age, health_status, diet_plan, other_recommendations, doctor_name, doctor_specialty 
             FROM pets WHERE owner_name IN (SELECT username FROM users WHERE role='owner')";
$result_list = mysqli_query($conn, $sql_list);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Health Status - Care4Purrt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f0f2f5;
    }
    .container {
        max-width: 900px;
        margin-top: 50px;
    }
    .card-modern {
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 12px 25px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    .card-modern:hover { transform: translateY(-5px); }
    h2 { text-align: center; margin-bottom: 30px; color: #4b2b7f; }
    .form-label { font-weight: 600; }
    .btn-modern {
        border-radius: 50px;
        font-weight: 600;
        padding: 12px 25px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    .btn-primary-modern {
        background: linear-gradient(90deg, #7F00FF, #E100FF);
        color: #fff;
        border: none;
    }
    .btn-primary-modern:hover { transform: scale(1.05); opacity: 0.9; }
    .alert { border-radius: 15px; font-weight: 500; }
    .health-list {
        margin-top: 40px;
    }
    .health-item {
        background: #fff;
        border-left: 5px solid #7F00FF;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .health-item:hover { transform: scale(1.02); }
    .health-item h5 { font-weight: 600; color: #4b2b7f; }
    .health-item p { margin-bottom: 5px; }
    .btn-back {
        display: block;
        width: 250px;
        margin: 30px auto;
        font-size: 1.1rem;
        border-radius: 50px;
        padding: 12px;
        background: #6c757d;
        color: #fff;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s;
    }
    .btn-back:hover { background: #5a6268; transform: scale(1.05); }
</style>
</head>
<body>
<div class="container">
    <h2>Update Pet Health Status</h2>

    <?php if (isset($success_message)) echo "<div class='alert alert-success text-center'>{$success_message}</div>"; ?>
    <?php if (isset($error_message)) echo "<div class='alert alert-danger text-center'>{$error_message}</div>"; ?>

    <!-- Form Card -->
    <div class="card-modern">
        <form method="POST">
            <div class="mb-3">
                <label for="pet_id" class="form-label">Select Pet:</label>
                <select name="pet_id" id="pet_id" class="form-select" required>
                    <option value="" disabled selected>Select a Pet</option>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo $row['name']; ?> (Age: <?php echo $row['age']; ?>)
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="health_status" class="form-label">Health Status:</label>
                <textarea name="health_status" id="health_status" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="diet_plan" class="form-label">Diet & Nutrition Plan:</label>
                <textarea name="diet_plan" id="diet_plan" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="other_recommendations" class="form-label">Other Recommendations:</label>
                <textarea name="other_recommendations" id="other_recommendations" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label for="doctor_name" class="form-label">Doctor's Name:</label>
                <input type="text" name="doctor_name" id="doctor_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="doctor_specialty" class="form-label">Doctor's Specialty:</label>
                <select name="doctor_specialty" id="doctor_specialty" class="form-select" required>
                    <option value="General Paw-sician">General Paw-sician</option>
                    <option value="Fur-skin Specialist">Fur-skin Specialist</option>
                    <option value="Heart Purr-fessional">Heart Purr-fessional</option>
                    <option value="Sugar Sniffer">Sugar Sniffer</option>
                    <option value="Brainy Whisker">Brainy Whisker</option>
                    <option value="Eye Meow-tist">Eye Meow-tist</option>
                    <option value="Tooth Fairy Vet">Tooth Fairy Vet</option>
                    <option value="Bone & Paw Surgeon">Bone & Paw Surgeon</option>
                    <option value="Behavior Buddy">Behavior Buddy</option>
                    <option value="Tail Chef">Tail Chef</option>
                </select>
            </div>

            <button type="submit" name="update_health_status" class="btn btn-primary-modern btn-modern w-100 mt-3">Update Health Status</button>
        </form>
    </div>

    <!-- Health Records List -->
    <?php if(mysqli_num_rows($result_list) > 0): ?>
        <div class="health-list">
            <h3 class="mb-3 text-center" style="color:#4b2b7f;">Health Records</h3>
            <?php while($pet = mysqli_fetch_assoc($result_list)): ?>
                <div class="health-item">
                    <h5><?php echo htmlspecialchars($pet['name']); ?> (Age: <?php echo $pet['age']; ?>)</h5>
                    <p><strong>Health Status:</strong> <?php echo htmlspecialchars($pet['health_status']); ?></p>
                    <p><strong>Diet Plan:</strong> <?php echo htmlspecialchars($pet['diet_plan']); ?></p>
                    <p><strong>Other Recommendations:</strong> <?php echo htmlspecialchars($pet['other_recommendations']); ?></p>
                    <p><strong>Doctor:</strong> <?php echo htmlspecialchars($pet['doctor_name']); ?> (<?php echo htmlspecialchars($pet['doctor_specialty']); ?>)</p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <!-- Back to Dashboard Button -->
    <a href="doctor_dashboard.php" class="btn-back">← Back to Dashboard</a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
