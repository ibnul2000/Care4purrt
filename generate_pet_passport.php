<?php
session_start();
include('db.php');

// ---- Access control (admin only) ----
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ---------- Helpers ----------
function ensure_dir($dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

function is_image_upload_ok($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return [false, "No file or upload error."];
    if ($file['size'] > 5 * 1024 * 1024) return [false, "File too large (max 5MB)."];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp'];
    if (!isset($allowed[$mime])) return [false, "Invalid image type. Use JPG/PNG/GIF/WEBP."];
    return [true, $allowed[$mime]];
}

function save_image($file, $destBase) {
    [$ok, $extOrMsg] = is_image_upload_ok($file);
    if (!$ok) return [false, $extOrMsg, null];

    ensure_dir($destBase);
    $ext = $extOrMsg;
    $safeName = uniqid('', true) . '.' . $ext;
    $relative = rtrim($destBase, '/').'/'.$safeName;
    $absolute = __DIR__ . '/' . $relative;

    if (!move_uploaded_file($file['tmp_name'], $absolute)) {
        return [false, "Failed to move uploaded file.", null];
    }
    return [true, "ok", $relative];
}

function generate_unique_passport_no(mysqli $conn) {
    do {
        $candidate = 'PP-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $stmt = $conn->prepare("SELECT 1 FROM pet_passports WHERE passport_number = ? LIMIT 1");
        $stmt->bind_param('s', $candidate);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_row();
        $stmt->close();
    } while ($exists);
    return $candidate;
}

// ---------- Handle POST ----------
$flash = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['generate_passport'])) {
    $pet_id = (int)($_POST['pet_id'] ?? 0);
    $vaccination_status = $_POST['vaccination_status'] ?? '';
    $vaccination_date = $_POST['vaccination_date'] ?? '';

    if ($pet_id <= 0) {
        $flash = ["danger", "Please select a pet."];
    } elseif (!in_array($vaccination_status, ['vaccinated','not vaccinated'], true)) {
        $flash = ["danger", "Invalid vaccination status."];
    } else {
        // Get the selected pet's data
        $stmt = $conn->prepare("SELECT name, COALESCE(pet_age, age) AS age, owner_name, pet_picture FROM pets WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $pet_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $petRow = $res->fetch_assoc();
        $stmt->close();

        if (!$petRow) {
            $flash = ["danger", "Selected pet not found."];
        } else {
            // Uploads
            $pet_picture_path = "";       
            $passport_picture_path = "";  

            if (isset($_FILES['pet_picture']) && $_FILES['pet_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
                [$ok, $msg, $path] = save_image($_FILES['pet_picture'], 'uploads/pets');
                if (!$ok) $flash = ["danger", "Pet picture: " . $msg];
                else $pet_picture_path = $path;
            }

            if (!$flash && isset($_FILES['passport_picture']) && $_FILES['passport_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
                [$ok, $msg, $path] = save_image($_FILES['passport_picture'], 'uploads/passports');
                if (!$ok) $flash = ["danger", "Passport picture: " . $msg];
                else $passport_picture_path = $path;
            }

            if (!$pet_picture_path && !empty($petRow['pet_picture'])) {
                $pet_picture_path = $petRow['pet_picture'];
            }

            if (!$flash) {
                // Check if passport already exists
                $stmtCheck = $conn->prepare("SELECT id, passport_number FROM pet_passports WHERE pet_id = ? LIMIT 1");
                $stmtCheck->bind_param("i", $pet_id);
                $stmtCheck->execute();
                $resCheck = $stmtCheck->get_result();
                $existingPassport = $resCheck->fetch_assoc();
                $stmtCheck->close();

                if ($existingPassport) {
                    // Update existing passport
                    $sql = "UPDATE pet_passports SET 
                                vaccination_status=?, 
                                vaccination_date=?, 
                                pet_picture=?, 
                                passport_picture=? 
                            WHERE pet_id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param(
                        "ssssi",
                        $vaccination_status,
                        $vaccination_date,
                        $pet_picture_path,
                        $passport_picture_path,
                        $pet_id
                    );
                    if ($stmt->execute()) {
                        $flash = ["success", "Pet Passport updated successfully! The owner will receive the updated passport through their portal."];
                    } else {
                        $flash = ["danger", "DB Error: " . $stmt->error];
                    }
                    $stmt->close();
                } else {
                    // Insert new passport
                    $passport_number = generate_unique_passport_no($conn);
                    $sql = "INSERT INTO pet_passports 
                            (pet_id, name, passport_number, vaccination_status, vaccination_date, pet_picture, passport_picture)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $pet_name = $petRow['name'] ?? '';
                    $stmt->bind_param(
                        "issssss",
                        $pet_id,
                        $pet_name,
                        $passport_number,
                        $vaccination_status,
                        $vaccination_date,
                        $pet_picture_path,
                        $passport_picture_path
                    );
                    if ($stmt->execute()) {
                        $flash = ["success", "Pet Passport generated successfully! The owner will receive it through their portal."];
                    } else {
                        $flash = ["danger", "DB Error: " . $stmt->error];
                    }
                    $stmt->close();
                }
            }
        }
    }
}

// ---------- Fetch pets ----------
$queryWithJoin = "
    SELECT 
        p.id, 
        p.name, 
        COALESCE(p.pet_age, p.age) AS age,
        p.owner_name,
        p.pet_picture,
        op.picture AS owner_picture
    FROM pets p
    LEFT JOIN owner_profiles op ON op.full_name = p.owner_name
    ORDER BY p.name ASC
";
$result_pets = mysqli_query($conn, $queryWithJoin);
if (!$result_pets && mysqli_errno($conn) == 1146) {
    $queryNoJoin = "
        SELECT p.id, p.name, COALESCE(p.pet_age, p.age) AS age, p.owner_name, p.pet_picture, NULL AS owner_picture
        FROM pets p ORDER BY p.name ASC";
    $result_pets = mysqli_query($conn, $queryNoJoin);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Generate Pet Passport - Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: linear-gradient(120deg,#e6e9f0,#eef1f5); font-family: 'Poppins', sans-serif; }
.preview-card img { max-height: 150px; object-fit: cover; border-radius:10px; }
.btn-custom { background: linear-gradient(135deg,#7f7fd5,#86a8e7,#91eae4); color:#fff; border-radius:25px; }
</style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Generate Pet Passport</h2>

    <?php if ($flash): ?>
        <div class="alert alert-<?php echo htmlspecialchars($flash[0]); ?> mt-3"><?php echo htmlspecialchars($flash[1]); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="pet_id" class="form-label">Select Pet:</label>
            <select name="pet_id" id="pet_id" class="form-select" required>
                <option value="" disabled selected>Select a Pet</option>
                <?php if ($result_pets && mysqli_num_rows($result_pets) > 0):
                    while ($row = mysqli_fetch_assoc($result_pets)):
                        $optLabel = ($row['name'] ?? 'Unnamed') . " (Age: " . (int)($row['age'] ?? 0) . ")";
                ?>
                    <option 
                        value="<?php echo (int)$row['id']; ?>"
                        data-pet-name="<?php echo htmlspecialchars($row['name']); ?>"
                        data-pet-age="<?php echo (int)$row['age']; ?>"
                        data-owner-name="<?php echo htmlspecialchars($row['owner_name']); ?>"
                        data-owner-pic="<?php echo htmlspecialchars($row['owner_picture']); ?>"
                        data-pet-pic="<?php echo htmlspecialchars($row['pet_picture']); ?>"
                    ><?php echo htmlspecialchars($optLabel); ?></option>
                <?php endwhile; endif; ?>
            </select>
        </div>

        <div class="row preview-card d-none mb-3" id="petPreview">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Pet Preview</h5>
                    <img id="petImg" src="" alt="Pet">
                    <p><strong>Name:</strong> <span id="petName">—</span></p>
                    <p><strong>Age:</strong> <span id="petAge">—</span></p>
                    <p><strong>Owner:</strong> <span id="ownerName">—</span></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Owner Profile</h5>
                    <img id="ownerImg" src="" alt="Owner" style="max-height:150px; border-radius:10px;">
                    <p class="text-muted">Owner picture shown if available.</p>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="vaccination_status" class="form-label">Vaccination Status:</label>
            <select name="vaccination_status" id="vaccination_status" class="form-select" required>
                <option value="vaccinated">Vaccinated</option>
                <option value="not vaccinated">Not Vaccinated</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="vaccination_date" class="form-label">Vaccination Date:</label>
            <input type="date" name="vaccination_date" id="vaccination_date" class="form-control" required>
        </div>

        <button type="submit" name="generate_passport" class="btn btn-custom btn-lg">Generate Passport</button>
        <a href="admin_dashboard.php" class="btn btn-secondary btn-lg ms-2">Go Back</a>
    </form>
</div>

<script>
const sel = document.getElementById('pet_id');
const preview = document.getElementById('petPreview');
const petImg = document.getElementById('petImg');
const ownerImg = document.getElementById('ownerImg');
const petName = document.getElementById('petName');
const petAge = document.getElementById('petAge');
const ownerName = document.getElementById('ownerName');

sel.addEventListener('change', function() {
    const opt = sel.options[sel.selectedIndex];
    if (!opt || !opt.value) { preview.classList.add('d-none'); return; }

    petName.textContent = opt.getAttribute('data-pet-name') || '—';
    petAge.textContent = opt.getAttribute('data-pet-age') || '—';
    ownerName.textContent = opt.getAttribute('data-owner-name') || '—';

    const pPic = opt.getAttribute('data-pet-pic') || '';
    const oPic = opt.getAttribute('data-owner-pic') || '';

    petImg.src = pPic ? pPic : '';
    petImg.style.display = pPic ? 'block' : 'none';
    ownerImg.src = oPic ? oPic : '';
    ownerImg.style.display = oPic ? 'block' : 'none';

    preview.classList.remove('d-none');
});
</script>
</body>
</html>
