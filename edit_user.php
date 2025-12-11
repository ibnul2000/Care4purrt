<?php
session_start();
include('db.php'); // Include database connection file

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect to login if not logged in or wrong role
    exit();
}

// Fetch user details from database for editing
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
    } else {
        die("User not found!");
    }
}

// Handle form submission for editing user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = $_POST['role']; // Get role from dropdown

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update user in the database
    $sql_update = "UPDATE users SET username='$username', password='$hashed_password', role='$role' WHERE id='$user_id'";

    if (mysqli_query($conn, $sql_update)) {
        // Redirect to manage users page after successful update
        header("Location: view_all_users.php");
        exit();
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Care4Purrt</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Admin Sidebar (can be added to include navigation links) -->
    <?php include('admin_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2>Edit User</h2>

        <!-- Display error message if any -->
        <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

        <!-- Edit User Form -->
        <form method="POST" action="edit_user.php?id=<?php echo $user['id']; ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="owner" <?php echo ($user['role'] == 'owner') ? 'selected' : ''; ?>>Pet Owner</option>
                    <option value="doctor" <?php echo ($user['role'] == 'doctor') ? 'selected' : ''; ?>>Doctor</option>
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
