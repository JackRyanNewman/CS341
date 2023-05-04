<?php

/*  p7_peopleSearch.php
/*	This page allows staff and admin to be able to search for a persons account by name.
/*
/*	Authors: Jack Newman, Jackson Mishuk
/*	Date Created: 04/02/2023
/*	Date Modified: 4/28/2023
*/

include_once("f0_connections.php");
include_once("f1_user.php");
include_once("f2_location.php");

session_start();
$user_data = checkLogin($conn);

// Data that goes into the pogram table...


?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>

    </head>
<body id="page">

<?php echo showMenu($user_data);?>



<div class="content">   
  <div id="userSearch"> 
                  <h2>Find user</h2>
                  
                  <form method="post">
                      First Name: <input type="text" id="text" name="firstName" height = 30px maxlength="30">  <br><br>
                      Last Name: <input type="text" id="text" name="lastName" maxlength="30">   <br><br>
                      
                      
                     


                      <input type="submit" id="button" value="Search User" />
            <br><br>
                <h1><u>Click user to display more details</u></h1>
                  </form>
              </div>
          </div>
          
              <?php
              //Making a post that javascript listens to. 
              if($_SERVER['REQUEST_METHOD']=="POST"){
                $first_name = $_POST['firstName'];
                $last_name = $_POST['lastName'];  
                $list_of_people = querySimilarPeople($conn, $first_name, $last_name);  
                if($list_of_people){
                  while($row = mysqli_fetch_assoc($list_of_people)){
                    $return =  "<A href='p03_account.php?id=". $row['peopleID']. "'>Name: "
                    .$row['firstName']. " " . $row['lastName']. " | Date of Birth: ". 
                    date('m/d/Y', strtotime($row['DOB'])) . " | Username: ".$row['userName']. " ";
                    if($row['accountStatus'] == 0){
                        $return .= "| <div class='validation'>DEACTIVATED</div>";
                    } 
                    echo $return . "</A><br><br>";
                  } 
                }
              }
              ?>

  </body>
</html>



<html>
    <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    </head>







</html>