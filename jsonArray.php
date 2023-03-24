<html>
    <head>
    </head>
    <body>
    <form method="post">
<div>
            <input id="button" type="submit" value="Register"/>
</div>

            <br><br><br>
            
        </form>
</body>
</html>


<?php 
session_start(); 
include("functions.php");
include("connections.php");

//Test intinal - Creation of intinal array to test. 

if($_SERVER['REQUEST_METHOD']=="POST") //Somebullshit i have yet to understand 
    {

$currSessions = array("00001", "00002"); //Created a test array. 
$strcurrSessions = json_encode($currSessions, JSON_PRETTY_PRINT); //created Json file. 

$query = "UPDATE `program` SET `sessions` = '$strcurrSessions' WHERE `program`.`programID` = '001';";
mysqli_query ($conn, $query);





    }
?>







//Receciving an array
//2 cases 
//json file empty 
//json file has something


//Then turning array into useful data to display. 

//manupliating the array - if needed 

//packing up the array
