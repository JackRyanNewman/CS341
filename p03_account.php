<?php 

/*  p3_account.php
/*	This page is used to display the id passed through the get. If someone is clicking the account button on the menu
/*  then it will show their account info, whereas going through a people search page will give you the page of that person.
/*
/*  It is used to deactivate & reactivate accounts(admin only), check on sessions that someone is in as well as allowing a user
/*  to see when their session has been cancelled.
/*
/*	Authors: Jackson Mishuk, Jack Newman
/*	Date Created: 04/04/2023
/*	Date Modified: 4/28/2023
*/

$peopleID = $_GET['id'];


include_once("f0_connections.php");
include_once("f1_user.php");
include_once("f2_location.php");

session_start();

$accountInfo = queryAdult($conn, $peopleID);

$user_data = checkLogin($conn);

if($_SERVER['REQUEST_METHOD']=="POST"){ 
    if($_POST['value'] == "deactivate"){    
       
        softDelete($conn, $_POST['peopleID']);
        header("Location: p03_account.php?id=". $peopleID);

    }else if($_POST['value'] == "reactivate"){

        activate($conn, $_POST['peopleID']);
        header("Location: p03_account.php?id=". $peopleID);

    }else if($_POST['value'] == "validateCancel"){
            cancelUserSession($conn, $_POST['sessionID'], $peopleID);
            header("Location: p03_account.php?id=". $peopleID);
    }
}
    
    ?>
<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>

    </head>
    <body id="page">

        <?php echo showMenu($user_data);?>

        <div class="content" style="font-size: 15pt">   

        <table class="backgroundStyle"><tr><td>
        <?php
            if($accountInfo !== null){
                echo accountTable($conn, $accountInfo, $user_data, $peopleID); 
            }else{
                echo "<h1>L bozo</h1>";
            }
        ?>
        </td></tr></table>
    </body>
</html>

<?php
    function accountTable($conn, $accountInfo, $user_data, $peopleID){
        $return = "<Table class='accountTable'>
            <tr>
                <td rowspan='60' class='left'>
                    <button class= 'buttonLeft' type='button'> update account  </button> <br>
                    <button class= 'buttonLeft' type='button'> update password  </button> <br>
                    <button class= 'buttonLeft' type='button'> update family account  </button><br>
                    <button class= 'buttonLeft' type='button'> delete account  </button> <br>
                </td>
                <td colspan='2' class='right'>".
                    get_user_info($conn, $accountInfo, $user_data, $peopleID)
                ."</td>
            </tr>"
             . get_family_info($conn, $accountInfo, $user_data, $peopleID) .
            
        "</Table>";
        return $return;
    }
    function get_user_info($conn, $accountInfo, $user_data, $peopleID){
        $return  = "
        
        <table class='rightAccount'>
            <tr>
                <td >
                    <h2 > 
                        <U> 
                            " . get_rank_people($accountInfo) ." : " . get_name_people($accountInfo) . " 
                        </U> 
                    </h2> 
                    <br><br>
                </td>";

                if($accountInfo['peopleID'] !== $peopleID){
                    $return .= "<td><A href='p03_account.php?id=". $accountInfo['peopleID'] ."'>View Account</A></td>";
                }
                if($user_data['type'] == "Admin" && $accountInfo['accountStatus'] == 1){
                        $return .= "<td><form method='post'>
                                        <input type='hidden' name='value' value='deactivate'>
                                        <input type='hidden' name='peopleID' value='". $accountInfo['peopleID'] ."'>
                                        <input type='submit' style='background-color: light-gray;' value='Soft Delete User' />
                                    </form></td>";
                }
                if($user_data['type'] == "Admin" && $accountInfo['accountStatus'] == 0){
                    $return .= "<td><form method='post'>
                                    <input type='hidden' name='value' value='reactivate'>
                                    <input type='hidden' name='peopleID' value='". $accountInfo['peopleID'] ."'>
                                    <input type='submit' style='background-color: light-gray;' value='Reactivate User' />
                                </form></td>";
            }
                if($accountInfo['accountStatus'] == 0){
                    $return .= "<td class='validation'>ACCOUNT DEACTIVATED</td>";
                }
                
        return $return . "</tr>
            <tr>
                <td>
                    Username: " . get_username_people($accountInfo). 
                    "<br><br>Date of Birth: " . get_dob_people($accountInfo) . 
                    "<br><br>Age: " . get_age_people($accountInfo) .                 "</td>
                
                <td> 
                    Sex: " . get_sex_people($accountInfo) . " 
                    <br><br>Address: " . get_address_people($accountInfo) ."
                    <br><br>Email: " . get_email_people($accountInfo) .
                "</td>
            </tr>
            <tr>
                <td>"
                . get_curr_sessions($conn, $accountInfo) .  

                "</td>
                <td>"
                    . get_cancelled_sessions($conn, $accountInfo, $user_data) . 
                "</td>
            </tr>
        </table>
        
        
        <br><br> <br><br> <br><br>
        ";
    }

    function get_family_info($conn, $accountInfo, $user_data, $peopleID){
        
        if($accountInfo['spouseID'] == 0){
            return "<tr>
                        <td>
                           <h1> No Family </h1>
                        </td>
                    </tr>";
        }else{
            $spouce = queryAdult($conn, $accountInfo['spouseID']);

            return "<tr>
                        <td>
                            <h2 ><pre>  <U>Family Members:</U></pre></h2>
                        </td>
                    </tr>
                    <tr>
                        <td class='right'>"
                        . get_user_info($conn, $spouce, $user_data, $peopleID).
                         
                        "</td>
                        <td class='right'>
                        
                        </td>
                    </tr>";
                
        }
       
    }

    function get_name_people($accountInfo){
        return $accountInfo['firstName'] . " " . $accountInfo['lastName'];
     }
     
     function get_username_people($accountInfo){
         return $accountInfo['userName'];
     }
     
     function get_dob_people($accountInfo){
         return date('m/d/Y', strtotime($accountInfo['DOB']));
     }
     
     function get_age_people($accountInfo){
        
        $userYear = date('Y', strtotime($accountInfo['DOB']));
        $userMonth = date('m', strtotime($accountInfo['DOB']));
        $userDay = date('d', strtotime($accountInfo['DOB']));

        $duration = "-". $userYear . " Years -". $userMonth . "Months -". $userDay . " Days";
        $timestamp = strtotime($duration, time());
        return date('o', $timestamp);
     
     }
     
     function get_sex_people($accountInfo){
         return $accountInfo['sex'];
     }
     
     function get_address_people($accountInfo){
         return $accountInfo['address'];
     }
     
     function get_email_people($accountInfo){
         return $accountInfo['email'];
     }
     
     function get_rank_people($accountInfo){
         return $accountInfo['type'];
     }

     function get_curr_sessions($conn, $accountInfo){
        $sessions = queryAllCurrSessions($conn, $accountInfo['peopleID']);
        $return = "Current Sessions:<br><br>";
        if(mysqli_num_rows($sessions)===0){
            return $return . "<div class='validation'> No Current Sessions! </div>";
        }else{
            
            while($row = mysqli_fetch_assoc($sessions)){

                $return .= $row['sessionName'] . " | ". 
date("m/d/Y", strtotime($row['startDate'])) . " -- ".  date("m/d/Y", strtotime($row['endDate'])) . " | " 
. date("h:i A", strtotime($row['startTime'])) . " -- " . date("h:i A", strtotime($row['endTime'])) . " | " 
. decodeDays($row['week'])."<br>";
            }
            return $return;
        }
    }

    function get_cancelled_sessions($conn, $accountInfo, $user_data){
        $sessions = queryAllCancelledSessions($conn, $accountInfo['peopleID']);
        $return = "";
        if(mysqli_num_rows($sessions)===0){
            return $return;
        }else{
            
            while($row = mysqli_fetch_assoc($sessions)){
                if($user_data['peopleID'] == $accountInfo['peopleID']){
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
            return $return;
        }
    }
     
?>

<html>
    <style>

        .backgroundStyle{
            background-color: rgba(35, 64, 153, .50);
            width: 100%
        }

        table.accountTable{
            margin:25px;
            width: 95%;
        }

        td.right{
            width:40%;
            height: 35%
        }

        td.left{
            height: 100%;
            width: 10%; 
            align-items: start;
            background-color: rgb(203, 204, 204);
        }
        .buttonLeft{
            width: 100%;
            background: none;
            display: inline-block;
            text-align: left;
            font-size: large;
            border: none;
            border-bottom: 2px solid black; 
            color: black; 

            

            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; 
        }

        .buttonLeft:hover {
            background-color: rgb(186, 186, 186);
             color: black;
        }

        table.rightAccount{

            padding-top: 20px;
            margin-left: 20px;
            padding-left: 20px;
            height: 100%;
            width: 100%; 
            background-color: rgb(203, 204, 204);
        }
    </style>
</html>
   