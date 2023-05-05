<?php

/*  p2_home.php
/*	This page is the entry page for someone who has just opened the site, and for somone who has just logged in
/*
/*	Authors: Jackson Mishuk, Travis Wiesner, Abrar Nizam, Jack Newman
/*	Date Created: 02/23/2023
/*	Date Modified: 05/04/2023
*/

include_once("f0_connections.php");
include_once("f1_user.php");
include_once("f2_location.php");

session_start();

$user_data = checkLogin($conn);


    ?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>
    </head>
<body id="page">


        

    
    
<?php echo showMenu($user_data);?>
    


<div class="content">   

               
                <div id="Img">
                    <img src="YMCA-Logo.png" height = "20%" alt="Image of YMCA Logo">
                   
                    <header style=" font-size: 30px">
                        Welcome to the Llanfairpwllgwyngyllgogerychwyrndrobwllllantysiliogogogoch YMCA!
                    </header>
                    
                </div>
                <table>
                    <tr>
                    <td width="40%">
                    <p style=" font-size: 20px">
                        <br>
                        <br>
                     We're part of a nonprofit organization of over 2,700 Ys located in 10,000 communities 
                     across the United States dedicated to strengthening the communities that they serve. 
                     With a focus on developing the potential of youth, improving individual health and well-being, 
                     and giving back to and supporting communities, your participation brings about meaningful change not just within yourself, 
                     but also in your community.
                
                        <br>
               
                    </p>
                    </td>
                    <td style="position:fixed; right:15%; top:250px">
                    
                </div>
                    
                    <td width="50%">
                        <?php
                        if($user_data != "" && $user_data['type'] != "Admin"){
                            echo "Notifications:<br><br>";
                            $sessions = queryAllCancelledSessions($conn, $user_data['peopleID']);
                            $return = "";
                            if(mysqli_num_rows($sessions)===0){
                                echo "No new notifications!";
                            }else{
                                
                                while($row = mysqli_fetch_assoc($sessions)){
                                    if($user_data['peopleID'] == $user_data['peopleID']){
                                        $return .= " <br><br>Cancelled Sessions:<br><br>
                                        <form method='post'>
                                            ".$row['sessionName']."
                                            <input type='hidden' name='value' value='validateCancel'>
                                            <input type='hidden' name='sessionID' value='". $row['sessionID'] ."'>
                                            <input type='submit' value='Okay' />
                                        </form>";
                                    }
                                    $return .= "<br><br>";
                                }
                                echo $return;
                            }
                        }

                        if($_SERVER['REQUEST_METHOD']=="POST"){
                            if($_POST['value'] == "validateCancel"){
                                    cancelUserSession($conn, $_POST['sessionID'], $user_data['peopleID']);
                                    header("Location: p02_home.php");
                            }
                        }
                        ?>

                    </td>
        
            </tr>
            </table>
    
    
        </div>
</body>
</html>
