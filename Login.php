<?php

session_start();

	include("connections.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$username = $_POST['username'];
		$password = $_POST['password'];
       

        echo "User type: $type";

		if(!empty($username) && !empty($password)/* && !is_numeric($user_name)*/)
		{

			//read from database
			$query = "SELECT * from people where username = '$username' limit 1";
			$result = mysqli_query($conn, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['username'] = $user_data['username'];


                        if($user_data['type'] === 'Admin' || $user_data['type'] === 'Staff'){
                            header("Location: Staff.php");
                        }else{
						    header("Location: User.php");
                        }
						die;
					}
				}
			}
			
			echo '<script>alert("wrong username or password")</script>';
		}else{
			echo '<script>alert("wrong username or password")</script>';
		}
	}


?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body id="page">

        <div id="menu">
        <h1>YMCA</h1>
        <ul>
            <li><A href="home.php">Home</A></li>
            <li><A href="Sign-up.php">Sign-up</A></li>
        </ul>
    </div>

    <div class="content">   


            <h2> Log In </h2>
            <p>
                Input Username and password below
            </p>
            <form method="post">
                <label for="input">Username:</label>
                <input type="text" name="username" size="50">
                <br><br>
                <label for="input">Password:</label>
                <input type="Password" name="password" size="50">
                <br><br>
                <input type="submit" value="Log-In" id="saveForm" class="button">
            </form>
    </div>
</body>
</html>