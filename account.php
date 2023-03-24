<?php
session_start();
include("connections.php");
include("functions.php");

$user_data = check_login($conn);

$currSessions_json = $user_data['currSessions'];
$currSessions_arr = json_decode($currSessions_json);


    
    ?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body id="page">

<div id="menu">
        <p style="text-align:left;position:fixed;">
                Hello <?php echo $user_data['name']; ?>
               </p>

    <h1>YMCA</h1>
    <ul>
        <li><A href="User.php">Home</A></li>
        <li><A href="register.php">Register</A></li>
        <li><A href="home.php">Sign-out</A></li>
    </ul>
</div>

<div class="content" style="font-size: 28pt">   

Sessions You're a part of:

       <?php
       for($item=0;$item<sizeof($currSessions_arr); $item++) { //foreach element in $arr
        $tempy1 = $currSessions_arr[$item];
        $sessions_query = "SELECT * FROM sessions WHERE sessionID = '$tempy1';";
        $resultSessionQuery = mysqli_query($conn,$sessions_query);
        $session_data = mysqli_fetch_assoc($resultSessionQuery);

        echo $session_data['sessionName']; echo " ";
    }    
       ?>        
</div>
</body>
</html>