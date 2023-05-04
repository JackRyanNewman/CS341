<?php

/*  p0_login.php
/*	This page is used to allow users, members, staff, and admin alike to login the their accounts
/*
/*	Authors: Jackson Mishuk, Abrar Nizam, Travis Wiesner
/*	Date Created: 04/14/2023
/*	Date Modified: 4/28/2023
*/


include_once("f0_connections.php");
include_once("f1_user.php");
include_once("f2_location.php");


$user_data = checkLogin($conn);

	


?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body id="page">

        <?php echo showMenu($user_data); ?>

 <div class= "bordered centred" >

 <div class="content">   
 <div class="validation">
    <?php if($_SERVER['REQUEST_METHOD'] == "POST"){
		//something was posted
			$userName = $_POST['username'];
			$password = $_POST['password'];
 
		echo validateLogin($conn,  $userName,  $password);
		}
        ?></div>

            <h2>Log In</h2>
            <p>Input Username and password below</p>
 
    </div>
            <form method="post">
                <label for="input">Username:</label>
                <input type="text" name="username" size="50">
                <br><br>
                <label for="input">Password:</label>
                <input type="Password" name="password" size="50">
                <br><br>
                <input type="submit" value="Log-In" id="saveForm" class="button">
            </form>
            <p>Dont have an account  <br> <br> click here</p>
</div>
		

		
		
		
		
    </div>
</body>
</html>