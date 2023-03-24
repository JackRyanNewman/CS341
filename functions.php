<?php

include("connections.php");


function check_login($conn)
{

	if(isset($_SESSION['username']))
	{

		$userName = $_SESSION['username'];
		$query = "SELECT * from people where username = '$userName' LIMIT 1";

		$resultF = mysqli_query($conn,$query);
		if($resultF && mysqli_num_rows($resultF) > 0)
		{

			$user_data = mysqli_fetch_assoc($resultF);
			return $user_data;
            
		}
	}

	//redirect to login
	header("Location: login.php");
	die;

}

//Used to get data for the programs
function  progam_query($conn)
{
	$query1 = "SELECT * from program";
	$result = mysqli_query($conn, $query1);
	return $result;
}

function  sessions_query($conn)
{
	$query1 = "SELECT * from sessions";
	$result = mysqli_query($conn, $query1);
	return $result;
}

?>