<?php

session_start();

  include("connections.php");
  include("functions.php");

 $user_data = check_login($conn);

  // Data that goes into the pogram table...
    
  if($_SERVER['REQUEST_METHOD']=="POST")
    {
        // user signing up. The user name and password are being
        // saved in the superGlobals..

        $programName = $_POST['program'];
        $description = $_POST['desc'];

        $temp_Arr = array();
        $temp_json = json_encode($temp_Arr);

        $query = "INSERT INTO `program` (`programID`, `programName`, `description`, `sessions`) 
        VALUES (NULL, '$programName', '$description', '$temp_json');";

        if(mysqli_query ($conn, $query)){
            header("Location: Staff.php");
        }
    }

?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body id="page">

<div id="menu">
        <p style="text-align:left;position:fixed;">
                Hello <?php echo $user_data['name']; ?>
               </p>

    <h1>YMCA</h1>
    <ul>
        <li><A href="Staff.php">Home</A></li>
        <li><A href="home.php">Sign-out</A></li>
    </ul>
</div>

<div class="content">   
    <div id="AddProgram"> 
                    <h2>Add Program:</h2>
                    
                    <form method="post">
                        Program Name: <input type="text" id="program" name="program" maxlength="30"><br><br>
                        Description:<br><br><textarea type="text" id="desc" name="desc" maxlength="200" rows="4" cols="30"></textarea><br><br>


                        <input type="submit" id="button" value="Add Program" />

                        <br><br><br>
    
                    </form>
                </div>
            </div>
    </body>
</html>