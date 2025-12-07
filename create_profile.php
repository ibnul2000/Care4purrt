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

