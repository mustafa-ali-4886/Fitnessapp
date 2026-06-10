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
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="responsive-overrides.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/x-icon" href="favicon.png">
	<link rel="stylesheet" href="Style_3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>s

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
	
	<div class="banner">
		<video src="12382105_1280_720_25fps.mp4" autoplay loop muted playsinline class="bg-video"></video>
	</div>

	<div class="bannertext">
		<h1 class="title">
			<span class="line-1">Choose the</span>
			<span class="line-2">Perfect Plan For</span>
			<span class="line-3">Your Journey</span>
		</h1>
		<p class="Bannertext">Transform your fitness journey with flexible plans designed to match your goals, lifestyle, and ambition.</p>
		<div class="HeroButtons">
			<button class="getstarted" onclick="location.href='#pricing-section'">Explore Plans <i class="fa fa-chevron-right"></i></button>
		</div>
	</div>
</header>
<div class="pricing-section" id= "pricing-section">
    <div class="pricing-header">
        <p class="section-label">PRICING</p>
			<h1 class="heading3">
				<span class="pickyour">PICK YOUR</span>
				<span class="perfectplan">PERFECT PLAN</span>
			</h1>
			<p class="gettoknow1">Transform your fitness journey with flexible plans designed to match your goals, lifestyle, and ambition.</p>
    </div>

    <div class="pricing-cards-container">
    <div class="price-card">
        <h3 class="plan-name">Basic Package</h3>
        <div class="plan-price">Rs 5000<span>/month</span></div>
        
        <ul class="plan-features">
            <li>Access to Cardio Section</li>
            <li>Access to General Training Programs</li>
            <li>Access to Free Weights Section</li>
            <li>Access to Cross Fit Area</li>
            <li>Once in 3 weeks Consultation</li>
        </ul>
		<button class="getstarted" onclick="window.location.href='membership_form1.php'">Get this Plan<i class="fa fa-chevron-right"></i></button>
    </div>

    <div class="price-card featured-card">
        <h3 class="plan-name">Platinum Package</h3>
        <div class="plan-price">Rs 7000<span>/month</span></div>
        
        <ul class="plan-features">
            <li>Access to Cardio Section</li>
            <li>Access to General Training Programs</li>
            <li>Access to Boxing Area & Sauna</li>
            <li>Once in a 2 weeks Consultation</li>
            <li>Get a Personal Trainer</li>
        </ul>
        <button class="getstarted" onclick="window.location.href='membership_form2.php'">Get this Plan<i class="fa fa-chevron-right"></i></button>
    </div>

    <div class="price-card">
        <h3 class="plan-name">Diamond Package</h3>
        <div class="plan-price">Rs 10,000<span>/month</span></div>
        
        <ul class="plan-features">
            <li>Complete Gym Access</li>
            <li>Pro Package for Digital Experience</li>
            <li>16 Personal Training Sessions</li>
            <li>Unlimited Consultation</li>
            <li>Access to Premium Parking</li>
        </ul>
        <button class="getstarted" onclick="window.location.href='membership_form3.php'">Get this Plan<i class="fa fa-chevron-right"></i></button>
    </div>
</div>
</div>
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

<script>

/* ===== SCROLL REVEAL INTERSECTION OBSERVER ===== */
(function() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => observer.observe(el));
})();

/* ===== HOMEPAGE BMI SLOT MACHINE ANIMATION ===== */
const HP_DIGITS = ['0','1','2','3','4','5','6','7','8','9'];
const hpSlot = document.getElementById('hp-slotWrapper');

function hpGetItemH() {
  const el = hpSlot ? hpSlot.querySelector('.digit-item') : null;
  return el ? el.getBoundingClientRect().height || 130 : 130;
}

function hpBuildSlot(id, isDot) {
  const col = document.createElement('div');
  col.className = isDot ? 'digit-col dot-col' : 'digit-col';
  col.id = 'hpcol_' + id;
  if (isDot) {
    const d = document.createElement('div');
    d.className = 'dot-char'; d.textContent = '.';
    col.appendChild(d);
  } else {
    const strip = document.createElement('div');
    strip.className = 'digit-strip';
    strip.id = 'hpstrip_' + id;
    HP_DIGITS.forEach((d, i) => {
      const item = document.createElement('div');
      item.className = 'digit-item';
      item.id = 'hpdigit_' + id + '_' + i;
      item.textContent = d;
      strip.appendChild(item);
    });
    col.appendChild(strip);
  }
  return col;
}

// Only execute the slot builder if the slot wrapper container exists on the active page
if (hpSlot) {
  ['d0','d1','dot','d2'].forEach(id => hpSlot.appendChild(hpBuildSlot(id, id==='dot')));
  setTimeout(() => { hpSetSlot('d0',0,false); hpSetSlot('d1',0,false); hpSetSlot('d2',0,false); }, 50);
}

function hpSetSlot(id, digit, animate) {
  const strip = document.getElementById('hpstrip_' + id);
  if (!strip) return;
  HP_DIGITS.forEach((_,i) => {
    const el = document.getElementById('hpdigit_' + id + '_' + i);
    if (el) el.classList.toggle('active-digit', i === digit);
  });
  const H = hpGetItemH();
  const targetTop = -(digit * H);
  if (animate) {
    const spinStart = targetTop - (2 * HP_DIGITS.length * H) - (H * 3);
    strip.style.transition = 'none';
    strip.style.top = spinStart + 'px';
    requestAnimationFrame(() => requestAnimationFrame(() => {
      strip.style.transition = 'top 0.6s cubic-bezier(0.22,1,0.36,1)';
      strip.style.top = targetTop + 'px';
    }));
  } else {
    strip.style.transition = 'none';
    strip.style.top = targetTop + 'px';
  }
}

function hpSetGender(g) {
  ['hp-gFemale','hp-gMale','hp-gOther'].forEach(id => document.getElementById(id).classList.remove('active'));
  const target = document.getElementById({female:'hp-gFemale', male:'hp-gMale', other:'hp-gOther'}[g]);
  if(target) target.classList.add('active');
}

function hpCalcBMI() {
  const w = parseFloat(document.getElementById('hp-weightInput').value);
  const h = parseFloat(document.getElementById('hp-heightInput').value);
  if (!w || w < 1 || w > 450 || !h || h < 1 || h > 300) {
    alert('Please enter valid weight (1-450 kg) and height (1-300 cm).');
    return;
  }
  const hm = h / 100;
  const bmi = w / (hm * hm);
  const rounded = Math.round(bmi * 10) / 10;
  const str = rounded.toFixed(1);
  const [intPart, decPart] = str.split('.');
  const padded = intPart.padStart(2,'0');

  if (hpSlot) hpSlot.classList.remove('idle');
  setTimeout(() => hpSetSlot('d0', parseInt(padded[0]), true), 0);
  setTimeout(() => hpSetSlot('d1', parseInt(padded[1]), true), 90);
  setTimeout(() => hpSetSlot('d2', parseInt(decPart[0]), true), 180);

  const badge = document.getElementById('hp-catBadge');
  const foot  = document.getElementById('hp-bmiFoot');
  const scale = document.getElementById('hp-scaleWrap');
  const dot   = document.getElementById('hp-scaleDot');
  const result = document.getElementById('hp-slotResult');

  let cat, cls, tip, pct;
  if (bmi < 18.5)      { cat='Underweight'; cls='cat-badge show cat-under'; tip='Your BMI suggests you may be underweight.'; pct=Math.max(2,(bmi/18.5)*25); }
  else if (bmi < 25)   { cat='Normal weight'; cls='cat-badge show cat-normal'; tip='Great! Your BMI is within the healthy range.'; pct=25+((bmi-18.5)/6.5)*25; }
  else if (bmi < 30)   { cat='Overweight'; cls='cat-badge show cat-over'; tip='A balanced diet and regular exercise can help.'; pct=50+((bmi-25)/5)*25; }
  else                 { cat='Obese'; cls='cat-badge show cat-obese'; tip='Please consult a healthcare professional.'; pct=Math.min(98,75+((bmi-30)/10)*25); }

  if(badge) { badge.textContent = cat; badge.className = cls; }
  if(foot) foot.textContent = tip;
  if(result) result.classList.add('show');
  if(scale) scale.classList.add('show');
  if(dot) setTimeout(() => { dot.style.left = pct + '%'; }, 60);
}
</script>
    <script src="mobile-nav.js"></script>
</body>
</html>



