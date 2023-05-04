<?php

/*  f0_connections.php
/*	This file contains methods and the $conn variable that could be used on all pages.
/*
/*	Authors: Jackson Mishuk, Abrar Nizam
/*	Date Created: 02/22/2023
/*	Date Modified: 4/28/2023
*/

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "_ymca";

$conn = mysqli_connect($dbHost,$dbUser,$dbPassword,$dbName);

if(!$conn)
{
    die("failed to connect!!");
}

function checkLogin($conn){
	//Checks to see if a user is logged in the session based on if a peopleID is saved
	if(isset($_SESSION['peopleID']))
	{
		$peopleID = $_SESSION['peopleID'];
		$user_data = queryAdult($conn, $peopleID);
		return $user_data;
	}else{
		// User is not logged in, redirect to login
		
		
	}
}

function validateEmpty($field){
	//Return: boolean (0 if nothing is in the field and 1 if there is)
	if($field == ""){
		return 0;
	}
	return 1;
} 

function compareSessionCalenders($daysInt1, $startTime1, $endTime1, $startDate1, $endDate1,
				$daysInt2, $startTime2, $endTime2, $startDate2, $endDate2){
	//Compares the dates, times, and days of a session to determine if they will conflict
	//Returns: 1 if the session calenders conflict and 0 if they don't
	if($startDate1>$endDate2 || $startDate2>$endDate1){
		return 0;
	}
	if($startTime1>$endTime2 || $startTime2>$endTime1){
		return 0;
	}
	for($i = 6; $i>=0; $i--){
		$divVal = pow(10, $i);
		if(round($daysInt1/$divVal) == 1 && round($daysInt2/$divVal) == 1){
			return 0;
		}
		$daysInt1 %= $divVal;
		$daysInt2 %= $divVal;
	}
	return 1;
}

function showMenu($user_data){
	//Displays the menu on the tops of all of the pages

	$return = "<div id='menu'>";
	if(isset($user_data)){
		$return .= "<p style='text-align:left;position:fixed;'>
                   Hello ";
		$return .= $user_data['firstName'];

        $return .= "</p>";
	}
	
    $return .= "<h1>YMCA</h1>";

    $return .= showMenuButtons($user_data);
    $return .= "</div>";
	return $return;
}
function showMenuButtons($user_data){

	$currentPage = $_SERVER['REQUEST_URI'];
	

		$return = "<ul>";

		$return .= "<li><A href='User_Manual.pdf'>User Manual</A></li>";

		if(!isset($user_data)){
            $return .= loginButton($currentPage);

        }else{
                switch($user_data['type']){
                    case "User":
                    case "Member":
                        $return .= programsButton($currentPage);
                        $return .= accountButton($user_data, $currentPage);

                        break; 
                    case "Staff":
                    case "Admin":
						$return .= programsButton($currentPage);
                        $return .= peopleSearchButton($currentPage);
                        $return .= createProgramButton($currentPage);
						$return .= createSessionButton($currentPage);
                        
        		}
				$return .= signOutButton();
        }
		$return .= homeButton($currentPage);
		$return .= "</ul>";
		return $return;
}


//All of the buttons in the menu

function peopleSearchButton($currentPage){
	if($currentPage !== "/Project/p07_peopleSearch.php")
		return "<li><A href='p07_peopleSearch.php'>People Search</A></li>";
return ""; }

function createProgramButton($currentPage){
	if($currentPage !== "/Project/p04_createProgram.php")
		return "<li><A href='p04_createProgram.php?page=0'>Create Program</A></li>";
return ""; }

function createSessionButton($currentPage){
	if($currentPage !== "/Project/p04_createProgram.php")
		return "<li><A href='p04_createProgram.php?page=1'>Create Sessions</A></li>";
return ""; }

function programsButton($currentPage){
	if($currentPage !== "/Project/p05_viewProgramList.php")
		return "<li><A href='p05_viewProgramList.php'>Programs</A></li>";
return ""; }

function accountButton($user_data, $currentPage){
	if($currentPage !== "/Project/p03_account.php")
    	return "<li><A href='p03_account.php?id=" . $user_data['peopleID'] . "'>Account</A></li>";
return ""; }

function calenderButton($currentPage){
	if($currentPage !== "/Project/p09_calendar.php")
    	return "<li><A href='p09_calendar.php'>Calander</A></li>";
return ""; }

function loginButton($currentPage){
	if($currentPage !== "/Project/p00_login.php")
		return "<li><A href='p00_login.php'>Login</A></li>";
return ""; }

function homeButton($currentPage){
	if($currentPage !== "/Project/p02_home.php")
		return "<li><A href='p02_home.php'>Home</A></li>";
return ""; }

function signOutButton(){
     return "<li><A id='SignOut'>Sign out</A></li>";
}
?>