<?php
session_start();
include('db.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch all users
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Users - Admin Panel</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f0f2f5;
        padding: 20px;
    }
    .container {
        max-width: 1000px;
        margin-top: 40px;
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    h2 {
        color: #333;
        margin-bottom: 20px;
        font-weight: 600;
    }
    .btn-dashboard {
        background: #4e73df;
        color: white;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 500;
        transition: 0.3s;
    }
    .btn-dashboard:hover {
        background: #2e59d9;
        transform: scale(1.05);
    }
    .btn-delete {
        background: #e74a3b;
        color: white;
        border-radius: 50px;
        padding: 6px 18px;
        font-weight: 500;
        transition: 0.3s;
    }
    .btn-delete:hover {
        background: #c0392b;
        transform: scale(1.05);
    }
    table th, table td {
        vertical-align: middle !important;
    }
    .table thead {
        background: #f8f9fc;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Registered Users</h2>
    <p>Welcome, Admin <strong><?php echo htmlspecialchars($username); ?></strong></p>

    <a href="admin_dashboard.php" class="btn btn-dashboard mb-3">Go to Dashboard</a>

    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($user = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo ucfirst($user['role']); ?></td>
                        <td>
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
