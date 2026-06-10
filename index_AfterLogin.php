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
	<link rel="stylesheet" href="Style_2.css">
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
			<li class = "active">Home</li>
			<li><a href="programs-Afterlogin.php">Programs</a></li>

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
	
	<div class="banner">
		<img src="Banner1.png" alt="banner image">
	</div>

	<div class="bannertext">
		<h1 class="title">
			<span class="line-1">Your Fitness</span>
			<span class="line-2">Journey</span>
			<span class="line-3">Starts Here</span>
		</h1>
		<p class="Bannertext">Getting fit doesn't have to feel complicated. With simple workouts, personalized plans, and the right support, we'll help you stay on track and see real progress.</p>
		<div class="HeroButtons">
			<button class="getstarted" onclick="window.location.href='programs-Afterlogin.php';">Join Now <i class="fa fa-chevron-right"></i></button>
			<button class="learnmore"onclick="window.location.href='about-Afterlogin.php';">Learn More</button>
		</div>
		<div class="Avatars"><img src="Avatars.png" alt="Avatars"></div>
		<div class="Ratingdetails">
			<p class="rating-no">500+ Ratings</p>
			<div class="Stars">
				<i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i>
			</div>
		</div>
	</div>
</header>

<div class="strip-container reveal">
	<div class="Scroller">
		<ul class="tagicons">
			<li><img src="1999446726a60718ad6d9a83ca9aa2c4.png" alt="asics"></li>
			<li><img src="toppng.com-under-armour-logo-white-png-sketch-988x557.png" alt="underarmour"></li>
			<li><img src="pngegg (1).png" alt="adidas"></li>
			<li><img src="b4f95f7c9f3b1f0a32d56c74c46e8abb.png" alt="puma"></li>
			<li><img src="toppng.com-reviousnext-imagenes-del-logo-reebok-441x209.png" alt="reebok"></li>
			<li><img src="1999446726a60718ad6d9a83ca9aa2c4.png" alt="asics"></li>
			<li><img src="toppng.com-under-armour-logo-white-png-sketch-988x557.png" alt="underarmour"></li>
			<li><img src="pngegg (1).png" alt="adidas"></li>
			<li><img src="b4f95f7c9f3b1f0a32d56c74c46e8abb.png" alt="puma"></li>
			<li><img src="toppng.com-reviousnext-imagenes-del-logo-reebok-441x209.png" alt="reebok"></li>
		</ul>
	</div>
</div>

<div class="section3 reveal">
	<div class="section-header">
		<div class="section-header-left">
			<p class="section-label">WHAT WE OFFER</p>
			<h1 class="heading3">
				<span class="programdesigned">PROGRAMS DESIGNED</span>
				<span class="foreverylevel">FOR EVERY FITNESS LEVEL</span>
			</h1>
			<p class="gettoknow1">Get to know more about our current running programs</p>
		</div>
		<button class="See_All">See All</button>
	</div>

	<div class="cardsplacement">
		<div class="program-card">
			<div class="card-image-wrapper">
				<img src="michael-faix-RlLjsllCe14-unsplash.jpg" class="card-image" alt="Female Fitness Festival">
			</div>
			<div class="card-body">
				<h3 class="card-title">Female Fitness Festival 2026</h3>
				<p class="card-description">Step into a high-energy fitness experience designed exclusively for women who want to feel stronger and more confident.</p>
				<div class="card-footer">
					<button class="card-btn">Join Now</button>
				</div>
			</div>
		</div>

		<div class="program-card card-featured">
			<div class="card-image-wrapper">
				<img src="luke-witter-k47w6BeapCs-unsplash.jpg" class="card-image" alt="Pure Strength Program">
			</div>
			<div class="card-body">
				<h3 class="card-title">Pure Strength Program</h3>
				<p class="card-description">Built for men who want real, measurable strength. No-nonsense training that delivers results you can see and feel.</p>
				<div class="card-footer">
					<button class="card-btn">Join Now</button>
				</div>
			</div>
		</div>

		<div class="program-card">
			<div class="card-image-wrapper">
				<img src="hamza-nouasria-t7SyUNppIeA-unsplash.jpg" class="card-image" alt="Conditioning Program">
			</div>
			<div class="card-body">
				<h3 class="card-title">Ultimate Conditioning Program</h3>
				<p class="card-description">Master compound movements, build functional power, and elevate your overall physical work capacity to the max.</p>
				<div class="card-footer">
					<button class="card-btn">Join Now</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="section4 reveal">
	<div class="firstcolumntext">
		<p class="about-label">ABOUT US</p>
		<h2 class="about-heading">We Build <span class="green-text">Stronger</span> People</h2>

			<ul class="bulletpoints">
    		<li><i class="fas fa-check"></i> <b>Elite Coaching Framework:</b> Backed by industry-leading expert trainers who build customized growth paths instead of generic, rigid routines.</li>
    		<li><i class="fas fa-check"></i> <b>Results-Driven Tracking:</b> Stay locked into your progress with precision momentum metrics that focus on your performance gains and personal bests.</li>
    		<li><i class="fas fa-check"></i> <b>Premium Training Spaces:</b> Train using top-tier, modern equipment engineered to optimize safety, focus, and athletic execution.</li>
    		<li><i class="fas fa-check"></i> <b>Comprehensive Wellness Approach:</b> From tailored nutrition guidance to compound lifting programs, we align every layer of your health.</li>
    		<li><i class="fas fa-check"></i> <b>Unstoppable Community Support:</b> Join a high-energy network of like-minded individuals driven to push limits and inspire your best self.</li>
			</ul>

		<button class="learn-more-btn">Learn More</button>
	</div>

	<div class="video-wrapper">
		<video src="AboutUs.mp4" autoplay loop muted playsinline class="bg-video"></video>
	</div>
</div>

<div class="section5 reveal">
	<div class="section-header">
		<div class="section-header-left">
			<p class="section-label">THE TEAM</p>
			<h1 class="heading3">
				<span class="programdesigned">MEET OUR TRAINERS</span>
				<span class="foreverylevel">DEDICATED COACHES</span>
			</h1>
			<p class="gettoknow1">Get to know the experts behind your transformation</p>
		</div>
		<button class="See_All">See All</button>
	</div>

	<div class="cardsplacement">
		<div class="program-card">
			<div class="card-image-wrapper">
				<img src="athletic-blond-male-doing-biceps-workout-with-barbell.jpg" class="card-image" alt="Trainer">
			</div>
			<div class="card-body">
				<h3 class="card-title">Jabeen Mikael | Bulkmaster</h3>
				<div class="card-metrics">
					<div class="metric">
						<p class="metric-value"><i class="fas fa-star"></i> 4.5</p>
						<p class="metric-label">Rating</p>
					</div>
					<div class="metric-divider"></div>
					<div class="metric">
						<p class="metric-value">2000/-</p>
						<p class="metric-label">Per Session</p>
					</div>
					<div class="metric-divider"></div>
					<div class="metric">
						<p class="metric-value">10 Yrs</p>
						<p class="metric-label">Experience</p>
					</div>
				</div>
				<p class="card-description">Jabeen Mikael is a highly experienced fitness coach with 10 years of expertise in strength training and muscle development.</p>
				<div class="card-footer">
					<button class="card-btn">Hire Now <i class="fa fa-chevron-right"></i></button>
				</div>
			</div>
		</div>

		<div class="program-card card-featured">
			<div class="card-image-wrapper">
				<img src="girl-athlete-keeps-disc-from-bar-weighting-agent-doing-crossfit-fitness-concept-sports-equipment-weight-loss.jpg" class="card-image" alt="Trainer">
			</div>
			<div class="card-body">
				<h3 class="card-title">Sarah Hayat | Squat Specialist</h3>
				<div class="card-metrics">
					<div class="metric">
						<p class="metric-value"><i class="fas fa-star"></i> 4.9</p>
						<p class="metric-label">Rating</p>
					</div>
					<div class="metric-divider"></div>
					<div class="metric">

						<p class="metric-value">1500/-</p>
						<p class="metric-label">Per Session</p>
					</div>
					<div class="metric-divider"></div>
					<div class="metric">
						<p class="metric-value">5 Yrs</p>
						<p class="metric-label">Experience</p>
					</div>
				</div>
				<p class="card-description">Sarah Hayat is a dedicated fitness trainer with 5 years of hands-on experience helping clients build strength, confidence, and proper lifting techniques. Specializing in squats and lower-body training</p>
				<div class="card-footer">
					<button class="card-btn">Hire Now <i class="fa fa-chevron-right"></i></button>
				</div>
			</div>
		</div>

		<div class="program-card">
			<div class="card-image-wrapper">
				<img src="download.jpg" class="card-image" alt="Trainer">
			</div>
			<div class="card-body">
				<h3 class="card-title">Liana Deans | Cardio Specialist</h3>
				<div class="card-metrics">
					<div class="metric">
						<p class="metric-value"><i class="fas fa-star"></i> 4.0</p>
						<p class="metric-label">Rating</p>
					</div>
					<div class="metric-divider"></div>
					<div class="metric">
						<p class="metric-value">5000/-</p>
						<p class="metric-label">Per Session</p>
					</div>
					<div class="metric-divider"></div>
					<div class="metric">
						<p class="metric-value">7 Yrs</p>
						<p class="metric-label">Experience</p>
					</div>
				</div>
				<p class="card-description">Liana Deans is a passionate cardio fitness specialist with 7 years of experience helping clients improve stamina, endurance, and overall health.</p>
				<div class="card-footer">
					<button class="card-btn">Hire Now <i class="fa fa-chevron-right"></i></button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="bmi-section" id="bmi-section">
	<div class="bmi-left reveal reveal-left">
		<p class="section-label">OUR PRODUCTS</p>
		<h1 class="bmi-heading">
			<span class="white-text">Find Your BMI in</span>
			<span class="green-text-block">Seconds</span>
		</h1>
		<p class="bmi-subtext">Your Body Mass Index is a simple but powerful starting point. Enter your details and get an instant reading with guidance.</p>

		<div class="bmi-stats reveal-stagger">
			<div class="stat-box">
				<p class="stat-number">Below 18.5</p>
				<p class="stat-label">Underweight</p>
			</div>
			<div class="stat-box stat-highlight">
				<p class="stat-number">18.5 to 24.9</p>
				<p class="stat-label">Normal</p>
			</div>
			<div class="stat-box">
				<p class="stat-number">25 to 29.9</p>
				<p class="stat-label">Overweight</p>
			</div>
			<div class="stat-box">
				<p class="stat-number">30+</p>
				<p class="stat-label">Obese</p>
			</div>
		</div>
	</div>

	<div class="bmi-panel reveal reveal-right" style="padding:0; overflow:hidden; background:transparent; border:none; display:flex; align-items:center; justify-content:center; flex:2; max-width:100%;">
		<a href="bmi-calculator-updated-afterlogin.php">
			<img src="mockuper.png" alt="BMI Calculator Preview" style="width:100%; border-radius:20px; display:block; transition: transform 0.3s ease; cursor:pointer;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
		</a>
	</div>
<div class="testimonials-section reveal">
	<div class="section-header">
		<div class="section-header-left">
			<p class="section-label">TESTIMONIALS</p>
			<h1 class="heading3">
				<span class="programdesigned">WHAT OUR CLIENTS</span>
				<span class="foreverylevel">SAY ABOUT US</span>
			</h1>
		</div>
	</div>

	<div class="testimonials-grid">
		<div class="testimonial-card">
			<div class="testimonial-box">
				<p class="testimonial-text">"The training programs here completely changed my life. I went from barely lifting to hitting personal records every week. The coaches genuinely care about the progress."</p>
				<div class="testimonial-stars"><i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i></div>
			</div>
			<div class="testimonial-person">
				<img src="Jennifer.jpg" alt="Jenny D'Souza" class="testimonial-avatar">
				<h4 class="testimonial-name">JENNY D'SOUZA</h4>
				<p class="testimonial-role">Fitness Program Member</p>
			</div>
		</div>

		<div class="testimonial-card">
			<div class="testimonial-box">
				<p class="testimonial-text">"Best investment I've made in myself. The BMI tracking, personalized plans, and the community support kept me consistent for over 6 months straight."</p>
				<div class="testimonial-stars"><i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i></div>
			</div>
			<div class="testimonial-person">
				<img src="Kareem.jpg" alt="Kareen Ali" class="testimonial-avatar">
				<h4 class="testimonial-name">KAREEM ALI </h4>
				<p class="testimonial-role">Cardio Program-26 Member</p>
			</div>
		</div>
	</div>
</div>

<div class="contact-us reveal">
	<div class="bgimage">
		<img src="contact.jpg" alt="background image">
	</div>
	<div class="content">
		<p class="section-label">Contact Us</p>
		<h1 class="titlel">
			<span class="white-text">Have any Question</span>
			<span class="green-text-block">For Us?</span>
		</h1>
		<p class="details">Have questions, feedback, or need assistance? Our team is here to help you every step of the way. <br> Reach out to us anytime, and weâ€™ll get back to you as soon as possible.</p>
		<button class="card-btn" onclick="window.location.href='contact-After_Login.php';">
    Contact Now <i class="fa fa-chevron-right"></i>
</button>
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
        <p>© 2026 ZeroFitness. All rights reserved.</p>
        <p style="margin: 0;">Designed for Excellence</p>
    </div>
</footer>


<script>
/* ===== SCROLL REVEAL ===== */
(function() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) entry.target.classList.add('visible');
    });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => observer.observe(el));
})();

/* ===== HOMEPAGE BMI SLOT ===== */
const HP_DIGITS = ['0','1','2','3','4','5','6','7','8','9'];
const hpSlot = document.getElementById('hp-slotWrapper');

function hpGetItemH() {
  const el = hpSlot ? hpSlot.querySelector('.digit-item') : null;
  return el ? el.getBoundingClientRect().height || 130 : 130;
}

function hpBuildSlot(id, isDot) {
  const col = document.createElement('div');
  col.className = isDot ? 'digit-col dot-col' : 'digit-col';
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
  document.getElementById({female:'hp-gFemale', male:'hp-gMale', other:'hp-gOther'}[g]).classList.add('active');
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

  const badge  = document.getElementById('hp-catBadge');
  const foot   = document.getElementById('hp-bmiFoot');
  const scale  = document.getElementById('hp-scaleWrap');
  const dot    = document.getElementById('hp-scaleDot');
  const result = document.getElementById('hp-slotResult');

  let cat, cls, tip, pct;
  if (bmi < 18.5)    { cat='Underweight';  cls='cat-badge show cat-under';  tip='Your BMI suggests you may be underweight.';        pct=Math.max(2,(bmi/18.5)*25); }
  else if (bmi < 25) { cat='Normal weight'; cls='cat-badge show cat-normal'; tip='Great! Your BMI is within the healthy range.';     pct=25+((bmi-18.5)/6.5)*25; }
  else if (bmi < 30) { cat='Overweight';   cls='cat-badge show cat-over';   tip='A balanced diet and regular exercise can help.';   pct=50+((bmi-25)/5)*25; }
  else               { cat='Obese';        cls='cat-badge show cat-obese';  tip='Please consult a healthcare professional.';        pct=Math.min(98,75+((bmi-30)/10)*25); }

  badge.textContent = cat; badge.className = cls;
  foot.textContent = tip;
  result.classList.add('show');
  scale.classList.add('show');
  setTimeout(() => { dot.style.left = pct + '%'; }, 60);
}
</script>
    <script src="mobile-nav.js"></script>
</body>
</html>



