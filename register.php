<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "fitnessguide";

$conn = mysqli_connect($host, $username, $password, $database);


if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}


if(isset($_POST['username']) &&
   isset($_POST['email']) &&
   isset($_POST['password'])){

    $username = mysqli_real_escape_string($conn,
    $_POST['username']);

    $email = mysqli_real_escape_string($conn,
    $_POST['email']);

    $password = mysqli_real_escape_string($conn,
    $_POST['password']);

    $query = "INSERT INTO users
    (username, email, password)

    VALUES

    ('$username', '$email', '$password')";

    if(mysqli_query($conn, $query)){

        echo "
        <script>

            alert('Registration Successful');

            window.location.href='login.html';

        </script>
        ";

    }else{

        echo "
        <script>

            alert('Registration Failed');

            window.location.href='register.html';

        </script>
        ";
    }

}else{

    echo "
    <script>

        alert('Please Fill All Fields');

        window.location.href='register.html';

    </script>
    ";
}

?>