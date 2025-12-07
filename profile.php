<?php
session_start();
include('db.php');

// Check if the user is logged in and is a pet owner
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch pet owner profile if it exists
$sql = "SELECT * FROM pet_owner_profiles WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$profile = mysqli_fetch_assoc($result);

// Handle Profile Update or Creation
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $passport = mysqli_real_escape_string($conn, $_POST['passport']);
    $additional_info = mysqli_real_escape_string($conn, $_POST['additional_info']);
    $profile_picture = $profile['profile_picture'] ?? "";

    // Handling profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $target_file;
        } else {
            $msg = "Error uploading profile picture.";
        }
    }

    // Update or insert profile
    if ($profile) {
        $sql_update = "UPDATE pet_owner_profiles SET name='$name', contact_number='$contact_number', passport='$passport', profile_picture='$profile_picture', additional_info='$additional_info' WHERE username='$username'";
        if (mysqli_query($conn, $sql_update)) { $msg = "Profile updated successfully!"; }
        else { $msg = "Error: " . mysqli_error($conn); }
    } else {
        $sql_insert = "INSERT INTO pet_owner_profiles (username, name, contact_number, passport, profile_picture, additional_info) VALUES ('$username', '$name', '$contact_number', '$passport', '$profile_picture', '$additional_info')";
        if (mysqli_query($conn, $sql_insert)) { $msg = "Profile created successfully!"; }
        else { $msg = "Error: " . mysqli_error($conn); }
    }

    // Refresh profile data
    $result = mysqli_query($conn, $sql);
    $profile = mysqli_fetch_assoc($result);
}
?>
//

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pet Owner Profile - Care4Purrt</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.4/lottie.min.js"></script>

<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background: linear-gradient(135deg, #FFDEE9, #B5FFFC);
    min-height: 100vh;
    transition: 0.4s;
    color: #333;
}
body.dark-mode { background: linear-gradient(135deg, #1f1c2c, #3a2c5f); color: #fff; }

.container-card {
    max-width: 1000px;
    margin: 50px auto;
    background: rgba(255,255,255,0.9);
    padding: 50px;
    border-radius: 25px;
    box-shadow: 0 15px 50px rgba(0,0,0,0.25);
    backdrop-filter: blur(15px);
    animation: fadeIn 0.7s ease;
}
body.dark-mode .container-card { background: rgba(35,35,50,0.85); }

.profile-card {
    padding: 40px;
    border-radius: 25px;
    background: rgba(255,255,255,0.95);
    box-shadow: 0 12px 40px rgba(0,0,0,0.2);
    text-align: center;
    margin-bottom: 25px;
    position: relative;
}
body.dark-mode .profile-card { background: rgba(50,50,65,0.9); }

.profile-pic {
    border-radius: 50%;
    width: 180px;
    height: 180px;
    object-fit: cover;
    border: 4px solid #FFB199;
    margin-bottom: 20px;
}

.profile-card p { font-size:1.2rem; margin: 12px 0; }

.btn-modern {
    padding: 16px 30px;
    border-radius: 35px;
    font-size: 1.25rem;
    font-weight: 600;
    background: linear-gradient(135deg, #FF6F91, #FF9671);
    color: white;
    border: none;
    margin: 6px;
    transition: 0.3s;
}
.btn-modern:hover { transform: scale(1.05); box-shadow: 0 10px 30px rgba(0,0,0,0.25); }

.btn-back {
    background: linear-gradient(135deg, #6A82FB, #FC5C7D);
}

#lottie-cat { width: 150px; height: 150px; margin: 0 auto 20px; }

.theme-btn {
    position: fixed; right: 20px; bottom: 20px;
    padding: 14px 20px;
    border-radius: 30px;
    font-size: 1.2rem;
    cursor: pointer;
    background: #FF6F91;
    color: white;
    border: none;
    transition: 0.3s;
}
.theme-btn:hover { transform: scale(1.1); }

@keyframes fadeIn { from { opacity:0; transform: translateY(20px); } to { opacity:1; transform: translateY(0); } }
</style>
</head>
<body>

<div class="container-card">
    <?php if($profile): ?>
    <div class="profile-card">
        <div id="lottie-cat"></div>
        <img src="<?= $profile['profile_picture'] ?: 'https://via.placeholder.com/180' ?>" alt="Profile Picture" class="profile-pic">
        <p><strong>Name:</strong> <?= htmlspecialchars($profile['name']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($profile['contact_number']) ?></p>
        <p><strong>Passport:</strong> <?= $profile['passport'] ?: 'Not Provided' ?></p>
        <p><strong>Additional Info:</strong> <?= $profile['additional_info'] ?: 'No additional information.' ?></p>
        <a href="#update-form" class="btn btn-modern"><i class="fa-solid fa-pen"></i> Edit Profile</a>
        <a href="pet_owner_dashboard.php" class="btn btn-back btn-modern"><i class="fa-solid fa-arrow-left"></i> Dashboard</a>
    </div>
    <?php endif; ?>

    <h3 id="update-form"><?= $profile ? 'Update Your Profile' : 'Create Your Profile' ?></h3>
    <?php if($msg != ""): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="<?= $profile['name'] ?? '' ?>" required>
        </div>
        <div class="form-group">
            <label>Contact Number:</label>
            <input type="text" name="contact_number" class="form-control" value="<?= $profile['contact_number'] ?? '' ?>" required>
        </div>
        <div class="form-group">
            <label>Passport (Optional):</label>
            <input type="text" name="passport" class="form-control" value="<?= $profile['passport'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label>Profile Picture (Optional):</label>
            <input type="file" name="profile_picture" class="form-control">
        </div>
        <div class="form-group">
            <label>Additional Info:</label>
            <textarea name="additional_info" class="form-control" rows="4"><?= $profile['additional_info'] ?? '' ?></textarea>
        </div>
        <button type="submit" class="btn btn-modern"><?= $profile ? 'Update Profile' : 'Save Profile' ?></button>
    </form>
</div>

<button class="theme-btn" onclick="toggleTheme()"><i class="fa-solid fa-circle-half-stroke"></i> Theme</button>
