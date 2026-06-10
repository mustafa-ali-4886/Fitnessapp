<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if (isset($_POST['email'])) {

    // 1. Capture and clean form inputs
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName  = htmlspecialchars(trim($_POST['lastName']));
    $userEmail = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message   = htmlspecialchars(trim($_POST['message']));

    if (empty($firstName) || empty($lastName) || empty($userEmail) || empty($message)) {
        echo "<script>alert('All fields are required.'); window.location.href='contact-After_Login.php';</script>";
        exit();
    }

    // 2. CONNECT TO DATABASE & SAVE QUERY FIRST
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "fitnessguide";

    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        die("Connection Failed: " . mysqli_connect_error());
    }

    // Sanitize values for SQL processing
    $db_firstName = mysqli_real_escape_string($conn, $firstName);
    $db_lastName  = mysqli_real_escape_string($conn, $lastName);
    $db_userEmail = mysqli_real_escape_string($conn, $userEmail);
    $db_message   = mysqli_real_escape_string($conn, $message);

    // Insert data into your newly renamed 'contact_queries' table
    $sql = "INSERT INTO contact_queries (first_name, last_name, email, message) 
            VALUES ('$db_firstName', '$db_lastName', '$db_userEmail', '$db_message')";
    
    mysqli_query($conn, $sql);
    mysqli_close($conn); // Close connection cleanly

    // 3. DISPATCH EMAIL VIA PHPMAILER
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'support.zerofitness@gmail.com'; 
        $mail->Password   = 'cedlpyaoshchjfbn'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('support.zerofitness@gmail.com', 'ZeroFitness Contact Portal');
        $mail->addAddress('support@zerofitness.com'); 
        $mail->addReplyTo($userEmail, "$firstName $lastName");

        $mail->isHTML(true);
        $mail->Subject = "New Support Query from $firstName $lastName";
        $mail->Body    = "
            <div style='font-family: sans-serif; max-width: 600px; background: #141514; color: #FFFFFF; padding: 30px; border-radius: 12px; border: 1px solid #2B2C2B;'>
                <h2 style='color: #57E201; border-bottom: 1px solid #2B2C2B; padding-bottom: 10px; margin-top: 0;'>New Contact Message Saved</h2>
                <p style='margin: 15px 0;'><strong>From:</strong> $firstName $lastName (<a href='mailto:$userEmail' style='color: #57E201;'>$userEmail</a>)</p>
                <div style='background: #1B1C1B; border: 1px solid #2B2C2B; padding: 20px; border-radius: 8px; margin-top: 20px;'>
                    <p style='color: #57E201; font-weight: bold; text-transform: uppercase; font-size: 11px; margin-top: 0; letter-spacing: 1px;'>Message:</p>
                    <p style='color: #CFDEDA; line-height: 1.6; white-space: pre-wrap; margin: 0;'>$message</p>
                </div>
            </div>
        ";

        $mail->send();
        
        echo "<script>
                alert('Your query has been logged and sent successfully!');
                window.location.href='contact-After_Login.php';
              </script>";
        exit();

    } catch (Exception $e) {
        // Even if the email script crashes locally, user knows their message was registered
        echo "<script>
                alert('Message logged to system, but email notification failed. Error: {$mail->ErrorInfo}');
                window.location.href='contact-After_Login.php';
              </script>";
    }
} else {
    header("Location: contact-After_Login.php");
    exit();
}
?>