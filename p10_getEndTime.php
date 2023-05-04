<?php 

/*  p10_getEndTime.php
/*	Used to get data for the session creation form based on updates in the form
/*
/*	Authors: Jackson Mishuk
/*	Date Created: 04/20/2023
/*	Date Modified: 4/28/2023
*/

include_once("f0_connections.php");
include_once("f1_user.php");
include_once("f2_location.php");

$page = $_REQUEST["calc"];
$start = $_REQUEST["s"];
$durration = $_REQUEST["d"];

if($page == "time"){

    $endTime = date("h:i A", strtotime("+". $durration . " minutes", strtotime($start)));
    echo $endTime;

}else if($page == "date"){

    $endDate = date("m/d/Y", strtotime("-1 days", strtotime("+". $durration . " weeks", strtotime($start))));
    echo $endDate;

}else if($page == "preReq"){
    //start will be used to store the programID selected 

    $sessions = queryPrevProgramSessions($conn, $start); // Replace with your function to retrieve the options

    $return = "Prerequisite Course: 
                                
    <select id='prerequisiteID' name='prerequisiteID' size='1'>
    <option value='None'>No Prerequisite</option>"; 

    while($row = mysqli_fetch_assoc($sessions)){
        if(isset($row['sessionID'])) {
            $return .= "<option value='" . $row['sessionID'] . "' id='sessionID'>" 
                . $row['sessionName'] . "</option>"; 
            } 
        }
        $return .= "</select><br>";

        echo $return;

}else if($page == "spot"){
    //start will be used to store the roomID selected 
    // Retrieve the options for the second dropdown based on the selected value
    $spots = queryAllSpots($conn, $start); // Replace with your function to retrieve the options

    $return = " Spot: 
                        
    <select name='spotID' size='1'>
    <option value=''>Select A Spot...</option>"; 

    while($row = mysqli_fetch_assoc($spots)){
        if(isset($row['spotID'])) {
            $return .= "<option value='" . $row['spotID'] . "'>" 
                . $row['spotName'] . "</option>"; 
        } 
    }
    $return .= "</select><br><br>";

    echo $return;
}
?>