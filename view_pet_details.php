<?php
session_start();
include('db.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch pet details from the database
$sql = "SELECT * FROM pets";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Pet Details - Admin</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f0eefc;
        padding: 20px;
    }
    .container {
        max-width: 1100px;
        margin-top: 40px;
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    h2 {
        font-weight: 600;
        color: #4b4b8f;
        margin-bottom: 20px;
    }
    .btn-dashboard {
        background: #6c63ff;
        color: white;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 500;
        transition: 0.3s;
        margin-bottom: 20px;
    }
    .btn-dashboard:hover {
        background: #574fcf;
        transform: scale(1.05);
    }
    .btn-passport {
        background: #00bcd4;
        color: white;
        border-radius: 50px;
        padding: 6px 18px;
        font-weight: 500;
        transition: 0.3s;
    }
    .btn-passport:hover {
        background: #0097a7;
        transform: scale(1.05);
    }
    table thead {
        background: #e3e0ff;
        font-weight: 600;
    }
    table img {
        border-radius: 10px;
        object-fit: cover;
    }
    @media(max-width: 768px) {
        table img {
            width: 70px;
        }
        .btn-passport {
            padding: 4px 12px;
            font-size: 0.9rem;
        }
    }
</style>
</head>
<body>
<div class="container">
    <h2>Pet Details</h2>
    <p>Welcome, Admin <strong><?php echo htmlspecialchars($username); ?></strong></p>

    <a href="admin_dashboard.php" class="btn btn-dashboard">Go to Dashboard</a>

    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Pet Name</th>
                <th>Pet Age</th>
                <th>Owner Name</th>
                <th>Vaccine Status</th>
                <th>Pet Picture</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['pet_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['pet_age']); ?></td>
                        <td><?php echo htmlspecialchars($row['owner_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['vaccine_status']); ?></td>
                        <td>
                            <?php if($row['pet_picture']): ?>
                                <img src="<?php echo htmlspecialchars($row['pet_picture']); ?>" alt="Pet Picture" width="100">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="generate_pet_passport.php?pet_id=<?php echo $row['id']; ?>" class="btn btn-passport">Generate Passport</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No pets found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
