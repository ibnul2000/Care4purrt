<?php
session_start();
include('db.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$sql_doctors = "SELECT * FROM doctor_profiles";
$result_doctors = mysqli_query($conn, $sql_doctors);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_username = mysqli_real_escape_string($conn, $_POST['doctor']);
    $pet_type = mysqli_real_escape_string($conn, $_POST['pet_type']);
    $pet_age = mysqli_real_escape_string($conn, $_POST['pet_age']);
    $pet_problem = mysqli_real_escape_string($conn, $_POST['pet_problem']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);

    $sql_appointment = "INSERT INTO appointments (pet_owner_username, doctor_username, pet_type, pet_age, pet_problem, appointment_date, appointment_time)
                        VALUES ('$username', '$doctor_username', '$pet_type', '$pet_age', '$pet_problem', '$appointment_date', '$appointment_time')";
    
    if (mysqli_query($conn, $sql_appointment)) {
        $success = true;
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Appointment</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
/* Body & background */
body {
    background: linear-gradient(135deg, #FFDEE9, #B5FFFC);
    font-family: 'Poppins', sans-serif;
    margin:0;
    padding:0;
}

/* Container */
.container-box {
    max-width: 900px;
    margin: 60px auto;
    background: rgba(255,255,255,0.98);
    border-radius: 30px;
    padding: 60px 50px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.25);
}

/* Step Header / Card Style */
.step-card {
    background: linear-gradient(145deg, #ffffff, #f0f0f0);
    border-radius: 25px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.4s, box-shadow 0.4s;
}
.step-card.active {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

/* Step Progress */
.progress-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 50px;
}
.step-circle {
    width: 60px;
    height: 60px;
    background: #ddd;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    font-size: 1.5rem;
    transition: 0.3s;
}
.step-circle.active {
    background: #FF6F91;
    color: white;
}

/* Input style */
.input-icon {
    position: relative;
    margin-bottom: 25px;
}
.input-icon i {
    position: absolute;
    top: 16px;
    left: 16px;
    font-size: 1.3rem;
    color: #777;
}
.input-icon input,
.input-icon select,
.input-icon textarea {
    padding-left: 55px;
    font-size: 1.25rem;
    height: 55px;
    border-radius: 15px;
    border: 1px solid #ccc;
}
.input-icon textarea { height:120px; }

/* Buttons */
.btn-custom {
    width: 100%;
    padding: 18px;
    font-size: 1.3rem;
    font-weight: 700;
    border-radius: 35px;
    transition: 0.3s;
}
.btn-custom:hover { transform: scale(1.05); box-shadow: 0 10px 30px rgba(0,0,0,0.3); }

.btn-primary.btn-custom { background: linear-gradient(135deg, #FF6F91, #FF9671); border: none; color:white; }
.btn-secondary.btn-custom { background: linear-gradient(135deg, #6A82FB, #FC5C7D); border: none; color:white; }
.btn-success.btn-custom { background: linear-gradient(135deg, #56ab2f, #a8e063); border: none; color:white; }

/* Confirmation Popup */
.popup {
    position: fixed;
    top:50%; left:50%;
    transform: translate(-50%, -50%);
    background:white;
    padding:50px 70px;
    border-radius:30px;
    text-align:center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    display:none;
    z-index:1000;
}
.popup.show { display:block; }

/* Go Back Button */
.btn-home {
    background: linear-gradient(135deg, #FF9671, #FFC75F);
    color:white;
    border-radius:35px;
    padding:16px 35px;
    font-size:1.3rem;
    font-weight:700;
    margin-top:30px;
    display:inline-block;
    text-decoration:none;
    transition:0.3s;
}
.btn-home:hover { transform: scale(1.05); box-shadow: 0 10px 30px rgba(0,0,0,0.25); }

/* Responsive */
@media(max-width:768px){
    .container-box { padding:40px 30px; }
    .step-circle { width:50px; height:50px; font-size:1.3rem; }
    .input-icon input, .input-icon select, .input-icon textarea { font-size:1.1rem; height:50px; }
    .input-icon textarea { height:100px; }
    .btn-custom, .btn-home { font-size:1.15rem; padding:14px; }
}
</style>
</head>
<body>

<div class="container-box">
    <h1 class="text-center mb-5" style="font-size:3rem;">Book Your Appointment</h1>

    <!-- Progress -->
    <div class="progress-container">
        <div class="step-circle step1 active">1</div>
        <div class="step-circle step2">2</div>
        <div class="step-circle step3">3</div>
    </div>

    <form method="POST" action="">
        <!-- Step 1 -->
        <div class="step step-1 step-card active">
            <h2 class="mb-4" style="font-size:2rem;">Choose a Doctor</h2>
            <div class="input-icon">
                <i class="fa fa-user-md"></i>
                <select name="doctor" class="form-control" required>
                    <?php while ($doctor = mysqli_fetch_assoc($result_doctors)) { ?>
                        <option value="<?= $doctor['username'] ?>">
                            <?= $doctor['name'] ?> - <?= $doctor['specialization'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" width="180">
            </div>
            <button type="button" class="btn btn-primary btn-custom nextBtn">Next</button>
        </div>

        <!-- Step 2 -->
        <div class="step step-2 step-card">
            <h2 class="mb-4" style="font-size:2rem;">Pet Information</h2>
            <div class="input-icon">
                <i class="fa fa-paw"></i>
                <input type="text" name="pet_type" placeholder="Pet Type" class="form-control" required>
            </div>
            <div class="input-icon">
                <i class="fa fa-bone"></i>
                <input type="number" name="pet_age" placeholder="Pet Age" class="form-control" required>
            </div>
            <div class="input-icon">
                <i class="fa fa-heartbeat"></i>
                <textarea name="pet_problem" placeholder="Pet Problem" class="form-control" required></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary btn-custom prevBtn">Back</button>
                <button type="button" class="btn btn-primary btn-custom nextBtn">Next</button>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="step step-3 step-card">
            <h2 class="mb-4" style="font-size:2rem;">Schedule Appointment</h2>
            <div class="input-icon">
                <i class="fa fa-calendar"></i>
                <input type="date" name="appointment_date" class="form-control" required>
            </div>
            <div class="input-icon">
                <i class="fa fa-clock"></i>
                <input type="time" name="appointment_time" class="form-control" required>
            </div>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary btn-custom prevBtn">Back</button>
                <button type="submit" class="btn btn-success btn-custom">Confirm Booking</button>
            </div>
        </div>
    </form>

    <!-- Back Button -->
    <div class="text-center">
        <a href="pet_owner_dashboard.php" class="btn-home"><i class="fas fa-home me-2"></i>Back to Dashboard</a>
    </div>
</div>

<!-- Confirmation Popup -->
<?php if(isset($success)) { ?>
<div class="popup show">
    <h3 style="font-size:2rem;">🎉 Appointment Booked!</h3>
    <p style="font-size:1.3rem;">Redirecting to your Dashboard...</p>
</div>
<script>
setTimeout(()=>{ window.location.href='pet_owner_dashboard.php'; }, 2500);
</script>
<?php } ?>

<script>
let currentStep = 1;
document.querySelectorAll(".nextBtn").forEach(btn=>{
    btn.addEventListener("click", ()=>{
        if(currentStep >=3) return;
        document.querySelector(".step-"+currentStep).classList.remove("active");
        currentStep++;
        document.querySelector(".step-"+currentStep).classList.add("active");
        document.querySelectorAll(".step-circle").forEach((circle,index)=>{
            circle.classList.toggle("active", index<currentStep);
        });
    });
});
document.querySelectorAll(".prevBtn").forEach(btn=>{
    btn.addEventListener("click", ()=>{
        if(currentStep<=1) return;
        document.querySelector(".step-"+currentStep).classList.remove("active");
        currentStep--;
        document.querySelector(".step-"+currentStep).classList.add("active");
        document.querySelectorAll(".step-circle").forEach((circle,index)=>{
            circle.classList.toggle("active", index<currentStep);
        });
    });
});
</script>

</body>
</html>
