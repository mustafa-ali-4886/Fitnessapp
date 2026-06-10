<?php
session_start();

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true || !isset($_SESSION['reset_email'])) {
    header("Location: forgotpassword.html");
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$database = "fitnessguide";

$message = "";
$message_class = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    
    $conn = mysqli_connect($host, $username, $password, $database);
    
    if (!$conn) {
        die("Connection Failed: " . mysqli_connect_error());
    }

    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $email = $_SESSION['reset_email'];

    if ($new_pass !== $confirm_pass) {
        $message = "Passwords do not match. Please try again.";
        $message_class = "error";
    } else {
        $safe_password = mysqli_real_escape_string($conn, $new_pass);
        
        $query = "UPDATE users SET password = '$safe_password' WHERE email = '$email'";
        
        if (mysqli_query($conn, $query)) {
            unset($_SESSION['reset_email']);
            unset($_SESSION['reset_otp']);
            unset($_SESSION['otp_expiry']);
            unset($_SESSION['otp_verified']);
            
            echo "
            <script>
            alert('Password updated successfully! Please sign in with your new password.');
            window.location.href = 'login.html';
            </script>
            ";
            exit();
        } else {
            $message = "Failed to update password in the database.";
            $message_class = "error";
        }
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
	<link rel="icon" type="image/x-icon" href="favicon.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://dribbble.com/shots/27230601-Website-Login-Page-Design">
</head>
<body>

<div class="container">

    <div class="left-section"></div>

    <div class="right-section">

        <div class="login-box">

            <div class="login-logo">
                <img src="Logo-v2.png" alt="Fitness Logo">
            </div>

            <h1>Create New Password</h1>
            <p>Set a secure password for your account</p>

            <form action="reset_password.php" method="POST">

                <?php if (!empty($message)): ?>
                    <div style="background: #2b1b1b; border: 1px solid #ff3333; color: #ff6666; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; text-align: center;">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <label>New Password</label>
                <div class="input-box">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="new_password" placeholder="Minimum 6 characters" required>
                </div>

                <label>Confirm Password</label>
                <div class="input-box">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Repeat your new password" required>
                </div>

                <button type="submit" class="login-btn">
                    Update Password
                </button>

            </form>

        </div>

    </div>

</div>
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
