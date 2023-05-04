<?php

/*  f1_user.php
/*	This file contains methods that specifically modify & query data that is found in tables relating to people in the database.
/*
/*	Authors: Jackson Mishuk
/*	Date Created: 04/11/2023
/*	Date Modified: 4/28/2023
*/


//Constructors for adult

    function queryAdult($conn, $peopleID){
        //used to query an adult account using a peopleID
        //Returns: Adult array with peopleID field
        $query = "SELECT * FROM `adult` WHERE `peopleID` = '$peopleID'";
        $sqliQuery = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($sqliQuery);
    }
    function queryLogin($conn, $userName){
        //used to get an adults account info in on the login page
        //Returns: Adult array with userName field
        $query = "SELECT * FROM `adult` WHERE `userName` = '$userName'";
        $sqliQuery = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($sqliQuery);
    }
    function queryFamily($conn, $peopleID, $spouceID){
        //will use a combination of searching in the child table and using the spouce
        //Returns: the rows of the peopleIDs spouce and children
        return queryAdult($conn, $spouceID);
        
    }
    function querySimilarPeople($conn, $firstName, $lastName){
        //used when searching for people in the peopleSearch
        //Returns: the rows of the people that corrispond in first and last name
        $query = "SELECT * FROM `adult` WHERE `firstName` = '$firstName' AND `lastName` = '$lastName'";
        return  mysqli_query($conn, $query);
    }


//Constructors for junction table

    function queryAllCancelledSessions($conn, $peopleID){
        //queries all of a person's sessions that they are currently enrolled in
        //Returns: the rows of the sessions that the peopleID has signed up for
        $query = "SELECT * FROM `sessions` JOIN `sessions_adult_junct` 
        ON sessions_adult_junct.sessionID = sessions.sessionID 
        WHERE `peopleID` = '$peopleID' AND `sessionStatus` = 0";

        return  mysqli_query($conn, $query);
    }

    function queryAllCurrSessions($conn, $peopleID){
        //queries all of a person's sessions that they are currently enrolled in
        //Returns: the rows of the sessions that the peopleID has signed up for
        $query = "SELECT * FROM `sessions` JOIN `sessions_adult_junct` 
        ON sessions_adult_junct.sessionID = sessions.sessionID 
        WHERE `peopleID` = '$peopleID' AND `sessionStatus` = 1";

        return  mysqli_query($conn, $query);
    }
    function queryAllPrevSessions($conn, $peopleID){
        //queries all of a person's sessions that they are currently enrolled in
        //Returns: the rows of the sessions that the peopleID has signed up for
        $query = "SELECT * FROM `sessions` JOIN `sessions_adult_junct` 
        ON sessions_adult_junct.sessionID = sessions.sessionID 
        WHERE `peopleID` = '$peopleID' AND `sessionStatus` = 2";

        return  mysqli_query($conn, $query);
    }
    function queryCurrSession($conn, $sessionID, $peopleID){
        //looks for a specific session to see if a person is enrolled in it
        //Returns: The row of the currSession if it exists
        $query = "SELECT * FROM `sessions` JOIN `sessions_adult_junct` 
        ON sessions_adult_junct.sessionID = sessions.sessionID 
        WHERE `sessions`.`sessionID` = '$sessionID' AND `peopleID` = '$peopleID' AND `sessionStatus` = 1";

        $sqliQuery = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($sqliQuery);
    }
    function queryPrevSession($conn, $sessionID, $peopleID){
        //looks for a specific session to see if a person was enrolled in it and finished it. This is used for checking pre requesite 
        //Returns: The row of the prevSession if it exists
        $query = "SELECT * FROM `sessions` JOIN `sessions_adult_junct` 
        ON sessions_adult_junct.sessionID = sessions.sessionID 
        WHERE `sessions`.`sessionID` = '$sessionID' AND `peopleID` = '$peopleID' AND `sessionStatus` = 2";

        $sqliQuery = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($sqliQuery);
    }

#===============================================================================================

//Action & Validations

    function validateLogin($conn, $userName, $password){
        //used to see if the username and the password inputted are the match the database
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        $userData = queryLogin($conn, $userName); 
        if($userData === null){//IF: username is not in the database
            return "Username is incorrect!<br>";
        }else{
            if($userData['accountStatus'] == 0){//IF: account was deactivated
                return "This account has been deactivated!";
            }else{
                if(!checkPassword($password, $userData['password'])){//IF: password is not correct
                    return "Username or password is incorrect!<br>";
                }else{//IF: the login is successful
                    session_start();
                    $_SESSION['peopleID'] = $userData['peopleID'];
                    header("Location: p02_home.php");
                        
                }
            }
        }
    }
    function enrollSession($conn, $sessionID, $peopleID){
        //goes through the validations for enrolling for a session and then enrolls for that session
        //Returns: String (Empty if no problems. String with the errors if there is a problem)

        $enroll = "INSERT INTO `sessions_adult_junct`(`peopleID`, `sessionID`) VALUES ('$peopleID','$sessionID')";
        mysqli_query($conn, $enroll);

        $query = querySession($conn, $sessionID);
        $newCurr = $query['currCapacity'] + 1;

        $currSessChange =  "UPDATE `sessions` SET `currCapacity` = '$newCurr' WHERE `sessionID` = '$sessionID'";
        mysqli_query($conn, $currSessChange);

        return "";
    }

    /*private*/ function validatePreReq($preReqQuery, $sessionName){
        //used to make sure that someone has the pre requisite to a class before enrolling
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        if($preReqQuery === null){
            return "The Pre Requisite ". $sessionName. " is not fulfilled!<br>";
        }
        return "";
    }
    /*private*/ function validateConflict($conn, $spotID, $days, $startTime, $endTime, $startDate, $endDate, $peopleID){
        //used to make sure that there is not a conflict in a persons schedule signing up for the class
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        $validation = "";
        $sessionQuery = queryAllCurrSessions($conn, $peopleID);
        while($session = mysqli_fetch_assoc($sessionQuery)){//WHILE: $sessionQuery has rows remaining
            if(compareSessionCalenders($days, $startTime, $endTime, $startDate, $endDate,
$session['week'], $session['startTime'], $session['endTime'], $session['startDate'], $session['endTime'])){//IF: a user has a time conflict
                return "There is a conflict with another session (". $session['sessionName'] . ")!";
            }
        }
        return $validation;
    }

    /*private*/ function validateClassSize($currCapacity, $capacity){
        //makes sure that the amount of people you want is valid
        //Returns: String (Empty if no problems. String with the errors if there is a problem)
        if($currCapacity+1>$capacity){
            return "The session is full!<br>";
        }
        return "";
    }

    function cancelUserSession($conn, $sessionID, $peopleID){ 
        //PRE: the peopleID must be in the sessionID
        //changes enrollmentStatus of adult to  0 
        //Returns: void
        $query = "DELETE FROM `sessions_adult_junct` WHERE  `sessionID` = '$sessionID' AND `peopleID` = '$peopleID'";
        mysqli_query($conn, $query);

        $query = querySession($conn, $sessionID);
        $newCurr = $query['currCapacity'] - 1;

        $currSessChange =  "UPDATE `sessions` SET `currCapacity` = '$newCurr' WHERE `sessionID` = '$sessionID'";
        mysqli_query($conn, $currSessChange);

        return "";
    }

    function encrypt($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
    function checkPassword($password, $hash) {
        return password_verify($password, $hash);
    }

#===============================================================================================

//Admin
    function softDelete($conn, $peopleID){
        //PRE: user must exist and be active
        //changes accountStatus to 0
        //Returns: void
        $query = "UPDATE `adult` SET `accountStatus` = '0' WHERE `peopleID` = '$peopleID'";
        mysqli_query($conn, $query);
        
        $query1 = queryAllCurrSessions($conn, $peopleID);
        
        while($row = mysqli_fetch_assoc($query1)){//WHILE: $query1 has rows remaining
            cancelUserSession($conn, $row['sessionID'], $peopleID);
        }

    }

    function activate($conn, $peopleID){
        //PRE: user must exist and be soft deleted
        //changes accountStatus to 1
        //Returns: void
        $query = "UPDATE `adult` SET `accountStatus` = '1' WHERE `peopleID` = '$peopleID'";
        mysqli_query($conn, $query);
    }
#===============================================================================================
    
?>