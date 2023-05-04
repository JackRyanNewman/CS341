<?php

/*  p5_viewProgramList.php
/*	This page allows users to see a list of all programs as well as being able to search for a program.
/*
/*	Authors: Jackson Mishuk, Jack Newman
/*	Date Created: 04/04/2023
/*	Date Modified: 4/28/2023
*/

include_once("f0_connections.php");
include_once("f1_user.php");
include_once("f2_location.php");

session_start();
$user_data = checkLogin($conn);
$query_programs = queryAllPrograms($conn);

    ?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>

    </head>
<body id="page">

<?php echo showMenu($user_data);?>

<div class="content">

    <table>
        <tr>
            <td>

                <header style="font-size: 35px;">
                &nbsp;&nbsp;<b>All Programs:</b>
                </header>
                <text style="font-size: 25px;">
                
                <?php
                
                while($program = mysqli_fetch_assoc($query_programs)){
                    echo "<br><br><A href='p06_viewProgram.php?id=". $program['programID']. "'>".$program['programName']."</A>";
                }
        ?>
            </td>
            <td style="padding-left:200px">
                <form method="post">

                    Search for a program

                    First Name: <input type="text" id="text" name="programName" height = 30px maxlength="30">  <br><br>

                                
                                
                                


                    <input type="submit" id="button" value="Search Program" />
                    <br><br>
                    <h1><u>Click program to display more details</u></h1>
                </form>
                <?php
                    if($_SERVER['REQUEST_METHOD']=="POST"){
                       
                        $listPrograms = searchLikePrograms($conn, $_POST['programName']);  
                        if($listPrograms){
                        while($row = mysqli_fetch_assoc($listPrograms)){
                            echo "<A href='p06_viewProgram.php?id=". $row['programID']. "'>".$row['programName'] . "</A><br><br>";
                        } 
                        }
                    }
                ?>
            </td>
        </tr>
    </table>
        
    </div>