<?php

include("db.php");

$goal = $_POST['goal'];
$body_type = $_POST['body_type'];
$experience = $_POST['experience'];

$sql = "SELECT * FROM workout_plans
        WHERE goal='$goal'
        AND body_type='$body_type'
        AND experience_level='$experience'";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0)
{
    $row = mysqli_fetch_assoc($result);

    echo json_encode($row);
}
else
{
    echo json_encode(array(
        "error"=>"Plan Not Found"
    ));
}

?>