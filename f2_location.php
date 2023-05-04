<?php

/*  f2_location.php
/*	This file contains methods that specifically modify & query data that is found in 
/*  tables relating to rooms, spots, programs and sessions in the database.
/*
/*	Authors: Jackson Mishuk
/*	Date Created: 04/11/2023
/*	Date Modified: 4/28/2023
*/

#_______________________________________________________________________________________________
#Room
//Constructors
    function queryRoom($conn, $roomID){
        //uses a roomID to get the information for that room
        //Returns: the row of the roomID
        $query = "SELECT * FROM `rooms` WHERE `roomID` = '$roomID'";
        $sqliQuery = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($sqliQuery);
    }

    function queryAllRooms($conn){
        //grabs all rooms. Used for creating sessions
        //Returns: all rows in rooms
        $query = "SELECT * FROM `rooms`";
        return mysqli_query($conn, $query);
    }
#_______________________________________________________________________________________________
#Spots
//Constructor
    function querySpot($conn, $spotID){
        //uses a spotID to get the information for that spot
        //Returns: the row of the spotID
        $query = "SELECT * FROM `spots` WHERE `spotID` = '$spotID'";
        $sqliQuery = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($sqliQuery);
    }
    function queryAllSpots($conn, $roomID){
        //grabs all spots in a room. Used for creating sessions
        //Returns: all rows in the spots table that are in the roomID
        $query = "SELECT * FROM `spots`";
        return mysqli_query($conn, $query);
    }
#_______________________________________________________________________________________________
#Programs
//Constructors
    function queryProgram($conn, $programID){
        //uses a programID to get the information for that program
        //Return: returns the row of the programID
        $query = "SELECT * FROM `programs` WHERE `programID` = '$programID'";
        $sqliQuery = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($sqliQuery);
    }
    function searchProgram($conn, $programName){
        $query = "SELECT * FROM `programs` WHERE `programName` = '$programName'";
        $sqliQuery = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($sqliQuery);
    }

    function searchLikePrograms($conn, $programName){
        $query = "SELECT * FROM `programs` WHERE `programName` LIKE '". $programName ."%'";
        return mysqli_query($conn, $query);
    }

     function queryAllPrograms($conn){
        //gets all of the progarams that exists. Used for creating sessions and programValidation
        //Returns: all rows in program
        $query = "SELECT * FROM `programs`";
        return mysqli_query($conn, $query);
    }
#===============================================================================================
//Action & Validations

    function searchPrograms($conn, $programName){
        //used to search for a program using a programName to find the corrisponding program. Used in program search
        //Return: returns the row of the programName
        $query = "SELECT * FROM `programs` WHERE `programName` = '$programName'";
        $sqliQuery = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($sqliQuery);
    }
    function createProgram($conn, $programName, $programDesc){
        //used to create a program
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        $validation = validateProgramName($conn, $programName).validateProgramDescription($conn, $programDesc);
        if($validation !== ""){
            return $validation;
        }
        $query = "INSERT INTO `programs`(`programName`, `programStatus`, `description`) 
        VALUES ('$programName','1','$programDesc')";
        mysqli_query($conn, $query);
        return "";

    }
    /*private*/ function validateProgramName($conn, $programName){ 
        //used to make sure that the programName field is not empty and that it does not have the same name as an existing one
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        $program = searchProgram($conn, $programName);

        if($program !== null || !validateEmpty($programName)){
            return "The program name you listed is either empty or already exists. Try again!<br>";
        }
        return "";
    }
    /*private*/ function validateProgramDescription($conn, $programDesc){
        //used to make sure that the programDesc field is not empty
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        if(!validateEmpty($programDesc)){
            return "The program description field is empty!<br>";
        }
        return "";
    }

#_______________________________________________________________________________________________
#Sessions
//Constructors
    function querySession($conn, $sessionID){
        //uses a sessionID to query information from the corrisponding session
        //Returns: a row of information from the sessions table
        $query = "SELECT * FROM `sessions` WHERE `sessionID` = '$sessionID'";
        $sqliQuery = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($sqliQuery);
    }
    function queryProgramSessions($conn, $programID){
        //uses a programID to query information from the corrisponding current sessions that are in the program
        //Returns: rows of information from the sessions table(based on how many are in the program)
        $query = "SELECT * FROM `sessions` WHERE `programID` = '$programID'";
        return mysqli_query($conn, $query);
    }

    function queryPrevProgramSessions($conn, $programID){
        //uses a programID to query information from the corrisponding current sessions that are in the program
        //Returns: rows of information from the sessions table(based on how many are in the program)
        $query = "SELECT * FROM `sessions` WHERE `programID` = '$programID' AND `sessionStatus` = 2";
        return mysqli_query($conn, $query);
    }

    function queryRelatedSessionsFromSpot($conn, $spotID){
        //uses a spotID to find all of the current sessions that are to be held in that spot
        //Returns: rows of information from the sessions table(based on how many are in the spot)
        $query = "SELECT * FROM `sessions` WHERE `spotID` = '$spotID'";
        return mysqli_query($conn, $query);
    }
    function queryPartcipants($conn, $sessionID){
        //uses a sessionID to find all of the participants in that session
        //Returns: rows of information from the people table(based on who is in the session)
        $query = "SELECT * FROM `adult` JOIN `sessions_adult_junct` 
        ON adult.peopleID = sessions_adult_junct.peopleID WHERE `sessionID` = '$sessionID'";
        return  mysqli_query($conn, $query);
    }

#===============================================================================================
//Action & Validations
    function createSession($conn, $sessionName, $roomID, $spotID, $programID, $capacity, $sessionDesc, 
    $startDate, $durrationWeeks, $feeM, $feeNM, $preReq, $daysArr, $startTime, $durrationMinutes){
        //used to create a session
        //Returns: String (Empty if no problems. String with the errors if there is a problem)

        $days = encodeDays($daysArr);

        $validation = validateSessionCreation($conn, $sessionName, $spotID, $capacity, $sessionDesc, 
        $startDate, $durrationWeeks, $feeM, $feeNM, $days, $startTime, $durrationMinutes);

        $endDate = date("Y-m-d", strtotime("-1 days", strtotime("+". $durrationWeeks . " weeks", strtotime($startDate))));

        if($validation != ""){//IF: no error message returned
            return $validation;
        }

        $endTime = date("H:i:s", strtotime("+". $durrationMinutes . " minutes", strtotime($startTime)));

        if($preReq == "None"){
            $preReq = null;
        }

        $validation .= validateSessionConflict($conn, $spotID, $days, $startTime, $endTime, $startDate, $endDate);

        if($validation != ""){//IF: no error message returned
            return $validation;
        }
        $query = "INSERT 
        INTO `sessions`(`sessionName`, `sessionStatus`, `roomID`, `spotID`, `programID`, `currCapacity`, `capacity`, `description`, 
        `startDate`, `endDate`, `feeMem`, `feeNonMem`, `preReq`, `minAge`, `maxAge`, `week`, `startTime`, `endTime`) 

        VALUES ('$sessionName','1','$roomID','$spotID','$programID','0','$capacity','$sessionDesc',
        '$startDate','$endDate','$feeM','$feeNM','$preReq','6','99','$days','$startTime','$endTime')";

        mysqli_query($conn, $query);

        return "";

    }
    function validateSessionCreation($conn, $sessionName, $spotID, $capacity, $sessionDesc, 
    $startDate, $durrationWeeks, $feeM, $feeNM, $days, $startTime, $durrationMinutes){
        //used to make sure that the session being created is valid
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        return validateSessionName($sessionName). validateCapcity($capacity).
        validateSessionDescription($sessionDesc). validateDates($startDate, $durrationWeeks).
        validateCosts($feeNM, $feeM). validateDays($days). validateTimes($startTime, $durrationMinutes);
    }

    function validateSessionName($sessionName){
        //used to make sure that the sessionName field is not empty
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        if(!validateEmpty($sessionName)){
            //if its empty
            return "The Session Name field is empty!<br>";
        }
        return "";
    }

    function validateSessionConflict($conn, $spotID, $days, $startTime, $endTime, $startDate, $endDate){ 
        //used to make sure that the spot (at spotID) does not have another session that conflicts
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        $validation = "";
        $sessionQuery = queryRelatedSessionsFromSpot($conn, $spotID);
        while($session = mysqli_fetch_assoc($sessionQuery)){ //WHILE: there are more rows in $sessionQuery 
            if(compareSessionCalenders($days, $startTime, $endTime, $startDate, $endDate,
$session['week'], $session['startTime'], $session['endTime'], $session['startDate'], $session['endTime'])){ //IF: there is no location conflict
                return "There is a conflict with another session (". $session['sessionName'] . ")in the spot ". $spotID. "!";
            }
        }
        return $validation;
    }

    function validateCapcity($capacity){
        //used to make sure that the capacity field is not empty and > 0
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        if(!validateEmpty($capacity)||$capacity<=0){
            return "The capacity field is either not a valid number, or is empty!<br>";
        }
        return "";
    }
    function validateSessionDescription($sessionDesc){
        //used to make sure that the description field is not empty
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        if(!validateEmpty($sessionDesc)){
            //if its empty
            return "The Session Description field is empty!<br>";
        }
        return "";
    }
    function validateDates($startDate, $durrationWeeks){
        //used to make sure that the startDate occurs on or after the current day and that the durrationWeeks field is not empty and is a natural number
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        $currDate = date("Y-m-d");
        /*if($currDate > $startDate){//IF: start date is before current day
            return "This program can not start in the past!";
        }else*/ if(!validateEmpty($durrationWeeks) || $durrationWeeks <= 0){
            return "The durration in weeks field is either not a valid number, or is empty!<br>";
        }
        return "";

    }
    

    function validateCosts($feeNM, $feeM){
        //used to make sure that the feeM field is <= feeNM and that they are both not empty
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        if(!validateEmpty($feeNM)){
            return "The non-member cost field is empty!";
        }else if(!validateEmpty($feeM)){
            return "The member cost field is empty!";
        }else if($feeNM < $feeM){//IF: member fee is greater than non-member fee
            return "The member cost cannot be more the the non-member cost!";
        }
        return "";

    }
    function validateDays($days){
        //used to make sure that the daysArr field is not empty
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        if($days == 0000000){//IF: no days selected
            return "No days were selected!<br>";
        }
        return "";
    }

    function validateTimes($startTime, $durrationMinutes){ 
        //used to make sure that the startTime(minutes into day) + durrationMinutes <= 1440 minute(amt of minutes in day)
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        if(!validateEmpty($startTime)){
            return "The start time field is empty!";
        }else if(!validateEmpty($durrationMinutes) || $durrationMinutes<=0 || $durrationMinutes>=1440){//IF: empty, <= 0, or the durration is longer than 24 hours(1440 minutes)
            return "The durrationminutes field is either empty or is not a valid number!";
        }else {
            $endTime = date("h:i", strtotime("+". $durrationMinutes . " minutes", strtotime($startTime)));
            if(date("h" , strtotime($endTime))<date("h" , strtotime($startTime))){//IF: the session starts on one day and ends on another
                return "An event can not start on one day and end on another";
            }
        }
        return "";
    }

    function cancelSession($conn, $sessionID){ 
        //PRE: session must exist and not already be cancelled
        //Changes sessionStatus to 0
        $query = "UPDATE `sessions` SET `sessionStatus` = '0' WHERE `sessionID` = $sessionID";
        mysqli_query($conn, $query);
    }

    

    function encodeDays($daysArr){
        //Changes the form submission days array to the int stored in the database
        //Returns: int where each day corresponds with a day of the week Mon-Sun where the digit is 1 if there is an event on that day
        
        $daysInt = 0;
        foreach($daysArr as $row){
            if($row == "Monday"){
                $daysInt += 1000000;
            }
        
            if($row == "Tuesday"){
                $daysInt += 100000;
            }
        
            if($row == "Wednesday"){
                $daysInt += 10000;
            }
        
            if($row == "Thursday"){
                $daysInt += 1000;
            }
        
            if($row == "Friday"){
                $daysInt += 100;
            }
        
            if($row == "Saturday"){
                $daysInt += 10;
            }
        
            if($row == "Sunday"){
                $daysInt += 1;
            }

        }
        return $daysInt;
    }

    function decodeDays($daysInt){
        //Turns the string of 0s and 1s to a string of the days
        //Returns: A string of days that the $daysInt represents
        $return = "";
        for($i = 6; $i>=0; $i--){
            $value = pow(10, $i);
            if(floor($daysInt / $value) == 1){
                if($i == 6){
                    $return .= "Mon, ";
                }else if($i == 5){
                    $return .= "Tues, ";
                }else if($i == 4){
                    $return .= "Wed, ";
                }else if($i == 3){
                    $return .= "Thurs, ";
                }else if($i == 2){
                    $return .= "Fri, ";
                }else if($i == 1){
                    $return .= "Sat, ";
                }else if($i == 0){
                    $return .= "Sun";
                }
            }
            $daysInt = $daysInt % $value;
        }
        return $return;
    }
#===============================================================================================

?>