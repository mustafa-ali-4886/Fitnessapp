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

$query = "SELECT username FROM users WHERE email = '$session_email'";
$result = mysqli_query($conn, $query);

$display_name = "User";
if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $full_name = htmlspecialchars($user_data['username']);
    $name_parts = explode(' ', trim($user_data['username']));
    $display_name = htmlspecialchars($name_parts[0]);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="responsive-overrides.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us – Zero Fitness</title>
    <link rel="icon" type="image/x-icon" href="favicon.png">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   
    <link rel="stylesheet" href="Style_2.css">
    <!-- Login-style CSS for the form -->
    <link rel="stylesheet" href="style.css">

    <style>

    body {
        background: #141514;
        color: #FFFFFF;
        min-height: 100vh;
        font-family: "BodyText", sans-serif;
    }

    .navbar {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 100;
        display: flex;
        align-items: center;
        padding: 18px 40px;
        box-sizing: border-box;
        background: linear-gradient(to bottom, rgba(0,0,0,0.7), transparent);
    }
    
    a {
        text-decoration: none;
        color: #CCCCCC;
    }

    .logo {
        flex: 1;
    }
    .logo img {
        height: 30px;
        width: auto;
    }

    .navigationbar {
        display: flex;
        list-style: none;
        gap: 32px;
        margin: 0;
        padding: 0;
        margin-left: auto; 
        margin-right: 50px; 
    }
    .navigationbar li {
        font-family: "BodyText", sans-serif;
        font-size: 13px;
        color: #cccccc;
        cursor: pointer;
        transition: color 0.3s ease;
        position: relative;
    }
    .navigationbar li:hover {
        color: #FFFFFF;
    }
    .navigationbar li.active {
        color: #57E201;
        font-weight: bold;
    }

    .has-dropdown {
        position: relative;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        background-color: #1e1f1e;
        border: 1px solid #2e2f2e;
        border-radius: 8px;
        padding: 10px;
        min-width: 210px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        z-index: 200;
    }

    .has-dropdown:hover .dropdown-menu {
        display: block;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 8px;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }
    .dropdown-item:hover {
        background-color: #2a2b2a;
    }

    .dropdown-title {
        font-family: "BodyText", sans-serif;
        font-size: 13px;
        font-weight: normal;
        color: white;
        margin: 0 0 2px 0;
    }

    .SignUp {
        background-color: #4EC901;
        font-family: "BodyText", sans-serif;
        font-size: 12px;
        font-weight: bold;
        border-radius: 6px;
        border: none;
        padding: 8px 18px;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
        white-space: nowrap;
        margin-left: auto;
        display: inline-block;
        text-align: center;
        text-decoration: none;
        color: #111; 
    }

    .SignUp:hover {
        background-color: #128700;
        color: #FFFFFF; 
    }

    /* ── MAIN CONTENT SPACING ── */
    .contact-page {
        padding: 140px 50px 90px;
        max-width: 1300px;
        margin: 0 auto;
    }


    .contact-page-header {
        margin-bottom: 55px;
    }
    .contact-page-header .section-label {
        margin-bottom: 10px;
    }
    .contact-page-title {
        font-family: "TitleText", sans-serif;
        font-size: clamp(36px, 5vw, 58px);
        font-weight: 800;
        color: #FFFFFF;
        line-height: 1.1;
        margin: 0;
    }
    .contact-page-title span {
        color: #57E201;
    }
    .contact-page-subtitle {
        font-family: "BodyText", sans-serif;
        font-size: 15px;
        color: #CFDEDA;
        margin-top: 16px;
        max-width: 520px;
        line-height: 1.7;
    }

    .contact-grid {
        display: flex;
        gap: 60px;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .contact-info-panel {
        flex: 1;
        min-width: 260px;
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    .info-card {
        background: #1B1C1B;
        border: 1px solid #2B2C2B;
        border-radius: 16px;
        padding: 24px 26px;
        display: flex;
        align-items: flex-start;
        gap: 18px;
        transition: border-color 0.3s ease, transform 0.3s ease;
    }
    .info-card:hover {
        border-color: #57E201;
        transform: translateY(-3px);
    }
    .info-card-icon {
        width: 42px;
        height: 42px;
        background: rgba(87, 226, 1, 0.12);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .info-card-icon i {
        color: #57E201;
        font-size: 16px;
    }
    .info-card-text .info-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 2px;
        color: #57E201;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .info-card-text .info-value {
        font-size: 14px;
        color: #FFFFFF;
        font-weight: 500;
        display: block;
    }
    .info-card-text .info-sub {
        font-size: 11px;
        color: #6b7a6b;
        margin-top: 3px;
    }

    /* Live Chat button */
    .btn-live-chat {
        background: #57E201;
        color: #141514;
        font-family: "BodyText", sans-serif;
        font-size: 13px;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        padding: 14px 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background-color 0.3s ease, transform 0.2s ease;
        width: 100%;
        justify-content: center;
    }
    .btn-live-chat:hover {
        background: #128700;
        color: #FFFFFF;
        transform: translateY(-2px);
    }

    /* ── RIGHT: FORM PANEL ── */
    .contact-form-panel {
        flex: 1.6;
        min-width: 320px;
    }

    /* Reuse login-page card aesthetic */
    .contact-form-card {
        background: #141514;
        padding: 0;
    }

    /* Form rows */
    .form-row-two {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    .form-row-two .input-group {
        flex: 1;
        min-width: 140px;
    }

    .input-group {
        margin-bottom: 22px;
    }
    .input-group label {
        display: block;
        margin-bottom: 10px;
        color: #CFDEDA;
        font-size: 14px;
        letter-spacing: 1px;
    }

    /* Reuse login input-box style */
    .input-box {
        width: 100%;
        background: #1B1C1B;
        border: 1px solid #2B2C2B;
        border-radius: 15px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .input-box:focus-within {
        border-color: #57E201;
        box-shadow: 0 0 0 3px rgba(87,226,1,0.09);
    }
    .input-box i {
        color: #57E201;
        font-size: 15px;
        flex-shrink: 0;
    }
    .input-box input,
    .input-box textarea {
        width: 100%;
        background: none;
        border: none;
        outline: none;
        color: #FFFFFF;
        font-size: 15px;
        font-family: "BodyText", sans-serif;
        resize: none;
    }
    .input-box textarea {
        min-height: 120px;
        line-height: 1.6;
    }
    .input-box input::placeholder,
    .input-box textarea::placeholder {
        color: #3a3e3a;
    }

    /* Error messages */
    .error-msg {
        font-size: 11px;
        color: #ff6b6b;
        display: none;
        margin-top: 6px;
        padding-left: 4px;
    }
    .input-group.has-error .input-box {
        border-color: #ff6b6b;
    }
    .input-group.has-error .error-msg {
        display: block;
    }

    .btn-submit-contact {
        width: 100%;
        padding: 18px;
        margin-top: 10px;
        border: none;
        border-radius: 10px;
        background: #57E201;
        color: #141514;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease, color 0.3s ease;
        font-family: "BodyText", sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        position: relative;
        overflow: hidden;
    }
    .btn-submit-contact:hover {
        background: #128700;
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .btn-submit-contact:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .btn-spinner {
        width: 18px;
        height: 18px;
        border: 2px solid rgba(20,21,20,0.3);
        border-top-color: #141514;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        display: none;
    }
    .btn-submit-contact.loading .btn-spinner { display: block; }
    .btn-submit-contact.loading .btn-label  { opacity: 0.5; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .success-card {
        display: none;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 50px 20px;
        gap: 16px;
    }
    .success-card.show { display: flex; }
    .success-icon {
        width: 80px;
        height: 80px;
        background: rgba(34,197,94,0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }
    .success-card h2 {
        font-family: "TitleText", sans-serif;
        font-size: 28px;
        color: #FFFFFF;
        margin: 0;
    }
    .success-card p {
        color: #CFDEDA;
        font-size: 14px;
        line-height: 1.6;
        max-width: 360px;
        margin: 0;
    }
    .btn-go-back {
        background: transparent;
        color: #57E201;
        border: 1px solid #57E201;
        border-radius: 10px;
        padding: 12px 28px;
        font-family: "BodyText", sans-serif;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.3s, color 0.3s;
        margin-top: 8px;
    }
    .btn-go-back:hover {
        background: #57E201;
        color: #141514;
    }

    :root {
        --chat-neon: #57E201;
        --chat-black: #141514;
        --chat-card: #1c1e1c;
        --chat-border: #2b2e2b;
        --chat-white: #FFFFFF;
        --chat-grey: #CFDEDA;
        --chat-muted: #6b7a6b;
        --chat-input-bg: #101210;
    }
    .chat-widget {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 350px;
        height: 500px;
        background: var(--chat-card);
        border: 1px solid var(--chat-border);
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        z-index: 1000;
        transform: translateY(20px);
        opacity: 0;
        pointer-events: none;
        transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.3s;
        font-family: 'BodyText', sans-serif;
    }
    .chat-widget.show { transform: translateY(0); opacity: 1; pointer-events: all; }
    .chat-header {
        padding: 16px;
        background: #151615;
        border-bottom: 1px solid var(--chat-border);
        border-radius: 16px 16px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .chat-title { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 15px; color: var(--chat-white); }
    .chat-avatar { background: var(--chat-neon); color: var(--chat-black); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 900; }
    .chat-close { background: transparent; border: none; color: var(--chat-muted); cursor: pointer; font-size: 16px; transition: color 0.2s; }
    .chat-close:hover { color: var(--chat-white); }
    .chat-body { flex: 1; padding: 16px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; }
    .chat-body::-webkit-scrollbar { width: 6px; }
    .chat-body::-webkit-scrollbar-thumb { background: var(--chat-border); border-radius: 3px; }
    .message { max-width: 85%; padding: 10px 14px; border-radius: 12px; font-size: 13.5px; line-height: 1.5; }
    .ai-msg { background: #232523; color: var(--chat-grey); align-self: flex-start; border-bottom-left-radius: 4px; }
    .user-msg { background: rgba(87,226,1,0.15); color: var(--chat-white); border: 1px solid rgba(87,226,1,0.3); align-self: flex-end; border-bottom-right-radius: 4px; }
    .chat-input-area { padding: 14px; border-top: 1px solid var(--chat-border); display: flex; gap: 10px; }
    .chat-input-area input { flex: 1; background: var(--chat-input-bg); border: 1px solid var(--chat-border); border-radius: 20px; padding: 10px 16px; color: var(--chat-white); font-family: inherit; outline: none; }
    .chat-input-area input:focus { border-color: var(--chat-neon); }
    .chat-send-btn { background: var(--chat-neon); color: var(--chat-black); border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; }
    .chat-send-btn:hover { opacity: 0.9; }
    @media (max-width: 600px) {
        .chat-widget { bottom: 0; right: 0; width: 100%; height: 100%; border-radius: 0; transform: translateY(100%); }
        .chat-header { border-radius: 0; }
    }

    }
    </style>
</head>
<body>
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
			<li><a href="membership_AfterLogin.php">Membership</a></li>
			<li><a href="about-AfterLogin.php">About Us</a></li>
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


<main class="contact-page">

    <div class="contact-page-header reveal">
        <p class="section-label">CONTACT US</p>
        <h1 class="contact-page-title">Get in &mdash; touch<br>with <span>us</span></h1>
        <p class="contact-page-subtitle">
            We're here to help! Whether you have a question about our services, need assistance with your account, or want to provide feedback — our team is ready.
        </p>
    </div>

    <div class="contact-grid">

        <div class="contact-info-panel reveal reveal-left">

            <div class="info-card">
                <div class="info-card-icon"><i class="fa-regular fa-envelope"></i></div>
                <div class="info-card-text">
                    <p class="info-label">Email</p>
                    <a href="mailto:hello@zerofitness.com" class="info-value">support@zerofitness.com</a>
                    <p class="info-sub">We reply within 24 hours</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-icon"><i class="fa-solid fa-phone"></i></div>
                <div class="info-card-text">
                    <p class="info-label">Phone</p>
                    <a href="tel:+921234567899" class="info-value">+921234567899</a>
                    <p class="info-sub">Mon – Fri, 9 AM – 6 PM PKT</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-icon"><i class="fa-solid fa-location-dot"></i></div>
                <div class="info-card-text">
                    <p class="info-label">Location</p>
                    <span class="info-value">Zero Fitness HQ</span>
                    <p class="info-sub">123 Gym Street, Fitness City</p>
                </div>
            </div>

            <button class="btn-live-chat" onclick="toggleChat()">
                <i class="fa-regular fa-comment-dots"></i>
                Start Live Chat
                <i class="fa fa-chevron-right" style="font-size:11px; margin-left:auto;"></i>
            </button>

        </div>

        <div class="contact-form-panel reveal reveal-right">
			<div class="contact-form-card" id="formWrapper">
    <form id="contactForm" action="contact.php" method="POST" novalidate>

                    <div class="form-row-two">
                        <div class="input-group" id="grp-firstName">
                            <label for="firstName">First Name</label>
                            <div class="input-box">
                                <i class="fa-regular fa-user"></i>
                                <input type="text" id="firstName" name="firstName" placeholder="Enter first name..." required>
                            </div>
                            <span class="error-msg">First name is required</span>
                        </div>
                        <div class="input-group" id="grp-lastName">
                            <label for="lastName">Last Name</label>
                            <div class="input-box">
                                <i class="fa-regular fa-user"></i>
                                <input type="text" id="lastName" name="lastName" placeholder="Enter last name..." required>
                            </div>
                            <span class="error-msg">Last name is required</span>
                        </div>
                    </div>

                    <div class="input-group" id="grp-email">
                        <label for="email">Email Address</label>
                        <div class="input-box">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" id="email" name="email" placeholder="Enter your email address..." required>
                        </div>
                        <span class="error-msg">Please enter a valid email address</span>
                    </div>

                    <div class="input-group" id="grp-message">
                        <label for="message">How can we help you?</label>
                        <div class="input-box" style="align-items:flex-start; padding-top:16px;">
                            <i class="fa-regular fa-message" style="margin-top:2px;"></i>
                            <textarea id="message" name="message" placeholder="Enter your message here..." required></textarea>
                        </div>
                        <span class="error-msg">Please enter a message (min. 10 characters)</span>
                    </div>

                    <button type="submit" id="submitBtn" class="btn-submit-contact">
                        <span class="btn-label">Send Message</span>
                        <div class="btn-spinner"></div>
                        <i class="fa fa-chevron-right btn-label" style="font-size:12px;"></i>
                    </button>

                </form>
            </div>

            <div class="success-card" id="successCard">
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <h2>Message Sent!</h2>
                <p>Thank you <strong id="successName"></strong>, we've received your message and will get back to you shortly.</p>
                <button class="btn-go-back" id="resetBtn">
                    <i class="fa fa-chevron-left" style="font-size:11px;"></i> Go Back
                </button>
            </div>
        </div>

    </div>
</main>

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


<div class="chat-widget" id="chatWidget">
    <div class="chat-header">
        <div class="chat-title">
            <span class="chat-avatar">AI</span>
            <span>Fitness Assistant</span>
        </div>
        <button class="chat-close" onclick="toggleChat()">&#x2715;</button>
    </div>
    <div class="chat-body" id="chatBody">
        <div class="message ai-msg">Hi there! I'm your AI fitness assistant. Need help understanding your BMI or want some health tips?</div>
    </div>
    <div class="chat-input-area">
        <input type="text" id="chatInput" placeholder="Ask me anything..." onkeypress="handleChatKeyPress(event)">
        <button class="chat-send-btn" onclick="sendMessage()">&#x27A4;</button>
    </div>
</div>


<script>

(function () {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => observer.observe(el));
})();

/* ════ FORM VALIDATION ════ */
const form       = document.getElementById('contactForm');
const submitBtn  = document.getElementById('submitBtn');
const successCard = document.getElementById('successCard');
const formWrapper = document.getElementById('formWrapper');
const resetBtn   = document.getElementById('resetBtn');

function setError(id, show) {
    const grp = document.getElementById('grp-' + id);
    if (grp) grp.classList.toggle('has-error', show);
}

function validateEmail(v) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
}

form.addEventListener('submit', function (e) {
    e.preventDefault();

    const fn  = document.getElementById('firstName').value.trim();
    const ln  = document.getElementById('lastName').value.trim();
    const em  = document.getElementById('email').value.trim();
    const msg = document.getElementById('message').value.trim();

    let valid = true;

    setError('firstName', !fn);  if (!fn)  valid = false;
    setError('lastName',  !ln);  if (!ln)  valid = false;
    setError('email',  !validateEmail(em)); if (!validateEmail(em)) valid = false;
    setError('message', msg.length < 10);   if (msg.length < 10) valid = false;

if (!valid) return;

submitBtn.disabled = true;
submitBtn.classList.add('loading');

setTimeout(function () {
    form.submit();
}, 1200);
});

['firstName','lastName','email','message'].forEach(function (id) {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', function () { setError(id, false); });
});

resetBtn.addEventListener('click', function () {
    successCard.classList.remove('show');
    formWrapper.querySelector('form').style.display = '';
    form.reset();
});

let chatHistory = [];

function toggleChat() {
    document.getElementById('chatWidget').classList.toggle('show');
}

function handleChatKeyPress(e) {
    if (e.key === 'Enter') sendMessage();
}

function appendMessage(text, sender) {
    const body   = document.getElementById('chatBody');
    const msgDiv = document.createElement('div');
    msgDiv.className = 'message ' + sender + '-msg';
    msgDiv.textContent = text;
    body.appendChild(msgDiv);
    body.scrollTop = body.scrollHeight;
}

async function sendMessage() {
    const input = document.getElementById('chatInput');
    const text  = input.value.trim();
    if (!text) return;

    appendMessage(text, 'user');
    input.value = '';
    chatHistory.push({ role: 'user', parts: [{ text }] });

    const typingId = 'typing-' + Date.now();
    const body   = document.getElementById('chatBody');
    const tDiv   = document.createElement('div');
    tDiv.className = 'message ai-msg';
    tDiv.id = typingId;
    tDiv.textContent = '...';
    body.appendChild(tDiv);
    body.scrollTop = body.scrollHeight;

    try {
        const res  = await fetch('http://localhost:3000/api/chat', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ message: text, history: chatHistory })
        });
        const data = await res.json();
        document.getElementById(typingId).remove();
        if (data.success) {
            appendMessage(data.text, 'ai');
            chatHistory.push({ role: 'model', parts: [{ text: data.text }] });
        } else {
            appendMessage(data.error || 'Failed to reach AI assistant.', 'ai');
        }
    } catch (err) {
        document.getElementById(typingId).remove();
        appendMessage('Connection error. Is the server running?', 'ai');
    }
}
</script>

</body>
</html>


