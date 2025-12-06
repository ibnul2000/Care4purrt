<?php
session_start();
include('db.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $energy_level = mysqli_real_escape_string($conn, $_POST['energy_level']);
    $appetite = mysqli_real_escape_string($conn, $_POST['appetite']);
    $social_interaction = mysqli_real_escape_string($conn, $_POST['social_interaction']);
    $play_behavior = mysqli_real_escape_string($conn, $_POST['play_behavior']);
    $sleep_rest = mysqli_real_escape_string($conn, $_POST['sleep_rest']);
    $vocalization = mysqli_real_escape_string($conn, $_POST['vocalization']);
    $other_info = mysqli_real_escape_string($conn, $_POST['other_info']);

    $sql = "INSERT INTO pet_moods (username, energy_level, appetite, social_interaction, play_behavior, sleep_rest, vocalization, other_info)
            VALUES ('$username', '$energy_level', '$appetite', '$social_interaction', '$play_behavior', '$sleep_rest', '$vocalization', '$other_info')";

    if (mysqli_query($conn, $sql)) {
        $msg = "Pet mood logged successfully!";
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Log Pet Mood - Care4Purrt</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.4/lottie.min.js"></script>

<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    transition: 0.4s;
    background: linear-gradient(135deg, #FFE6E6, #E0FFFA);
    color: #333;
}
body.dark-mode {
    background: linear-gradient(135deg, #1f1c2c, #3a2c5f);
    color: #fff;
}

.sidebar {
    width: 240px;
    height: 100vh;
    position: fixed;
    left: 0; top: 0;
    padding: 25px;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(15px);
    border-right: 1px solid #ddd;
    transition: 0.4s;
    z-index: 100;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.sidebar.dark-mode {
    background: rgba(20,20,35,0.9);
}
.sidebar h3 {
    margin-top: 15px;
    font-weight: 600;
}
.sidebar a {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 100%;
    padding: 12px 15px;
    margin: 8px 0;
    font-size: 1rem;
    border-radius: 12px;
    text-decoration: none;
    color: #333;
    transition: 0.3s;
}
.sidebar.dark-mode a { color: #ddd; }
.sidebar a:hover {
    background: #FF8C94;
    color: white;
    transform: translateX(5px);
}
#lottie-pet { width: 160px; height: 160px; margin-bottom: 15px; }

.content { margin-left: 260px; padding: 40px; }
.container-card {
    background: rgba(255,255,255,0.85);
    padding: 35px;
    border-radius: 25px;
    backdrop-filter: blur(15px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    animation: fadeIn 0.7s ease;
}
body.dark-mode .container-card { background: rgba(35,35,50,0.85); }

.form-group label { font-weight: 600; }
.btn-modern {
    width: 100%;
    padding: 14px;
    border-radius: 30px;
    font-size: 1.2rem;
    font-weight: 600;
    background: linear-gradient(135deg, #FF6F91, #FF9671);
    color: white;
    border: none;
    transition: 0.3s;
}
.btn-modern:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.btn-back {
    background: linear-gradient(135deg, #6A82FB, #FC5C7D);
    margin-top: 12px;
}

.theme-btn {
    position: fixed;
    right: 20px;
    bottom: 20px;
    padding: 14px 20px;
    border-radius: 30px;
    font-size: 1.2rem;
    cursor: pointer;
    background: #FF6F91;
    color: white;
    border: none;
    transition: 0.3s;
}
.theme-btn:hover { transform: scale(1.1); }

@keyframes fadeIn {
    from { opacity:0; transform: translateY(20px); }
    to { opacity:1; transform: translateY(0); }
}
</style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div id="lottie-pet"></div>
    <h3><i class="fa-solid fa-paw"></i> Pet Owner</h3>
    <a href="pet_owner_dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a>
    <a href="log_pet_mood.php"><i class="fa-solid fa-heart-pulse"></i> Log Mood</a>
    <a href="logout.php" style="color:#FF6F91;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<div class="content">
    <h2>🐾 Log Your Pet's Mood</h2>
    <?php if($msg != ""): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <div class="container-card">
        <form method="POST">
            <div class="form-group">
                <label>Energy Level:</label>
                <select name="energy_level" class="form-control" required>
                    <option value="very_low">Very Low 😴</option>
                    <option value="low">Low 💤</option>
                    <option value="normal">Normal 🙂</option>
                    <option value="high">High ⚡</option>
                    <option value="super_hyper">Super Hyper 🐕</option>
                </select>
            </div>
            <div class="form-group">
                <label>Appetite:</label>
                <select name="appetite" class="form-control" required>
                    <option value="didnt_eat">Didn’t eat at all 🚫🍖</option>
                    <option value="ate_less">Ate less than usual 🍗</option>
                    <option value="ate_normally">Ate normally 🍴</option>
                    <option value="overeating">Overate / Begged a lot 😋</option>
                </select>
            </div>
            <div class="form-group">
                <label>Social Interaction:</label>
                <select name="social_interaction" class="form-control" required>
                    <option value="wanted_alone">Wanted to be alone 🙈</option>
                    <option value="cuddly_needy">Cuddly & needy 🤗</option>
                    <option value="playful">Playful 🐾</option>
                    <option value="overly_clingy">Overly clingy 😅</option>
                </select>
            </div>
            <div class="form-group">
                <label>Play Behavior:</label>
                <select name="play_behavior" class="form-control" required>
                    <option value="ignored_toys">Ignored toys 🧸🚫</option>
                    <option value="played_little">Played a little 🐕</option>
                    <option value="played_normally">Played normally 🎾</option>
                    <option value="couldnt_stop">Couldn’t stop playing 😂</option>
                </select>
            </div>
            <div class="form-group">
                <label>Sleep & Rest:</label>
                <select name="sleep_rest" class="form-control" required>
                    <option value="slept_all_day">Slept almost all day 🛏️</option>
                    <option value="normal_naps">Normal naps 😌</option>
                    <option value="restless">Restless / Pacing 🐾</option>
                    <option value="barely_slept">Barely slept ⚡</option>
                </select>
            </div>
            <div class="form-group">
                <label>Vocalization:</label>
                <select name="vocalization" class="form-control" required>
                    <option value="quiet">Quiet 🤫</option>
                    <option value="normal_sounds">Normal sounds 🎶</option>
                    <option value="extra_talkative">Extra talkative / barking 🗣️</option>
                    <option value="whiny_crying">Whiny / crying 😢</option>
                </select>
            </div>
            <div class="form-group">
                <label>Other Info:</label>
                <textarea name="other_info" class="form-control" rows="4" placeholder="Pet name, preferred doctor, notes..."></textarea>
            </div>

            <button type="submit" class="btn btn-modern">Log Mood</button>
            <a href="pet_owner_dashboard.php" class="btn btn-back btn-modern mt-2"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </form>
    </div>
</div>

<button class="theme-btn" onclick="toggleTheme()"><i class="fa-solid fa-circle-half-stroke"></i> Theme</button>

<script>
lottie.loadAnimation({
    container: document.getElementById('lottie-pet'),
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: 'https://assets7.lottiefiles.com/packages/lf20_3vbOcw.json' // Playful animated cat
});

function toggleTheme(){
    document.body.classList.toggle('dark-mode');
    document.getElementById('sidebar').classList.toggle('dark-mode');
}
</script>



</body>
</html>
