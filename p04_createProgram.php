<?php

/*  p4_createProgram.php
/*	This page allows staff and admin to create either a session or a program based on entry point.
/*
/*	Authors: Jackson Mishuk, Abrar Nizam, Travis Wiesner
/*	Date Created: 03/27/2023
/*	Date Modified: 4/28/2023
*/

$pagePopup = $_GET['page'];

include_once("f0_connections.php");
include_once("f1_user.php");
include_once("f2_location.php");

session_start();
$user_data = checkLogin($conn);

  // Data that goes into the pogram table...
    
  

?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>

    </head>
    <body id="page">

    <?php echo showMenu($user_data);?>

        
            <div class="content">   
                
                        
            <div class="validation">
                    <?php
                            if($_SERVER['REQUEST_METHOD']=="POST"){
                                // user signing up. The user name and password are being
                                // saved in the superGlobals..
                                if($_POST['postID'] === "ProgramPost"){
                                    $programName = $_POST['program'];
                                    $programDesc = $_POST['desc'];

                                    echo createProgram($conn, $programName, $programDesc);
                                }else if($_POST['postID'] === "SessionPost"){
                                    $programID = $_POST['programID'];
                                    $sessionName = $_POST['sessionName'];
                                    $daysArr = $_POST['days'];
                                    $sessionDesc = $_POST['sessionDesc'];
                                    $startTime = $_POST['start'];
                                    $durrationMinutes = $_POST['durrMin'];
                                    $roomID = $_POST['roomID'];
                                    $startDate = $_POST['startDate'];
                                    $durrationWeeks = $_POST['durrWeek'];
                                    $feeM = $_POST['Mcost'];
                                    $feeNM = $_POST['Ucost'];
                                    $capacity = $_POST['capacity'];
                                    $preReq = $_POST['prerequisiteID'];
                                    
                                    $spotID = $_POST['spotID'];

                                    echo createSession($conn, $sessionName, $roomID, $spotID, $programID, $capacity, $sessionDesc, 
                                    $startDate, $durrationWeeks, $feeM, $feeNM, $preReq, $daysArr, $startTime, $durrationMinutes);

                                    //header("Refresh:0");
                                }
                            }
                        ?>
                        </div>
                    
                        <div id="AddSession" style="width: 100%">
                            <?php if($pagePopup != 0){
                                echo print_form_session($conn); 
                            }else{
                                echo print_form_program($conn); 
                            }?>
                        
                    
                    <br><br>

                    </td>
                </tr>
            </div>
        </Table>
        <script>
            function showEndTime() {
                var durr = document.getElementById("durrMin").value;
                var start = document.getElementById("start").value;

                if (durr.length == 0 || start.length == 0) {
                    document.getElementById("endTime").innerHTML = "";
                    return;

                }else{            
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("endTime").innerHTML = this.responseText;
                    }
                    };
                    xmlhttp.open("GET", "p10_getEndTime.php?calc=time&d=" + durr + "&s=" + start, true);
                    xmlhttp.send();

                }
            }

            function showEndDate() {
                var durr = document.getElementById("durrWeek").value;
                var start = document.getElementById("startDate").value;

                if (durr.length == 0 || start.length == 0) {
                    document.getElementById("endDate").innerHTML = "";
                    return;

                }else{            
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("endDate").innerHTML = this.responseText;
                    }
                    };
                    xmlhttp.open("GET", "p10_getEndTime.php?calc=date&d=" + durr + "&s=" + start, true);
                    xmlhttp.send();

                }
            }

            function showPrereqs(programID) {
                
                if (roomID == "") {
                    document.getElementById("prereqs").innerHTML = "";
                    return;

                }else{            
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("prereqs").innerHTML = this.responseText;
                    }
                    };
                    xmlhttp.open("GET", "p10_getEndTime.php?calc=preReq&d=&s=" + programID, true);
                    xmlhttp.send();

                }
            }

            function showSpots(roomID) {
                
                if (roomID == "") {
                    document.getElementById("spots").innerHTML = "";
                    return;

                }else{            
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("spots").innerHTML = this.responseText;
                    }
                    };
                    xmlhttp.open("GET", "p10_getEndTime.php?calc=spot&d=&s=" + roomID, true);
                    xmlhttp.send();

                }
            }
        </script>
    </body>
</html>

<?php

    function print_form_program($conn){
        return "<div id='AddProgram' > 
                            <h2>Add Program:</h2>
                            <form method='post'>
                                <input type='hidden' name='postID' value='ProgramPost'/>
                                Program Name: <input type='text' id='program' name='program' maxlength='30'><br><br>
                                Description: <br><br><textarea type='text' id='desc' name='desc' maxlength='200' rows='4' cols='30'></textarea><br><br>


                                <input type='submit' id='button' value='Add Program' />
                                <br><br>
                                
                                
            
                            </form>
                </div>";
    }
    function print_form_session($conn){
    
        $programs = queryAllPrograms($conn);
        if(mysqli_num_rows($programs)){
            $return = 
                "<h2>Add Session:</h2>
        
                <form method='post' id='SessionPost'>
                <input type='hidden' name='postID' value='SessionPost' /><br>";
        
            $return .= print_form_program_name_session($programs).
            print_form_session_name_session().
            "<table >
                <tr>
                    <td>"
                        .print_form_days_session().
                    "</td>
                    <td style='padding-left:100px'>".
                        print_form_desc_session().
                    "</td>
                </tr>
            </table>".
            print_form_times_session().
            "End time of program: <span id='endTime'></span><br><br>".
            print_form_dates_session().
            "End date of program: <span id='endDate'></span><br><br>".
            print_form_costs_session().
            print_form_capacity_session().
            "<br><span id='prereqs'></span>".
            print_form_room_num_session($conn).
            "<span id='spots'></span>".
        
            "<br><br>
                <input type='submit' id='add' value='Add Sessions' />
                <br><br>
                </form>";

                return $return;
            }else{
                return "No current programs!";
            }
    }
    

    function print_form_program_name_session($programs){

        $return = "Program Name: 
                            
        <select id='programID' name='programID' size='1' onchange='showPrereqs(this.value)'>
        <option value=''>Select A Program...</option>"; 

        while ($row = mysqli_fetch_assoc($programs)){
            if(isset($row['programID'])) {
                $return .= "<option value='" . $row['programID'] . "' id='programID'>" 
                    . $row['programName'] . "</option>"; 
            } 
        }
        $return .= "</select><br><br>";

        return $return;
    }

    function print_form_session_name_session(){
        return "Session Name: <input type='text' id='sessionName' name='sessionName' size='10px' maxlength='30'>
              <br><br><br>";
    }
    
    function print_form_days_session(){
        return "Days: 
        <br><br>
        <input type='checkbox' id='Monday' name='days[]' value='Monday'>
        <label for='monday'>Monday</label>
        <br>
        <input type='checkbox' id='Tuesday' name='days[]' value='Tuesday'>
        <label for='tuesday'>Tuesday</label>
        <br>
        <input type='checkbox' id='Wednesday' name='days[]' value='Wednesday'>
        <label for='wednesday'>Wednesday</label>
        <br>
        <input type='checkbox' id='Thursday' name='days[]' value='Thursday'>
        <label for='thursday'>Thursday</label>
        <br>
        <input type='checkbox' id='Friday' name='days[]' value='Friday'>
        <label for='friday'>Friday</label>
        <br>
        <input type='checkbox' id='Saturday' name='days[]' value='Saturday'>
        <label for='saturday'>Saturday</label>
        <br>
        <input type='checkbox' id='Sunday' name='days[]' value='Sunday'>
        <label for='sunday'>Sunday</label>
        <br><br>";
    }
    
    function print_form_desc_session(){
        return "Description: <br><br><textarea type='text' id='sessionDesc' name='sessionDesc' maxlength='200' rows='4' cols='30'></textarea>";
    }

    function print_form_times_session(){
        $return = print_form_start_time_session().print_form_durration_minutes_session();
        $return .= "<br><br>";
        return $return;
    }
    
    function print_form_start_time_session(){
        return 
"Start Time: <input type='time' id='start' name='start' size='10px' onkeyup='showEndTime()' onchange='showEndTime()' value='". date("H:i", strtotime("-7 hours")) ."'>";
    }
    
    function print_form_durration_minutes_session(){
        return " Durration(minutes): <input type='number' id='durrMin' name='durrMin' size='10px' onkeyup='showEndTime()' onchange='showEndTime()'>";
    }
    
    function print_form_dates_session(){
        $return = print_form_start_date_session().print_form_end_date_session();
       $return .= "<br><br>";
       return $return;
    }
    
    function print_form_start_date_session(){
        return "Start Date: <input type='date' id='startDate' name='startDate' size='10px' maxlength='20' onkeyup='showEndDate()' onchange='showEndDate()' value='". date("Y-m-d", strtotime("-7 hours")) ."'>";
    }
    
    function print_form_end_date_session(){
        return " Durration(weeks): <input type='number' id='durrWeek' name='durrWeek' size='10px' onchange='showEndDate()' onkeyup='showEndDate()'>";
    }
    
    
    
    function print_form_costs_session(){
        $return = print_form_member_cost_session().print_form_nonmember_cost_session();
        $return .= "<br><br>";
        return $return;
    }
    
    function print_form_member_cost_session(){
        return "Member Cost: <input type='text' id='Mcost' name='Mcost' maxlength='10'>";
    }
    function print_form_nonmember_cost_session(){
        return " Nonmember Cost: <input type='text' id='Ncost' name='Ucost' height='150px' maxlength='10'>";
    }
    
    function print_form_capacity_session(){
        return "Capacity: <input type='number' id='capacity' name='capacity' height='150px' maxlength='30'><br>";
    }
    

    function print_form_room_num_session($conn){
        $rooms = queryAllRooms($conn);

        $return = "<br>Room Number: 
                            
        <select id='roomID' name='roomID' size='1' onchange='showSpots(this.value)'>
        <option value=''>Select A Room...</option>"; 

        while($row = mysqli_fetch_assoc($rooms)){
            if(isset($row['roomID'])) {
                $return .= "<option value='" . $row['roomID'] . "'>" 
                    . $row['roomNum'] . "</option>"; 
            } 
        }
        $return .= "</select>";

        return $return;
    }
      

?>