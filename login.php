<?php
session_start();
include('db.php'); // Include your database connection

// Redirect if already logged in
if (isset($_SESSION['username'])) {
    switch ($_SESSION['role']) {
        case 'owner': header("Location: pet_owner_dashboard.php"); exit();
        case 'doctor': header("Location: doctor_dashboard.php"); exit();
        case 'admin': header("Location: admin_dashboard.php"); exit();
    }
}

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // HARD-CODED ADMIN LOGIN
    if ($username === "admin" && $password === "admin123") {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';
        header("Location: admin_dashboard.php");
        exit();
    }

    // Check database for other users
    $stmt = $conn->prepare("SELECT username, password, role FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_username, $db_password, $db_role);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $_SESSION['username'] = $db_username;
            $_SESSION['role'] = $db_role;

            switch ($db_role) {
                case 'owner': header("Location: pet_owner_dashboard.php"); break;
                case 'doctor': header("Location: doctor_dashboard.php"); break;
            }
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "No user found with this username!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Care4Purrt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* General Body */
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #E6E6FA, #F8F0FF); /* Light pastel lavender gradient */
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

/* Card */
.login-card {
    background: #ffffff;
    border-radius: 25px;
    padding: 50px 40px;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.15);
    text-align: center;
    transition: transform 0.3s ease;
}
.login-card:hover {
    transform: translateY(-5px);
}

/* Pet Image */
.pet-image {
    width: 100px;
    margin-bottom: 25px;
}

/* Headings */
.login-card h3 {
    color: #4B6CB7; /* bluish text */
    margin-bottom: 30px;
    font-weight: 600;
}

/* Form Inputs */
.form-control {
    border-radius: 50px;
    padding: 15px 20px;
    font-size: 1rem;
    box-shadow: inset 0 2px 6px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid #ccc;
}
.form-control:focus {
    border-color: #4B6CB7;
    box-shadow: 0 0 10px rgba(75,108,183,0.4);
    outline: none;
}

/* Buttons */
.btn-login {
    background: linear-gradient(135deg, #4B6CB7, #182848); /* bluish gradient */
    border: none;
    color: white;
    font-size: 1.15rem;
    font-weight: 600;
    border-radius: 50px;
    padding: 14px;
    width: 100%;
    transition: all 0.3s ease;
}
.btn-login:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 30px rgba(75,108,183,0.4);
}

/* Alert messages */
.alert {
    border-radius: 15px;
    font-weight: 500;
    margin-top: 15px;
}

/* Signup link */
.text-center a {
    color: #4B6CB7;
    font-weight: 600;
    text-decoration: none;
}
.text-center a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="login-card">
    <!-- Cute Pet Illustration -->
    <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Pet Icon" class="pet-image">

    <h3>Welcome to Care4Purrt</h3>

    <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

    <form method="POST" action="">
        <div class="form-group mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
        </div>

        <div class="form-group mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" class="btn-login mt-3">Login</button>
    </form>

    <p class="mt-4 text-center">Don't have an account? <a href="register.php">Sign Up</a></p>
</div>

</body>
</html>
