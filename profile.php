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