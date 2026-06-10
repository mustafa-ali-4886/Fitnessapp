<?php

session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "fitnessguide";

$conn = mysqli_connect($host, $username, $password, $database);


if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}


if(isset($_POST['email']) && isset($_POST['password'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users 
              WHERE email='$email' 
              AND password='$pass'";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){

        $_SESSION['email'] = $email;
		session_write_close();
        echo "
        <script>
            alert('Login Successful');
            window.location.href='index_AfterLogin.php';
        </script>
        ";
		exit();
    }else{

        echo "
        <script>
            alert('Invalid Email or Password');
            window.location.href='login.html';
        </script>
        ";
		exit();
    }

}else{

    echo "
    <script>
        alert('Please Fill All Fields');
        window.location.href='login.html';
    </script>
    ";
	exit();
}

?>