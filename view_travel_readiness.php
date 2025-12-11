<?php
session_start();
include('db.php');

// Check if the user is logged in and is a pet owner
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch pet passports joined with pet details, only where passport exists
$sql = "
    SELECT pp.*, p.name AS pet_name, COALESCE(p.pet_age, p.age) AS pet_age, p.owner_name, p.pet_picture 
    FROM pet_passports pp
    JOIN pets p ON pp.pet_id = p.id
    WHERE p.owner_name = ?
    ORDER BY pp.id DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PurrPort: Your Pet Passport</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f0f2f5;
        padding: 40px 20px;
    }
    h1 {
        text-align: center;
        color: #4b4b8f;
        margin-bottom: 50px;
    }
    .passport-card {
        background: linear-gradient(to bottom right, #fff, #e0f7fa);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 8px 18px rgba(0,0,0,0.2);
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .passport-header h2 {
        margin: 0;
        font-weight: bold;
        color: #2c3e50;
    }
    .passport-header p {
        font-style: italic;
        color: #555;
        margin: 5px 0 20px 0;
    }
    .passport-picture img {
        width: 250px;
        height: 250px;
        object-fit: cover;
        border-radius: 15px;
        border: 3px solid #2c3e50;
        display: block;
        margin: 0 auto 20px auto;
    }
    .passport-details p {
        font-size: 18px;
        line-height: 1.6;
        color: #2c3e50;
    }
    .passport-details strong {
        color: #34495e;
    }
    .btn-pdf {
        background: #6c63ff;
        color: #fff;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        transition: 0.3s;
        display: block;
        margin: 20px auto 0 auto;
    }
    .btn-pdf:hover {
        background: #574fcf;
        transform: scale(1.05);
    }
    .text-muted-center {
        text-align: center;
        color: #6c757d;
    }
</style>
</head>
<body>

<h1>🐾 PurrPort: Your Pet Passport 🐾</h1>

<div class="container">
<?php if ($result->num_rows > 0) { 
    while ($row = $result->fetch_assoc()) { 
        $petPic = !empty($row['pet_picture']) ? $row['pet_picture'] : 'placeholder_pet.png'; // fallback placeholder
?>
    <div class="passport-card" id="passport-<?php echo $row['id']; ?>">
        <div class="passport-header text-center">
            <h2>Pet Passport</h2>
            <p>Care4Purrt - Official Travel Document</p>
        </div>
        <div class="passport-picture">
            <img src="<?php echo htmlspecialchars($petPic); ?>" alt="Pet Picture">
        </div>
        <div class="passport-details">
            <p><strong>Pet Name:</strong> <?php echo htmlspecialchars($row['pet_name']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($row['pet_age']); ?> years</p>
            <p><strong>Owner:</strong> <?php echo htmlspecialchars($row['owner_name']); ?></p>
            <p><strong>Passport Number:</strong> <?php echo htmlspecialchars($row['passport_number']); ?></p>
            <p><strong>Vaccination Status:</strong> <?php echo ucfirst($row['vaccination_status']); ?></p>
            <p><strong>Vaccination Date:</strong> <?php echo htmlspecialchars($row['vaccination_date']); ?></p>
        </div>
        <button class="btn-pdf" onclick="downloadPassport('<?php echo $row['id']; ?>')">
            <i class="fas fa-download me-2"></i>Download PDF
        </button>
    </div>
<?php } 
} else { ?>
    <p class="text-muted-center">No passport found for your pets yet. Please check back later. For assistance, <a href='https://wa.me/8801308654774' target='_blank'>contact the admin via WhatsApp</a>.</p>
<?php } ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
async function downloadPassport(id) {
    const { jsPDF } = window.jspdf;
    const element = document.getElementById("passport-" + id);
    const canvas = await html2canvas(element, { scale: 2 });
    const imgData = canvas.toDataURL("image/png");

    const pdf = new jsPDF("p", "pt", "a4");
    const pageWidth = pdf.internal.pageSize.getWidth();
    const imgWidth = pageWidth - 40;
    const imgHeight = canvas.height * imgWidth / canvas.width;

    pdf.addImage(imgData, "PNG", 20, 20, imgWidth, imgHeight);
    pdf.save("pet_passport_<?php echo $username; ?>_" + id + ".pdf");
}
</script>
</body>
</html>
