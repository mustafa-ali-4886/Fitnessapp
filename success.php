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

require_once 'tcpdf/tcpdf.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$session_email = mysqli_real_escape_string($conn, $_SESSION['email']);

$query = "SELECT id, username FROM users WHERE email = '$session_email'";
$result = mysqli_query($conn, $query);

$display_name = "User";
$full_name = "Member";
$user_id = 0;

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $user_id = $user_data['id'];
    $full_name = htmlspecialchars($user_data['username']);
    $name_parts = explode(' ', trim($user_data['username']));
    $display_name = htmlspecialchars($name_parts[0]);
}

$phone_no = "N/A";
$selected_plan_text = "Standard Plan";
$bookingCode = "XY@" . rand(1000, 9999);
$issueDate = date('d-m-Y');

if ($user_id > 0) {
    $booking_query = "SELECT phone_no, selected_plan FROM membership_info WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
    $booking_result = mysqli_query($conn, $booking_query);

    if ($booking_result && mysqli_num_rows($booking_result) > 0) {
        $booking_data = mysqli_fetch_assoc($booking_result);
        $phone_no = htmlspecialchars($booking_data['phone_no']);

        if ($booking_data['selected_plan'] == 3) {
            $selected_plan_text = "Diamond - 10000/month";
        } elseif ($booking_data['selected_plan'] == 2) {
            $selected_plan_text = "Platinum - 7000/month";
        } else {
            $selected_plan_text = "Basic - 5000/month";
        }
    }
}

mysqli_close($conn);

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('ZeroFitness');
$pdf->SetTitle('ZeroFitness - Booking Transcript');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

$html = '
<table cellpadding="0" cellspacing="0" style="width: 100%;">
    <tr>
        <td style="text-align: center; padding-bottom: 30px;">
            <img src="' . __DIR__ . '/Logo_V3.png" width="220" />
        </td>
    </tr>
</table>

<br />

<table cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 25px;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <span style="font-size: 13px; font-weight: bold; text-decoration: underline;">CODE:</span>
            <span style="font-size: 13px; font-weight: bold;"> ' . $bookingCode . '</span>
            <span style="font-size: 10px; font-style: italic; color: #444444;"> (valid till one week)</span>
        </td>
        <td style="width: 50%; text-align: right; vertical-align: top;">
            <span style="font-size: 13px; font-weight: bold; text-decoration: underline;">DATE:</span>
            <span style="font-size: 13px;"> ' . $issueDate . '</span>
        </td>
    </tr>
</table>

<br /><br />

<p style="font-size: 13px; font-weight: bold; text-decoration: underline; margin-bottom: 10px;">USER DETAILS:</p>
<table cellpadding="4" cellspacing="0" style="width: 100%; font-size: 12px; color: #141514;">
    <tr><td><strong>Full Name:</strong> ' . $full_name . '</td></tr>
    <tr><td><strong>Package:</strong> ' . $selected_plan_text . '</td></tr>
    <tr><td><strong>Phone:</strong> ' . $phone_no . '</td></tr>
    <tr><td><strong>Email:</strong> ' . htmlspecialchars($session_email) . '</td></tr>
</table>

<br /><br />

<p style="font-size: 13px; font-weight: bold; text-decoration: underline; margin-bottom: 10px;">GYM DETAILS:</p>
<table cellpadding="4" cellspacing="0" style="width: 100%; font-size: 12px; color: #141514;">
    <tr><td><strong>GYM Address:</strong> <a href="https://maps.app.goo.gl/HRWJfT8AM3G2RJob9" style="color: #141514; text-decoration: underline;">https://maps.app.goo.gl/HRWJfT8AM3G2RJob9</a></td></tr>
    <tr><td><strong>Phone:</strong> 0123456789</td></tr>
    <tr><td><strong>Phone 2:</strong> 0123456789</td></tr>
</table>

<br /><br />

<p style="font-size: 13px; font-weight: bold; text-decoration: underline; margin-bottom: 10px;">INSTRUCTIONS:</p>
<ol style="font-size: 12px; line-height: 1.8; margin: 0; padding-left: 16px; color: #141514;">
    <li>Please visit the gym within one week of the issuance of this document.</li>
    <li>Kindly present this booking transcript at the reception upon arrival.</li>
    <li>Complete the payment of the applicable package fee.</li>
    <li>Upon successful registration, you may begin your gym membership and workout program.</li>
</ol>

<br /><br /><br />

<p style="font-size: 10px; color: #141514; font-style: italic; line-height: 1.5;">
    <strong><u>Note:</u></strong> Failure to provide this document (or expired document) may result in your request not being processed.
</p>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdfString = $pdf->Output('ZeroFitness_Booking_Transcript.pdf', 'S');

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'support.zerofitness@gmail.com';
    $mail->Password   = 'cedlpyaoshchjfbn';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('support.zerofitness@gmail.com', 'ZeroFitness Team');
    $mail->addAddress($session_email, $full_name);

    $mail->addStringAttachment($pdfString, 'ZeroFitness_Booking_Transcript.pdf');

    $mail->isHTML(true);
    $mail->Subject = 'Your ZeroFitness Booking Confirmation - ' . $bookingCode;
    $mail->Body    = '
        <div style="font-family: Arial, sans-serif; font-size: 14px; color: #141514; line-height: 1.5;">
            <p>Hi ' . $display_name . ',</p>
            <p>Thank you for choosing <strong>ZeroFitness</strong>! Your slot has been booked successfully.</p>
            <p>Please find your attached booking ticket containing your unique confirmation code:
               <strong style="color: #57E201; background-color: #141514; padding: 2px 5px; border-radius: 3px;">' . $bookingCode . '</strong>.
            </p>
            <p>Make sure to bring this copy to the gym registration desk within one week to process your membership.</p>
            <br>
            <p>Best Regards,<br><strong>ZeroFitness Team</strong></p>
        </div>';

    $mail->send();
} catch (Exception $e) {
    error_log("PHPMailer could not deliver mail. Error Details: {$mail->ErrorInfo}");
}
?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <link rel="stylesheet" href="Style_5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Booking Success - ZeroFitness</title>
</head>
<body>

<header class="hero">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <nav class="navbar">
        <div class="logo">
            <img src="Logo-v2.png" alt="logo">
        </div>

        <ul class="navigationbar">
            <li><a href="index_AfterLogin.php">Home</a></li>
            <li><a href="programs-Afterlogin.php">Programs</a></li>

            <li class="has-dropdown">
                Products <i class="fa fa-chevron-down"></i>
                <div class="dropdown-menu">
                    <a href="bmi-calculator-updated-afterlogin.php" class="dropdown-item">
                        <div>b
                            <p class="dropdown-title">BMI Calculator</p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="active">Membership</li>
            <li><a href ="about-AfterLogin.php">About Us</a></li>
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

    <div class="msgcontainer">
        <h1 class="successmsg">Congratulations! Your slot has been booked successfully.
            <span class="greentext">Kindly Check Your Email.</span>
        </h1>
        <p class="text">We have sent you the essential details associated with your registration.
            If you are facing any difficulty in email receiving process, feel free to contact us.
        </p>
        <button class="ContactUs"onclick="window.location.href='contact.html';">Contact Us</button>
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

<script>/* ===== SCROLL REVEAL INTERSECTION OBSERVER ===== */
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
}</script>
</body>
</html>

