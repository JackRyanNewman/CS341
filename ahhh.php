<?php 
session_start(); 
include("functions.php");
include("connections.php");

//Test intinal - Creation of intinal array to test. 


$query = "SELECT * FROM `people` WHERE `username` = 'User';";
$result = mysqli_query ($conn, $query);
$user_data = mysqli_fetch_assoc($result);

$strcurrSessions = $user_data['currSessions']; //Grabbing the json file from the dataBase. 

$currSessions = json_decode($strcurrSessions); //Making the array again. 

$sizeOfArray = 0;

if(!empty($currSessions)){
    $sizeOfArray = sizeof($currSessions);
}

for($i=0; $i<$sizeOfArray; $i++){
    echo $currSessions[$i];
    $query1 = "SELECT * FROM `sessions` WHERE `sessionID` = '$currSessions[$i]';";
    $result1 = mysqli_query ($conn, $query1);
    $var[$i] = mysqli_fetch_assoc($result1);
}

?>



<html>
    <head>
    </head>
    <body>
    <form method="post">
<div>
            <?php
            for($x=0; $x<$sizeOfArray; $x++){
                echo $currSessions[$x];
                echo ($var[$x])['sessionName'];
                echo ($var[$x])['roomNumber'];
                echo ($var[$x])['spotName'];
                echo ($var[$x])['programName'];
            }
            ?>

</div>

            <br><br><br>
            
        </form>
</body>
</html>
