<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Care4Purrt - Your Pet Care Provider</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; margin:0; padding:0; }
.navbar { background-color: #343a40; }
.navbar-brand { font-size: 2rem; color: #ffcc00 !important; display:flex; align-items:center; }
.navbar-brand img { height: 50px; margin-right: 10px; }

/* Hero Section */
.hero {
    position: relative;
    background: url('https://i.pinimg.com/1200x/db/47/3b/db473b3c49f5aa9a196612c48481aa1f.jpg') no-repeat center center fixed;
    background-size: cover;
    height: 80vh; min-height:400px;
    display:flex; align-items:center; justify-content:center;
    text-align:center; overflow:hidden;
    margin:20px 0;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
}
.hero::before {
    content:'';
    position:absolute; top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.35);
    z-index:1;
}
.hero .container { position:relative; z-index:2; }
.hero h1 { font-size:4.5rem; font-weight:bold; color:#fff; text-shadow:3px 3px 12px rgba(0,0,0,0.8); margin-bottom:20px; }
.hero p { font-size:1.8rem; color:#fff; text-shadow:2px 2px 10px rgba(0,0,0,0.7); margin-bottom:30px; }
.hero .btn-custom { font-size:1.3rem; padding:12px 40px; margin:10px; border-radius:25px; transition: transform 0.3s, box-shadow 0.3s; }
.hero .btn-custom:hover { transform:translateY(-3px); box-shadow:0 10px 20px rgba(0,0,0,0.4); }

/* Sections */
.about-us, .services, .faq, .help, .cta { padding:60px 0; }
.about-us h2, .faq h2, .help h2, .services h3, .cta h3 { text-align:center; margin-bottom:30px; font-weight:bold; }
.about-us p, .faq p, .help p { text-align:center; max-width:900px; margin:auto; }
.services .service-box { padding:30px; background-color:#fdfdfd; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.1); text-align:center; transition: transform 0.3s, box-shadow 0.3s;}
.services .service-box:hover { transform:translateY(-10px); box-shadow:0 10px 25px rgba(0,0,0,0.2);}
.services .service-box i { font-size:3rem; color:#ffcc00; margin-bottom:15px; }
.services .service-box h4 { font-size:1.5rem; margin-bottom:10px; }
.services .service-box p { font-size:1rem; }
.faq .accordion-button { background-color:#343a40; color:white; font-weight:bold;}
.faq .accordion-button:not(.collapsed) { color:#ffcc00; background-color:#495057;}
.help .card { margin-bottom:20px; }
.cta { background-color:#343a40; color:white; text-align:center; padding:60px 0;}
.cta .btn-custom { font-size:1.3rem; padding:12px 35px; border-radius:25px;}
.whatsapp-button { position:fixed; bottom:25px; right:25px; z-index:999;}
.whatsapp-button a { background-color:#25D366; color:white; padding:15px 20px; border-radius:50px; font-size:1.5rem; box-shadow:2px 2px 10px rgba(0,0,0,0.3); display:flex; align-items:center; text-decoration:none;}
.whatsapp-button a:hover { background-color:#1ebe57; }
.footer { background-color:#343a40; color:white; text-align:center; padding:40px 0; margin-top:40px; }
.footer a { color:#ffcc00; text-decoration:none; }
.footer a:hover { text-decoration:underline; }

@media(max-width:768px){ 
    .hero h1{ font-size:3rem;} 
    .hero p{ font-size:1.5rem;} 
    .services .service-box{padding:20px;} 
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="landing.php">
            <img src="https://i.pinimg.com/1200x/49/25/52/492552485a4bb59d0a1f3cf0e8470ac1.jpg" alt="Logo"> 
            Care4Purrt
        </a>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Welcome to Care4Purrt</h1>
        <p>Your Trusted Pet Care Provider</p>
        <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
            <?php if(!isset($_SESSION['username'])): ?>
                <a href="login.php" class="btn btn-primary btn-custom">Login</a>
                <a href="register.php" class="btn btn-success btn-custom">Sign Up</a>
            <?php else: ?>
                <a href="pet_owner_dashboard.php" class="btn btn-warning btn-custom">Go to Dashboard</a>
                <a href="logout.php" class="btn btn-danger btn-custom">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- WhatsApp Floating Button -->
<div class="whatsapp-button">
    <a href="https://wa.me/8801308654774" target="_blank"><i class="fab fa-whatsapp"></i>&nbsp; Chat Now</a>
</div>

<!-- About Us -->
<section class="about-us">
    <div class="container">
        <h2>About Us</h2>
        <p>At Care4Purrt, we provide top-notch healthcare for your pets, ensuring their well-being and happiness. We aim to offer a seamless experience with online booking, mood tracking, nutrition planning, and digital pet passports.</p>
    </div>
</section>

<!-- Services -->
<section class="services">
    <div class="container">
        <h3>Our Services</h3>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-box">
                    <i class="fas fa-smile-beam"></i>
                    <h4>MoodTrack</h4>
                    <p>Track your pet’s daily mood, activity, and emotional well-being with ease.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-box">
                    <i class="fas fa-apple-alt"></i>
                    <h4>CareTailor</h4>
                    <p>Personalized nutrition and care plans based on your pet’s breed, age, and health needs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-box">
                    <i class="fas fa-passport"></i>
                    <h4>Pet Passport</h4>
                    <p>Generate a digital and printable pet passport with vaccination, health, and travel info.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq">
    <div class="container">
        <h2>Frequently Asked Questions</h2>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                        How do I register my pet?
                    </button>
                </h2>
                <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        You can register your pet by signing up and filling in their details in the "My Pets" section after login.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                        Can I book appointments online?
                    </button>
                </h2>
                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes! Our platform allows you to book appointments with doctors online seamlessly.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                        How do I access my pet's health records?
                    </button>
                </h2>
                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        You can access your pet’s records through the "My Pet Portal" after logging into your account.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Help Section -->
<section class="help">
    <div class="container">
        <h2>Need Help?</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Contact Support</h5>
                    <p>Email us at <a href="mailto:bushrachow28@gmail.com">bushrachow28@gmail.com</a> or call +880 1308654774.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Live Chat</h5>
                    <p>Chat with our support team directly from the WhatsApp button.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h5>FAQs</h5>
                    <p>Check out our FAQ section above for quick answers to common questions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta">
    <div class="container">
        <h3>Ready to take care of your pet?</h3>
        <p>Join Care4Purrt today and give your pet the best care possible.</p>
        <?php if(!isset($_SESSION['username'])): ?>
            <a href="register.php" class="btn btn-success btn-custom">Sign Up Now</a>
        <?php else: ?>
            <a href="pet_owner_dashboard.php" class="btn btn-warning btn-custom">Go to Dashboard</a>
        <?php endif; ?>
    </div>
</section>

<!-- Go Back to Homepage Button -->
<div class="text-center my-4">
    <a href="landing.php" class="btn btn-warning btn-lg">
        <i class="fas fa-home me-2"></i>Back to Top
    </a>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p>&copy; 2025 Care4Purrt. All rights reserved. | <a href="#about">About Us</a> | <a href="#faqAccordion">FAQ</a> | <a href="#help">Help</a></p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
