<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$host = "localhost";
$username = "root";
$password = "";
$database = "fitnessguide";

$conn = mysqli_connect($host, $username, $password, $database);

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

if(isset($_POST['email'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){

        $otp = rand(100000, 999999);
        
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['otp_expiry'] = time() + 300;

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
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your ZeroFitness Password Reset OTP';
            $mail->Body    = "<h3>Welcome Back</h3>
                              <p>You requested a password reset. Use the verification code below to proceed. This code is valid for 5 minutes.</p>
                              <h2 style='color:#57E201; letter-spacing:5px;'>$otp</h2>";

            $mail->send();
            
            mysqli_close($conn);
            echo "
            <script>
            alert('OTP code sent successfully to your email.');
            window.location.href='verify_otp.php';
            </script>
            ";
            exit();

        } catch (Exception $e) {
            echo "
            <script>
            alert('Mail delivery failed. Check your SMTP configuration settings.');
            window.location.href='forgetpassword.html';
            </script>
            ";
        }

    }else{
        echo "
        <script>
        alert('Email Not Found');
        window.location.href='forgetpassword.html';
        </script>
        ";
    }

}else{
    echo "
    <script>
    alert('Please Enter Email');
    window.location.href='forgetpassword.html';
    </script>
    ";
}
?>