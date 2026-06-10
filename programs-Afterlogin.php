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
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fitness Programs - ZeroFitness</title>
<link rel="icon" type="image/x-icon" href="favicon.png">
<link href="cssstyle.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<header class="hero">
<nav class="navbar">
		<div class="logo">
			<img src="Logo-v2.png" alt="logo">
		</div>

		<ul class="navigationbar">
			<li><a href="index_AfterLogin.php">Home</a></li>
			<li class = "active">Programs</li>

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
			<li><a href="about-AfterLogin.php">About</a></li>
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
</header>

<div class="wrapper">

    <div class="page-header reveal">
        <span class="tagline">Our Offerings</span>
        <h1 class="main-heading">Available Fitness Programs</h1>
        <p class="sub-text">Choose a fitness path tailored explicitly to your performance target tracks.</p>
    </div>
    <div class="program-row reveal-stagger">
        <div class="program-box">
            <h3>Summer Fitness</h3>
            <p>High-intensity dynamic styling structures engineered for rapid physical conditioning loops.</p>
        </div>
        <div class="program-box">
            <h3>Weight Loss</h3>
            <p>Shed metrics efficiently via safe calorie deficits mixed with systemic cardio routines.</p>
        </div>
        <div class="program-box">
            <h3>Muscle Gain</h3>
            <p>Hypertrophy progression tracking strategies utilizing high intensity resistance work.</p>
        </div>
        <div class="program-box last">
            <h3>Cardio Training</h3>
            <p>Heart acceleration circuits focused entirely on improving physical endurance bounds.</p>
        </div>
    </div>

    <div class="featured-programs-header reveal">
        <div class="featured-programs-title">
            <span class="tagline">WHAT WE OFFER</span>
            <h2 class="featured-heading">PROGRAMS DESIGNED <span>FOR EVERY FITNESS LEVEL</span></h2>
            <p class="featured-sub">Get to know more about our current running programs</p>
        </div>
    </div>

    <div class="featured-programs-grid reveal-stagger">

        <div class="featured-card">
            <div class="featured-card-img" style="background: #1a1a1a url('michael-faix-RlLjsllCe14-unsplash.jpg') center/cover no-repeat;">
                <div class="card-img-overlay"></div>
            </div>
            <div class="featured-card-body">
                <h3>Female Fitness Festival 2026</h3>
                <p>Step into a high-energy experience designed exclusively for women who want to feel stronger and more confident.</p>
                <a href="membership.html" class="join-btn">Join Now</a>
            </div>
        </div>

        <div class="featured-card featured-card--active">
            <div class="featured-card-img" style="background: #1a1a1a url('luke-witter-k47w6BeapCs-unsplash.jpg') center/cover no-repeat;">
                <div class="card-img-overlay"></div>
            </div>
            <div class="featured-card-body">
                <h3>Pure Strength Program</h3>
                <p>Built for those who want real, measurable strength. No-nonsense training that delivers results you can see and feel.</p>
                <a href="membership.html" class="join-btn">Join Now</a>
            </div>
        </div>

        <div class="featured-card">
            <div class="featured-card-img" style="background: #1a1a1a url('hamza-nouasria-t7SyUNppIeA-unsplash.jpg') center/cover no-repeat;">
                <div class="card-img-overlay"></div>
            </div>
            <div class="featured-card-body">
                <h3>Ultimate Conditioning Program</h3>
                <p>Master compound movements, build functional power, and elevate your overall physical work capacity to the max.</p>
                <a href="membership.html" class="join-btn">Join Now</a>
            </div>
        </div>

    </div>

</div><div class="wrapper">

    <div class="trainer-new-header reveal">
        <div>
            <span class="tagline">THE TEAM</span>
            <h2 class="trainer-new-heading">MEET OUR TRAINERS<br><span>DEDICATED COACHES</span></h2>
            <p class="featured-sub">Get to know the experts behind your transformation</p>
        </div>
    </div>

    <div class="trainer-new-grid reveal-stagger" id="trainerGrid">

        <div class="tncard">
            <div class="tncard-img" style="background:url('athletic-blond-male-doing-biceps-workout-with-barbell.jpg') center/cover no-repeat;"></div>
            <div class="tncard-body">
                <h4>Jabeen Mikael | <span>Bulkmaster</span></h4>
                <div class="tncard-stats">
                    <div class="tncard-stat">
                        <strong class="rating-value"><span class="stat-star">â˜…</span> 4.5</strong>
                        <small>Rating</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>2000/-</strong>
                        <small>Per Session</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>10 Yrs</strong>
                        <small>Experience</small>
                    </div>
                </div>
                <p>Jabeen Mikael is a highly experienced fitness coach with 10 years of expertise in strength training and muscle development.</p>
                <a href="membership.html" class="hire-btn">Hire Now â¯</a>
            </div>
        </div>

        <div class="tncard tncard--active">
            <div class="tncard-img" style="background:url('girl-athlete-keeps-disc-from-bar-weighting-agent-doing-crossfit-fitness-concept-sports-equipment-weight-loss.jpg') center/cover no-repeat;"></div>
            <div class="tncard-body">
                <h4>Sarah Hayat | <span>Squat Specialist</span></h4>
                <div class="tncard-stats">
                    <div class="tncard-stat">
                        <strong class="rating-value"><span class="stat-star">â˜…</span> 4.9</strong>
                        <small>Rating</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>1500/-</strong>
                        <small>Per Session</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>5 Yrs</strong>
                        <small>Experience</small>
                    </div>
                </div>
                <p>Sarah Hayat is a dedicated fitness trainer with 5 years of hands-on experience helping clients build strength, confidence and proper lifting techniques.</p>
                <a href="#" class="hire-btn">Hire Now â¯</a>
            </div>
        </div>

        <div class="tncard">
            <div class="tncard-img" style="background:url('download.jpg') center/cover no-repeat;"></div>
            <div class="tncard-body">
                <h4>Liana Deans | <span>Cardio Specialist</span></h4>
                <div class="tncard-stats">
                    <div class="tncard-stat">
                        <strong class="rating-value"><span class="stat-star">â˜…</span> 4.0</strong>
                        <small>Rating</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>5000/-</strong>
                        <small>Per Session</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>7 Yrs</strong>
                        <small>Experience</small>
                    </div>
                </div>
                <p>Liana Deans is a passionate cardio fitness specialist with 7 years of experience helping clients improve stamina, endurance and overall health.</p>
                <a href="membership.html" class="hire-btn">Hire Now â¯</a>
            </div>
        </div>

        <div class="tncard">
            <div class="tncard-img" style="background:url('GYM Coach.jpg') center/cover no-repeat;"></div>
            <div class="tncard-body">
                <h4>Elena Rostova | <span>HIIT Expert</span></h4>
                <div class="tncard-stats">
                    <div class="tncard-stat">
                        <strong class="rating-value"><span class="stat-star">â˜…</span> 4.8</strong>
                        <small>Rating</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>3500/-</strong>
                        <small>Per Session</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>8 Yrs</strong>
                        <small>Experience</small>
                    </div>
                </div>
                <p>Elena Rostova specializes in high-intensity interval training and endurance programs, helping clients achieve peak fitness performance.</p>
                <a href="membership.html" class="hire-btn">Hire Now â¯</a>
            </div>
        </div>

        <div class="tncard">
            <div class="tncard-img" style="background:url('20716.jpg') center/cover no-repeat;"></div>
            <div class="tncard-body">
                <h4>Ryan Torres | <span>Muscle Builder</span></h4>
                <div class="tncard-stats">
                    <div class="tncard-stat">
                        <strong class="rating-value"><span class="stat-star">â˜…</span> 4.7</strong>
                        <small>Rating</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>3000/-</strong>
                        <small>Per Session</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>6 Yrs</strong>
                        <small>Experience</small>
                    </div>
                </div>
                <p>Ryan Torres focuses on hypertrophy training and structured strength programs, helping clients build lean muscle efficiently.</p>
                <a href="membership.html" class="hire-btn">Hire Now â¯</a>
            </div>
        </div>

        <div class="tncard">
            <div class="tncard-img" style="background:url('female-coach.jpg') center/cover no-repeat;"></div>
            <div class="tncard-body">
                <h4>Priya Sharma | <span>Nutrition Expert</span></h4>
                <div class="tncard-stats">
                    <div class="tncard-stat">
                        <strong class="rating-value"><span class="stat-star">â˜…</span> 4.6</strong>
                        <small>Rating</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>2500/-</strong>
                        <small>Per Session</small>
                    </div>
                    <div class="tncard-stat">
                        <strong>9 Yrs</strong>
                        <small>Experience</small>
                    </div>
                </div>
                <p>Priya Sharma combines nutrition science and fitness coaching to help clients achieve sustainable body transformation and healthy lifestyles.</p>
                <a href="membership.html" class="hire-btn">Hire Now â¯</a>
            </div>
        </div>

    </div>
</div>


<div class="wrapper">

    <div class="page-header reveal" style="margin-top:20px; margin-bottom:25px;">
        <span class="tagline">Tailored Setup</span>
        <h2 class="main-heading" style="font-size:36px;">Customize Your Workout Plan</h2>
        <p class="sub-text">Build a plan that fits your body type, goal, and experience level.</p>
    </div>

    <div class="customizer-box reveal" id="plan-form-box">
       <form id="workout-form" action="get_plan.php" method="POST">
            <table class="form-table" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="25%"><label style="font-weight:bold;color:#888;text-transform:uppercase;font-size:12px;">Fitness Goal:</label></td>
                    <td>
                        <input type="radio" name="fitness_goal" id="g_loss" value="loss" checked>
                        <label for="g_loss" class="radio-label">Weight Loss</label>
                        <input type="radio" name="fitness_goal" id="g_gain" value="gain">
                        <label for="g_gain" class="radio-label">Weight Gain</label>
                        <input type="radio" name="fitness_goal" id="g_muscle" value="muscle">
                        <label for="g_muscle" class="radio-label">Muscle Building</label>
                    </td>
                </tr>
                <tr>
                    <td><label style="font-weight:bold;color:#888;text-transform:uppercase;font-size:12px;">Body Type:</label></td>
                    <td>
                        <select name="body_type" id="body_type" class="form-input" style="width:300px;">
                            <option value="ectomorph">Ectomorph</option>
                            <option value="mesomorph">Mesomorph</option>
                            <option value="endomorph">Endomorph</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label style="font-weight:bold;color:#888;text-transform:uppercase;font-size:12px;">Experience Level:</label></td>
                    <td>
                        <select name="exp_level" id="exp_level" class="form-input" style="width:300px;">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label style="font-weight:bold;color:#888;text-transform:uppercase;font-size:12px;">Target Weight (KG):</label></td>
                    <td>
                        <input type="number" name="target_weight" id="target_weight" class="form-input" style="width:300px;" placeholder="e.g. 70" min="30" max="200">
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <button type="button" onclick="generatePlan()" class="generate-plan-btn">Generate Custom Plan</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="plan-result-box" id="plan-result" style="display:none;">
        <div class="plan-result-header">
            <span class="plan-result-icon"></span>
            <h3 id="plan-title">Your Custom Plan</h3>
        </div>
        <div class="plan-calories" id="plan-calories"></div>
        <div class="plan-section">
            <h4>Your Daily Instructions</h4>
            <ul class="plan-list" id="plan-instructions"></ul>
        </div>
        <div class="plan-section">
            <h4>Recommended Foods</h4>
            <ul class="plan-list" id="plan-foods"></ul>
        </div>
        <div class="plan-section">
            <h4>Workout Routine</h4>
            <ul class="plan-list" id="plan-workouts"></ul>
        </div>
        <button onclick="resetForm()" class="action-btn" style="margin-top:25px;">â† Generate Another Plan</button>
    </div>

</div><script>
var planDB = {
    loss_ectomorph_beginner:{kcalFormula:function(w){return[Math.round(w*28),Math.round(w*32)]},instructions:["Start with 30 min brisk walks 5 days a week.","Reduce processed sugar and refined carbs completely.","Drink at least 2.5 litres of water daily.","Sleep 7-8 hours â€” poor sleep raises hunger hormones.","Track your meals in a journal or free app."],foods:["Eggs (boiled or scrambled)","Oats with skim milk","Grilled chicken breast","Green salads with olive oil","Bananas & apples","Brown rice (small portions)","Greek yogurt"],workouts:["30 min walk or light jog (5x/week)","Bodyweight squats: 3Ã—15","Push-ups: 3Ã—10","Plank holds: 3Ã—30 sec","Rest fully on weekends"]},
    loss_ectomorph_intermediate:{kcalFormula:function(w){return[Math.round(w*26),Math.round(w*30)]},instructions:["Add 2 HIIT cardio sessions per week.","Use a 300-400 kcal deficit from maintenance.","Prioritise protein at every meal.","Limit alcohol.","Re-measure every 2 weeks."],foods:["Chicken & fish (grilled)","Sweet potatoes","Lentils & chickpeas","Mixed nuts (small handful)","Berries & citrus","Egg whites","Cottage cheese"],workouts:["HIIT cardio: 25 min (3x/week)","Dumbbell circuit (2x/week)","Core work: planks, leg raises","Active rest: yoga","10,000 steps daily"]},
    loss_ectomorph_advanced:{kcalFormula:function(w){return[Math.round(w*24),Math.round(w*28)]},instructions:["Cycle calories â€” lower on rest days.","Add resistance training to prevent muscle loss.","Consider intermittent fasting (16:8).","Monitor macros: 40% protein, 35% carbs, 25% fat.","Deload every 4th week."],foods:["Lean beef & turkey","Quinoa & buckwheat","Leafy greens","Avocado (moderation)","Protein shakes post-workout","Almonds & walnuts","Salmon (2x/week)"],workouts:["Heavy compounds 4x/week","HIIT finisher: 15 min","Steady-state cardio 2x/week","Mobility & foam rolling daily","Track progressive overload"]},
    loss_mesomorph_beginner:{kcalFormula:function(w){return[Math.round(w*26),Math.round(w*30)]},instructions:["Stay consistent for 6 weeks.","Eat in a 300 kcal deficit.","Do not skip breakfast.","Avoid late-night eating after 9 PM.","Weigh yourself every Monday."],foods:["Chicken breast","Egg whites + 1 yolk","Oats & whole grain bread","Broccoli & cauliflower","Apples & pears","Low-fat dairy","Rice cakes"],workouts:["3x/week full-body resistance","2x/week 30 min jogging","Bodyweight circuit on off days","Stretch 10 min after sessions","Weekend active activity"]},
    loss_mesomorph_intermediate:{kcalFormula:function(w){return[Math.round(w*25),Math.round(w*29)]},instructions:["Split training upper/lower days.","Increase intensity via progressive overload.","Hydrate â€” 3 litres/day.","Replace refined with complex carbs.","Add one HIIT session per week."],foods:["Ground turkey","Whole eggs","Brown rice & oats","Spinach & kale","Cottage cheese","Blueberries","Hummus with veggies"],workouts:["Upper/lower split 4x/week","HIIT: 20 min (1x/week)","Core circuit 3x/week","Daily 8,000+ steps","Yoga or mobility 1x/week"]},
    loss_mesomorph_advanced:{kcalFormula:function(w){return[Math.round(w*23),Math.round(w*27)]},instructions:["Use a push/pull/legs split.","Calorie cycle around training days.","Time carbs around workouts only.","Prioritise sleep.","Consider carb cycling for plateaus."],foods:["Lean beef steak","Egg whites","Yams & sweet potatoes","Asparagus & green beans","Protein isolate","Olive oil","Grapefruit"],workouts:["PPL 6x/week","Cardio: 20 min LISS post-lifting","Abs circuit 4x/week","Deload every 4th week","HR zone training twice monthly"]},
    loss_endomorph_beginner:{kcalFormula:function(w){return[Math.round(w*22),Math.round(w*26)]},instructions:["Consistency is critical.","Start low-impact: walking, cycling, swimming.","Cut all sugary drinks and white bread.","Eat smaller meals 5x a day.","Track intake honestly for 30 days."],foods:["Grilled fish (tilapia, cod)","Egg whites","Cucumber & celery","Lentil soup","Oats (no sugar)","Green tea","Berries"],workouts:["Daily 40 min brisk walk","Cycling 3x/week (30 min)","Resistance bands 2x/week","Avoid sitting >1 hour straight","Light swimming 1x/week"]},
    loss_endomorph_intermediate:{kcalFormula:function(w){return[Math.round(w*20),Math.round(w*24)]},instructions:["Combine cardio AND weights.","Keep carbs under 150g/day.","Eat protein first at every meal.","Reduce sodium.","10 min walk after every main meal."],foods:["Chicken thighs (skinless)","Boiled eggs","Broccoli & zucchini","Black beans","Almonds (12/day)","Apple cider vinegar water","Salmon"],workouts:["Cardio: 40 min (4x/week)","Weight training: full body (3x/week)","HIIT: 2x/week 20 min","Stretch & foam roll daily","Active recovery on off days"]},
    loss_endomorph_advanced:{kcalFormula:function(w){return[Math.round(w*18),Math.round(w*22)]},instructions:["Strict macro tracking: high protein, low carb.","Intermittent fasting 16:8.","Cardio 5x/week minimum.","No cheat meals for first 8 weeks.","Monthly body composition assessment."],foods:["Tilapia & white fish","Egg whites","Leafy greens unrestricted","Casein protein at night","Green tea & black coffee","Cucumber & celery","Berries as only fruit"],workouts:["Cardio + weights 5x/week","HIIT 3x/week 25 min","Fasted morning walk 20 min","Heavy compound lifts","Weekly physique photos"]},
    gain_ectomorph_beginner:{kcalFormula:function(w){return[Math.round(w*38),Math.round(w*44)]},instructions:["Eat every 3 hours â€” do not skip meals.","Focus on calorie-dense foods.","Track calories daily.","Aim for 1.6g protein per KG.","Sleep 8 hours minimum."],foods:["Peanut butter","Whole milk","Bananas","Eggs","White rice","Chicken breast","Oats with honey"],workouts:["Full body 3x/week","Squat, bench, deadlift basics","3 sets Ã— 10-12 reps","No cardio for first 8 weeks","Rest 90 sec between sets"]},
    gain_ectomorph_intermediate:{kcalFormula:function(w){return[Math.round(w*36),Math.round(w*42)]},instructions:["Switch to a 4-day split.","Target +0.3kg/week on scale.","Increase calories by 200 if stalling.","Prioritise compound lifts.","Sleep 8-9 hours."],foods:["Mass gainer shake","Beef & chicken","Rice, pasta, potatoes","Eggs (6-8/day)","Nuts & seeds","Avocado","Whole milk dairy"],workouts:["Upper/lower split 4x/week","Progressive overload every session","8-12 rep hypertrophy range","Minimal cardio (1x/week walking)","Deload every 5th week"]},
    gain_ectomorph_advanced:{kcalFormula:function(w){return[Math.round(w*34),Math.round(w*40)]},instructions:["Lean bulk â€” 300 kcal surplus max.","Periodize: hypertrophy then strength.","2g protein per KG daily.","Recovery: sleep and massage.","Consider creatine supplementation."],foods:["Lean red meat 3x/week","Salmon & sardines","Egg variety","Protein isolate","Avocado & olive oil","All complex carb sources","Vegetables every meal"],workouts:["PPL or bro-split 5-6x/week","16-20 sets/muscle/week","Strength day once a week","Daily 15 min mobility","Monthly progress photos"]},
    gain_mesomorph_beginner:{kcalFormula:function(w){return[Math.round(w*34),Math.round(w*38)]},instructions:["Track to avoid fat gain.","250 kcal surplus from maintenance.","Eat 5 meals a day.","Protein at every meal.","Sleep 8 hours."],foods:["Chicken & beef","Rice & oats","Eggs (4-6/day)","Protein shake (1x/day)","Bananas","Sweet potatoes","Whole milk"],workouts:["Full body 3x/week","Big 4 lifts focus","3 sets Ã— 10-15 reps","Walk 30 min 2x/week","Flexibility routine weekends"]},
    gain_mesomorph_intermediate:{kcalFormula:function(w){return[Math.round(w*32),Math.round(w*36)]},instructions:["4-day upper/lower split.","Target +0.25kg/week.","Increase calories if scale stalls.","Compound movements first.","Monitor fat levels monthly."],foods:["Beef, tuna, chicken rotation","Rice & oats base","Whole eggs (4+/day)","Cottage cheese (before bed)","Almonds & walnuts","Pasta & potatoes","Protein shake 1-2x/day"],workouts:["4 days/week training","Hypertrophy: 8-15 reps","Superset isolation work","2x/week core training","1x/week mobility & stretching"]},
    gain_mesomorph_advanced:{kcalFormula:function(w){return[Math.round(w*30),Math.round(w*34)]},instructions:["Lean bulk â€” 200 kcal surplus strictly.","RPE-based training.","Periodize quarterly.","Recovery: ice baths, sleep.","Work with a coach."],foods:["Lean beef & chicken","Egg whites + yolks","Complex carb rotation","Casein protein before bed","MCT oil","All vegetables","Fruit post-workout only"],workouts:["PPL 5-6x/week","18-22 sets/muscle/week","1 strength day (1-5 reps)","Daily 15 min mobility","Quarterly physique assessments"]},
    gain_endomorph_beginner:{kcalFormula:function(w){return[Math.round(w*28),Math.round(w*32)]},instructions:["Very small surplus (100-150 kcal).","Heavy lifting from day 1.","Keep carbs moderate, protein high.","Walk 30 min daily.","Be patient."],foods:["Chicken breast & eggs","Brown rice (measured portions)","Broccoli & green vegetables","Almonds","Greek yogurt","Oats","Lean fish"],workouts:["Full body 3x/week â€” compound only","20 min cardio after lifting","No skipping training days","8,000 steps/day minimum","Sleep 8 hours"]},
    gain_endomorph_intermediate:{kcalFormula:function(w){return[Math.round(w*26),Math.round(w*30)]},instructions:["150-200 kcal surplus only.","Train 4 days/week.","Carb cycle around workouts.","Keep cardio 2-3x/week.","Monitor fat gain monthly."],foods:["Turkey & chicken","Quinoa & sweet potato","Egg whites","Whey protein shake","Cottage cheese at night","Vegetables freely","Avocado (small amount)"],workouts:["Upper/lower split 4x/week","LISS cardio 3x/week (25 min)","Abs training 3x/week","Progressive overload strictly","Deload every 5th week"]},
    gain_endomorph_advanced:{kcalFormula:function(w){return[Math.round(w*24),Math.round(w*28)]},instructions:["Body recomposition goal.","Strict carb cycling.","Protein: 2.2g per KG daily.","Track body fat % monthly.","Prioritise big compounds."],foods:["Lean beef, chicken, white fish","Egg whites primarily","Leafy greens unrestricted","Casein protein at night","MCT oil","Berries as only fruit","Bone broth for recovery"],workouts:["PPL 5x/week + fasted morning walk","LISS cardio 3x/week 30 min","Heavy compound priority","Drop sets on last exercise","Monthly performance benchmarks"]},
    muscle_ectomorph_beginner:{kcalFormula:function(w){return[Math.round(w*36),Math.round(w*42)]},instructions:["Never miss a meal â€” eat every 3 hours.","Protein goal: 2g per KG bodyweight.","Focus on big compound lifts.","Sleep 8-9 hours.","Avoid excessive cardio."],foods:["Mass gainer shake","Peanut butter","Whole milk","Eggs (6-8/day)","Rice & pasta","Chicken & beef","Oats & bananas"],workouts:["Full body 3x/week","Squat, deadlift, bench, row basics","3-4 sets Ã— 8-12 reps","Rest 2 min between sets","No cardio â€” walk only"]},
    muscle_ectomorph_intermediate:{kcalFormula:function(w){return[Math.round(w*34),Math.round(w*40)]},instructions:["Switch to upper/lower split.","Add 200 kcal per week until scale moves.","Track lifts for progressive overload.","Add creatine 5g/day.","Stretch post-session."],foods:["Beef, chicken, tuna rotation","Rice & potatoes","Eggs (full)","Mass gainer (post-workout)","Nuts & seeds","Avocado","Whole milk dairy"],workouts:["Upper/lower 4x/week","8-15 rep hypertrophy focus","Compound then isolation","1 cardio day (light walk)","Deload every 5th week"]},
    muscle_ectomorph_advanced:{kcalFormula:function(w){return[Math.round(w*32),Math.round(w*38)]},instructions:["Periodize: hypertrophy â†’ strength â†’ deload.","Lean bulk â€” 300 kcal surplus.","2.2g protein per KG.","Recovery: sleep 9h, cold therapy.","Consider creatine + beta-alanine."],foods:["Lean red meat 3x/week","Salmon 2x/week","Egg variety","Protein isolate","Avocado & olive oil","All complex carbs","Vegetables every meal"],workouts:["PPL 5-6x/week","16-20 sets/muscle/week","Strength day once weekly","Daily 15 min mobility","Monthly performance benchmarks"]},
    muscle_mesomorph_beginner:{kcalFormula:function(w){return[Math.round(w*32),Math.round(w*36)]},instructions:["Be consistent.","200-250 kcal surplus from maintenance.","Protein: 1.8g per KG daily.","Big lifts first, machines last.","Rest 60-90 sec between sets."],foods:["Chicken & beef","Eggs (4-6/day)","Brown rice & oats","Protein shake 1x/day","Sweet potatoes","Broccoli & spinach","Bananas"],workouts:["Full body 3x/week","Big 4 lifts every session","3 sets Ã— 10-15 reps","Light cardio 2x/week","Weekend mobility session"]},
    muscle_mesomorph_intermediate:{kcalFormula:function(w){return[Math.round(w*31),Math.round(w*35)]},instructions:["Switch to 4-day upper/lower or PPL split.","Compound movements first.","Target 10-20 sets per muscle/week.","Eat carbs post-workout.","Sleep 8 hours."],foods:["Beef, tuna, chicken rotation","Rice & oats base","Whole eggs (4+/day)","Cottage cheese (before bed)","Almonds & walnuts","Pasta & potatoes","Protein shake 1-2x/day"],workouts:["4 days/week training","Hypertrophy range: 8-15 reps","Superset isolation work","2x/week core training","1x/week mobility & stretching"]},
    muscle_mesomorph_advanced:{kcalFormula:function(w){return[Math.round(w*30),Math.round(w*34)]},instructions:["Periodize annually.","Use RPE to gauge intensity.","Lean bulk â€” 200-300 kcal surplus.","Recovery: ice baths, massage, 9h sleep.","Work with a coach."],foods:["Lean red meat 2x/week","Salmon 2x/week","Egg variety","Protein isolate","Avocado & olive oil","All complex carb sources","Vegetables at every meal"],workouts:["PPL or bro-split 5-6x/week","18-22 sets/muscle/week","Strength day once a week","Daily 15 min mobility","Quarterly physique assessments"]},
    muscle_endomorph_beginner:{kcalFormula:function(w){return[Math.round(w*26),Math.round(w*30)]},instructions:["Small surplus only.","Lift heavy from day 1.","Keep carbs moderate, protein high.","Walk 30 min daily.","Be patient â€” endomorphs take longer."],foods:["Chicken breast & eggs","Brown rice (measured)","Broccoli & green vegetables","Almonds","Greek yogurt","Oats","Lean fish"],workouts:["Full body 3x/week â€” compound only","20 min cardio after lifting","No skipping training days","8,000 steps/day minimum","Sleep 8 hours, no exceptions"]},
    muscle_endomorph_intermediate:{kcalFormula:function(w){return[Math.round(w*25),Math.round(w*29)]},instructions:["150-200 kcal surplus only.","4 days/week structured split.","Carb cycle around workouts.","Keep cardio 2-3x/week.","Monitor fat gain monthly."],foods:["Turkey & chicken","Quinoa & sweet potato","Egg whites","Whey protein shake","Cottage cheese at night","Vegetables freely","Avocado (small amount)"],workouts:["Upper/lower split 4x/week","LISS cardio 3x/week (25 min)","Abs training 3x/week","Progressive overload strictly","Deload every 5th week"]},
    muscle_endomorph_advanced:{kcalFormula:function(w){return[Math.round(w*24),Math.round(w*28)]},instructions:["Body recomposition.","Strict carb cycling.","Protein: 2.2g per KG daily.","Track body fat % monthly.","Prioritise big compound movements."],foods:["Lean beef, chicken, white fish","Egg whites primarily","Leafy greens unrestricted","Casein protein at night","MCT oil or coconut oil","Berries as only fruit","Bone broth for recovery"],workouts:["PPL 5x/week + fasted morning walk","Cardio: 3x/week LISS 30 min","Heavy compound priority","Drop sets on last set","Monthly performance benchmarks"]}
};
 
function generatePlan() {
    var goal = document.querySelector('input[name="fitness_goal"]:checked').value;
    var bodyType = document.getElementById('body_type').value;
    var expLevel = document.getElementById('exp_level').value;
    var targetWeight = parseFloat(document.getElementById('target_weight').value);
    
    if (!targetWeight || targetWeight < 30 || targetWeight > 200) {
        alert('Please enter a valid target weight between 30 and 200 KG.');
        return;
    }
    
    var key = goal + '_' + bodyType + '_' + expLevel;
    var plan = planDB[key];
    
    if (!plan) {
        alert('Plan layout configuration details not found.');
        return;
    }
    
    // Calculate Calories
    var kcallimits = plan.kcalFormula(targetWeight);
    document.getElementById('plan-calories').innerText = "Target Daily Intake: " + kcallimits[0] + " - " + kcallimits[1] + " kcal";
    
    // Set Plan Title 
    var titleGoal = goal === 'loss' ? 'Weight Loss' : (goal === 'gain' ? 'Weight Gain' : 'Muscle Building');
    document.getElementById('plan-title').innerText = "Your Custom " + titleGoal + " Plan (" + bodyType.toUpperCase() + ")";
    
    // Populate Dynamic Lists
    populateList('plan-instructions', plan.instructions);
    populateList('plan-foods', plan.foods);
    populateList('plan-workouts', plan.workouts);
    
    // Toggle Layout views
    document.getElementById('plan-form-box').style.display = 'none';
    document.getElementById('plan-result').style.display = 'block';
}

function populateList(elementId, itemsArray) {
    var listElement = document.getElementById(elementId);
    listElement.innerHTML = '';
    for (var i = 0; i < itemsArray.length; i++) {
        var li = document.createElement('li');
        li.innerText = itemsArray[i];
        listElement.appendChild(li);
    }
}

function resetForm() {
    document.getElementById('workout-form').reset();
    document.getElementById('plan-result').style.display = 'none';
    document.getElementById('plan-form-box').style.display = 'block';
}
</script>
<script>
(function () {

  /* 1. SCROLL-REVEAL â€” .reveal fades + slides up on scroll */
  const revealObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          revealObserver.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.12 }
  );
  document.querySelectorAll('.reveal, .reveal-stagger').forEach((el) => {
    revealObserver.observe(el);
  });

  /* 2. STAGGER CHILDREN â€” cascade delay on cards inside .reveal-stagger */
  document.querySelectorAll('.reveal-stagger').forEach((container) => {
    container.querySelectorAll('.program-box, .featured-card, .tncard').forEach((child, i) => {
      child.style.transitionDelay = (i * 0.1) + 's';
    });
  });

  /* 3. NAVBAR SCROLL SHADOW */
  const navbar = document.querySelector('.navbar');
  if (navbar) {
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 30);
    }, { passive: true });
  }

  /* 4. PROGRAM-BOX HOVER LIFT â€” subtle scale on .program-box */
  document.querySelectorAll('.program-box').forEach((box) => {
    box.style.transition = 'transform 0.25s ease, box-shadow 0.25s ease';
    box.addEventListener('mouseenter', () => {
      box.style.transform = 'translateY(-6px)';
      box.style.boxShadow = '0 12px 32px rgba(0,0,0,0.35)';
    });
    box.addEventListener('mouseleave', () => {
      box.style.transform = '';
      box.style.boxShadow = '';
    });
  });

  /* 5. GENERATE PLAN BUTTON â€” ripple effect */
  document.querySelectorAll('.generate-plan-btn, .action-btn').forEach((btn) => {
    btn.style.position = 'relative';
    btn.style.overflow = 'hidden';
    btn.addEventListener('click', function (e) {
      const old = this.querySelector('.zf-ripple');
      if (old) old.remove();
      const r = document.createElement('span');
      const rect = this.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      r.className = 'zf-ripple';
      Object.assign(r.style, {
        position: 'absolute', borderRadius: '50%',
        background: 'rgba(255,255,255,0.22)',
        width: size + 'px', height: size + 'px',
        left: (e.clientX - rect.left - size / 2) + 'px',
        top:  (e.clientY - rect.top  - size / 2) + 'px',
        transform: 'scale(0)', pointerEvents: 'none',
        animation: 'zfRipple 0.55s linear forwards'
      });
      this.appendChild(r);
      r.addEventListener('animationend', () => r.remove());
    });
  });

  /* 6. PLAN RESULT â€” smooth slide-down reveal when generated */
  const origGeneratePlan = window.generatePlan;
  if (typeof origGeneratePlan === 'function') {
    window.generatePlan = function () {
      origGeneratePlan();
      const result = document.getElementById('plan-result');
      if (result && result.style.display !== 'none') {
        result.style.opacity = '0';
        result.style.transform = 'translateY(24px)';
        result.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        requestAnimationFrame(() => setTimeout(() => {
          result.style.opacity = '1';
          result.style.transform = 'translateY(0)';
        }, 30));
        result.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    };
  }

  /* Inject shared keyframe once */
  if (!document.getElementById('zf-ripple-style')) {
    const s = document.createElement('style');
    s.id = 'zf-ripple-style';
    s.textContent = `
      @keyframes zfRipple { to { transform: scale(2.5); opacity: 0; } }
      .reveal { opacity:0; transform:translateY(32px); transition:opacity 0.65s ease, transform 0.65s ease; }
      .reveal.visible { opacity:1; transform:translateY(0); }
      .reveal-stagger { opacity:0; transform:translateY(24px); transition:opacity 0.55s ease, transform 0.55s ease; }
      .reveal-stagger.visible { opacity:1; transform:translateY(0); }
      .reveal-stagger .program-box,
      .reveal-stagger .featured-card,
      .reveal-stagger .tncard { opacity:0; transform:translateY(20px); transition:opacity 0.5s ease, transform 0.5s ease; }
      .reveal-stagger.visible .program-box,
      .reveal-stagger.visible .featured-card,
      .reveal-stagger.visible .tncard { opacity:1; transform:translateY(0); }
      .navbar.scrolled { box-shadow:0 4px 24px rgba(0,0,0,0.45); background:rgba(10,10,10,0.96); }
    `;
    document.head.appendChild(s);
  }

})();
</script>
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

</body>
</html>
