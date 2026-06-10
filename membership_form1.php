<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$database = "fitnessguide";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

$session_email = mysqli_real_escape_string($conn, $_SESSION['email']);

$query = "SELECT id, username FROM users WHERE email = '$session_email'";
$result = mysqli_query($conn, $query);

$display_name = "User";
$user_id = 0;

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $user_id = $user_data['id'];
    $full_name = htmlspecialchars($user_data['username']);
    $name_parts = explode(' ', trim($user_data['username']));
    $display_name = htmlspecialchars($name_parts[0]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['phone']) && isset($_POST['plan'])) {
    if ($user_id == 0) {
        die("Unauthorized user session.");
    }

$phone_no = mysqli_real_escape_string($conn, $_POST['phone']);
    $selected_plan = intval($_POST['plan']);
    $created_at = date("Y-m-d H:i:s");
    $insert_query = "INSERT INTO membership_info (user_id, phone_no, selected_plan, created_at) 
                     VALUES ('$user_id', '$phone_no', '$selected_plan', '$created_at')
                     ON DUPLICATE KEY UPDATE 
                     phone_no = '$phone_no', 
                     selected_plan = '$selected_plan', 
                     created_at = '$created_at'";
    
    if (mysqli_query($conn, $insert_query)) {
        mysqli_close($conn);
        header("Location: success.php");
        exit();
    } else {
        echo "Database Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="responsive-overrides.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/x-icon" href="favicon.png">
	<link rel="stylesheet" href="Style_4.css">
	<link rel="stylesheet" href="reveal-animations.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header class="hero">
    <link rel="stylesheet" href="responsive-overrides.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<nav class="navbar">
		<div class="logo">
			<img src="Logo-v2.png" alt="logo">
		</div>

		<ul class="navigationbar">
			<li><a href = "index_AfterLogin.php">Home</a></li>
			<li><a href = "programs-Afterlogin.php">Programs</a></li>

			<li class="has-dropdown">
				Products <i class="fa fa-chevron-down"></i>
				<div class="dropdown-menu">
					<a href="bmi-calculator-updated-afterlogin.php" class="dropdown-item">
						<div>
							<p class="dropdown-title">BMI Calculator</p>
						</div>
					</a>
				</div>
			</li>
			<li class="active">Membership</li>
			<li><a href = "about-AfterLogin.php">About Us</a></li>
		</ul>

			<div class="user-dropdown-container">
   				 <input type="checkbox" id="profile-toggle" class="dropdown-state-checkbox">
    
    			<label for="profile-toggle" class="nav-avatar-trigger">
       			 <img src="loginavatar.png" alt="User Profile" class="nav-avatar-img">
        		<i class="fa fa-chevron-down chevron-icon"></i>
    			</label>
    
    			<div class="profile-dropdown-menu">
        		<div class="menu-user-header">
            	<div class="avatar-status-wrapper">
                <img src="loginavatar.png" alt="User Profile" class="menu-avatar-large">
            	</div>
            	<h3 class="menu-full-name"><?php echo $full_name; ?></h3>
        		</div>
        
        <div class="menu-divider"></div>
        
        <div class="menu-actions">
            <a href="index.html" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>
	</nav>
	<div class="booking-container reveal">
    <div class="booking-left">
        <h2 class="booking-title">Fill the required details to book your slot:</h2>
        <p class="booking-subtitle">Please fill all details, so we can notify you for further process.</p>
        
        <form class="membership-form" action="membership_form1.php" method="POST">
            <div class="input-group">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName"required>
            </div>
            
            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" required>
            </div>
            
            <div class="input-group">
				<label for="phone">Phone Number</label>
				<input type="tel" name="phone" id="phone" required>
				<input type="hidden" name="plan" id="selectedPlanInput" value="1">
            </div>
            
            <button type="submit" class="submit-booking-btn">Confirm & Book Slot</button>
        </form>
    </div>

    <div class="booking-right">
        <div class="selected-plan-card active-plan">
            <h3 class="plan-title">Basic Package</h3>
            <div class="plan-price">Rs 5000<span>/month</span></div>
            
            <ul class="plan-features">
                <li><i class="fa fa-check"></i> Access to Cardio Section</li>
                <li><i class="fa fa-check"></i> Access to General Training Programs</li>
                <li><i class="fa fa-check"></i> Access to Free Weights Section</li>
                <li><i class="fa fa-check"></i> Access to Cross Fit Area</li>
                <li><i class="fa fa-check"></i> Once in 3 weeks Consultation</li>
            </ul>
        </div>
    </div>
</div>
</header>

<footer class="footer">
    <div class="footer-inner">
        <div class="footer-col">
            <img src="Logo-v2.png" alt="ZF Logo" class="footer-logo-img">
            <p class="footer-tagline">Your Fitness Journey Starts Here. We help you build strength, confidence, and a healthier life.</p>
            <div class="vip-social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h4 class="footer-col-title">Quick Links</h4>
            <ul class="footer-links">
                <li><a href="membership.html">Membership</a></li>
                <li><a href="programs.html">Programs</a></li>
                <li><a href="bmi-calculator-updated.html">BMI Calculator</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4 class="footer-col-title">Services</h4>
            <ul class="footer-links">
                <li><a href="about.html">About Us</a></li>
                <li><a href="contact.html">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4 class="footer-col-title">Newsletter</h4>
            <p class="footer-tagline" style="margin-bottom: 0;">Subscribe for fitness tips and exclusive offers.</p>
            <form class="vip-newsletter-form">
                <input type="email" placeholder="Your Email Address" required>
                <button type="submit"><i class="fa fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
    <div class="footer-copy">
        <p>Â© 2026 ZeroFitness. All rights reserved.</p>
        <p style="margin: 0;">Designed for Excellence</p>
    </div>
</footer>

<script src="zf-animations.js"></script>
    <script src="mobile-nav.js"></script>
</body>
</html>



