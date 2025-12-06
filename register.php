<?php
session_start();
include('db.php'); // Include the database connection file

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] == 'owner') header("Location: pet_owner_dashboard.php");
    elseif ($_SESSION['role'] == 'doctor') header("Location: doctor_dashboard.php");
    elseif ($_SESSION['role'] == 'admin') header("Location: admin_dashboard.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = $_POST['role'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql_check = "SELECT * FROM users WHERE username='$username'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        $error_message = "Username already exists!";
    } else {
        $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            if ($role == 'admin') header("Location: admin_dashboard.php");
            elseif ($role == 'doctor') header("Location: doctor_dashboard.php");
            else header("Location: pet_owner_dashboard.php");
            exit();
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up - Care4Purrt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #c3cfe2, #c3cfe2, #e0c3fc);
}

.container {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    border-radius: 25px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    padding: 50px;
    max-width: 500px;
    width: 100%;
    color: #333;
}

h2 {
    text-align: center;
    margin-bottom: 40px;
    font-weight: 700;
    font-size: 2.2rem;
    color: #4b3f72;
}

.form-control {
    border-radius: 15px;
    border: 1px solid #b3aee0;
    padding: 15px;
    margin-bottom: 25px;
    background: #f8f8ff;
    color: #333;
    font-size: 1.1rem;
}

.form-control:focus {
    outline: none;
    border-color: #7e7bdc;
    box-shadow: 0 0 8px rgba(126, 123, 220, 0.5);
    background: #fff;
}

select.form-control {
    color: #333;
}

.btn-custom {
    width: 100%;
    padding: 15px;
    font-size: 1.3rem;
    font-weight: 600;
    border-radius: 25px;
    border: none;
    background: linear-gradient(90deg, #7e7bdc, #a18cd1);
    color: #fff;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.btn-custom:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
}

.alert {
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
    font-weight: 500;
}

.footer {
    text-align: center;
    margin-top: 25px;
    font-size: 0.95rem;
}

.footer a {
    color: #7e7bdc;
    text-decoration: underline;
    transition: color 0.3s ease;
}

.footer a:hover {
    color: #4b3f72;
}
</style>
</head>
<body>

<div class="container">
    <h2>Create Your Account</h2>

    <?php if (isset($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    } ?>

    <form method="POST" action="register.php">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <select name="role" class="form-control" required>
            <option value="" disabled selected>Select Role</option>
            <option value="owner">Pet Owner</option>
            <option value="doctor">Doctor</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit" class="btn btn-custom">Sign Up</button>
    </form>

    <div class="footer">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

</body>
</html>

