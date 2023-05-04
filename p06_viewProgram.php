<?php

/*  p6_viewProgram.php
/*	This page allows users to see a list of all sessions within the program(passed in the get) as well as allowing for
/*  enrollment, unenrollment, and canceling of sessions(Admin only).
/*
/*	Authors: Jackson Mishuk
/*	Date Created: 04/04/2023
/*	Date Modified: 4/28/2023
*/

$program_id = $_GET['id'];

include_once("f0_connections.php");
include_once("f1_user.php");
include_once("f2_location.php");

session_start();
$user_data = checkLogin($conn);

$program_data = queryProgram($conn, $program_id);

if($_SERVER['REQUEST_METHOD']=="POST") {
    if($_POST['value'] == "enroll"){
        echo enrollSession($conn, $_POST['sessionID'], $user_data['peopleID']);
    }else if($_POST['value'] == "unenroll"){
        echo cancelUserSession($conn, $_POST['sessionID'], $user_data['peopleID']);
    }else if($_POST['value'] == "cancel"){
        echo cancelSession($conn, $_POST['sessionID']);
    }

    header("Location: p06_viewProgram.php?id=". $program_id);
}
    ?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>

    </head>
<body id="page">

<?php echo showMenu($user_data);?>

    <div class="content">
<?php
if(!isset($user_data)){

    echo show_data_program($conn, $program_data, $user_data, 0);

}else if($user_data['type']==="User"){

    echo show_data_program($conn, $program_data, $user_data, 1);

}else if($user_data['type']==="Member"){

    echo show_data_program($conn, $program_data, $user_data, 2);

}else if($user_data['type']==="Staff"){

        echo show_data_program($conn, $program_data, $user_data, 3);
}else if($user_data['type']==="Admin"){

    echo show_data_program($conn, $program_data, $user_data, 4);
}






function show_data_program($conn, $program_data, $user_data, $type){//Type is baised on the user accessing the page 0(Home), 1(NMem), 2(Mem), 3(Staff), 4(Admin)
	
		
    $return = "<br><br><h2>" .$program_data['programName']. "</h2>Description: " . $program_data['description'];
 
    $sessions = queryProgramSessions($conn, $program_data['programID']);

    $j=0;

    $return .= "<Table style='font-size:11pt; width:100%'>";
        while($row = mysqli_fetch_assoc($sessions)) { //foreach element in $arr
            if($j == 0){
                echo "<tr>";
            }
            if($row['sessionStatus'] == 2){
                continue;
            }

            if($row['sessionStatus'] == 0 && $type!=4 ){
                continue;
            }
            $room = queryRoom($conn, $row['roomID']);

            $preReqName = "";
            if($row['preReq'] == 0){
                $preReqName = "No Pre Requisite!";
            }else{
                $preReqName = querySession($conn, $row['preReq'])['sessionName'];
            }
            $return .= "<br><br>";
            
            $return .= "<td style:'width: 25%;'>
            ".($row['sessionName']).": ";
            if($row['sessionStatus'] == 0){
                $return .= "<div class='validation'>Cancelled</div>";
            }
            
            $return .= "<br>
            Description: ".($row['description']).
            "<br><br>
            Room: ". $room['roomNum'] ./*" <br>Spot: " . $spot_data['spotName'] .*/ "
            <br>
            Days: " . decodeDays($row['week']) . " 
            <br>
            Time: ".  date("h:i A", strtotime($row['startTime'])) . " -- " . date("h:i A", strtotime($row['endTime'])).
            "<br>
            Dates: ". date("m/d/Y", strtotime($row['startDate'])) . " -- " . date("m/d/Y", strtotime($row['endDate'])) ."<br>";
            
            if($row['sessionStatus'] != 0){
                $return .="Capacity: " . $row['currCapacity'] . "/" .$row['capacity']."<br> ";
            }
            $return .= "Level required: ".$preReqName."
            <br>";
            if(!($type===1)){
                $return .= "Member Pricing: $".$row['feeMem']."<br>";
            }
            if(!($type===2)){
                $return .= "Nonmember Pricing: $".$row['feeNonMem']."
                <br>";
            }

            $inSession = queryCurrSession($conn, $row['sessionID'], $user_data['peopleID']);

            $return .= "<Form method='post'>";
            if($type!==4 && $row['sessionStatus'] != 0){
                if($inSession != NULL){
                    $return .= "<input type='hidden' name='value' value='unenroll'>
                    <input type='hidden' name='sessionID' value='". $row['sessionID'] ."'>
                    <input type='submit' value='unenroll' />
                    </form>";
                }else{
                    if($row['currCapacity'] >= $row['capacity']){
                        $return .= "<div class='validation'>This session is full</div>";
                    }else{
                        if($row['preReq'] != 0 && queryPrevSession($conn, $row['preReq'], $user_data['peopleID']) == null){
                            $return .= "<div class='validation'>You do not satisfy the prerequisite requirement</div>";
                        }else{
                            $confliction = 0;

                            $sessionQuery = queryAllCurrSessions($conn, $user_data['peopleID']);

                            while(($session = mysqli_fetch_assoc($sessionQuery)) && $confliction == 0){

                                if(compareSessionCalenders($row['week'], $row['startTime'], $row['endTime'], $row['startDate'], $row['endTime'],
                                    $session['week'], $session['startTime'], $session['endTime'], $session['startDate'], $session['endTime'])){

                                    $confliction = 1;
                                    if($confliction == 1){
                                        $return .= "<div class='validation'>You do not satisfy the prerequisite requirement</div>";
                                    }
                                }
                            }
                            if($confliction == 0){
                                $return .= "<input type='hidden' name='value' value='enroll'>
                                <input type='hidden' name='sessionID' value='". $row['sessionID'] ."'>
                                <input type='submit' value='enroll' />
                                </form>";

                            }
                        }
                    }
                }
            }
            if(($type==4 || $type==3) && $row['sessionStatus'] != 0){
                $return .= "    <input type='hidden' name='value' value='cancel'>
                                <input type='hidden' name='sessionID' value='". $row['sessionID'] ."'>
                                <input type='submit' value='Cancel Program' />
                            </Form>";
            }
        }
        return $return;
    }
    ?>

