<?php
session_start();
include("connections.php");
include("functions.php");

$user_data = check_login($conn);

// Data that goes into the pogram table...

$query = "SELECT programName FROM program";
$result = mysqli_query($conn, $query);

$query1 = "SELECT roomNumber FROM rooms";
$result1 = mysqli_query($conn, $query1);

// Generate the select element with options


if($_SERVER['REQUEST_METHOD']=="POST")
    {

        $programName = $_POST['program'];

//Data that goes into the event table...

$sessionName = $_POST['sessionName'];
$startTime=$_POST['start'];
$endTime=$_POST['end'];
$roomNumber=$_POST['roomNumber'];
$startDate=$_POST['startdate'];
$endDate=$_POST['enddate'];
$Mcost=$_POST['Mcost'];
$Ucost=$_POST['Ucost'];
$capacity=$_POST['capacity'];
$prereq=$_POST['prereq'];



$query_SelectPrTable = "SELECT * FROM program WHERE programName = '$programName'";
$result_PrRow = mysqli_query($conn, $query_SelectPrTable);
$result_PrRowFetch = mysqli_fetch_assoc($result_PrRow);
$progID = $result_PrRowFetch['programID'];

$roomIDQuery = "SELECT roomID FROM rooms WHERE roomNumber = $roomNumber";
$roomResult = mysqli_query($conn, $roomIDQuery);
$roomResultFetch = mysqli_fetch_assoc($roomResult);
$roomID = $roomResultFetch['roomID'];

$temp_Arr = array();
$json_participants = json_encode($temp_Arr);

$queryEventTable = 
"INSERT INTO `sessions` (`sessionID`, `sessionName`, `roomID`, `roomNumber`, `programID`, `programName`, 
                        `capacity`, `feeMem`, `feeNonMem`, `level`, `minAge`, `maxAge`, `startDate`, `endDate`, `participant`) 
VALUES (NULL, '$sessionName', '$roomID', '$roomNumber', '$progID','$programName', '$capacity', 
'$Mcost', '$Ucost', '$prereq', '8', '18', '$startDate', '$endDate', '$json_participants')";

mysqli_query($conn, $queryEventTable);

if(isset($_POST['add'])){
    // Get the selected days and store them in an array
    if(!empty($_POST['days'])){
        $selectedDays = $_POST['days'];
        // Loop through the array of selected days and display them
        foreach($selectedDays as $day){
            echo $day."</br>"; //you can use your own logic to store the values
        }
    }
}


//program
$not_inline = $result_PrRowFetch['sessions'];
$Sessions_list_arr = json_decode($not_inline);


$sessQuery = "SELECT sessionID FROM sessions WHERE sessionName='$sessionName'";
$result_sess = mysqli_query($conn, $sessQuery);
$im_a_result = mysqli_fetch_assoc($result_sess);

$Sessions_list_arr[sizeof($Sessions_list_arr)] = $im_a_result;
$strSessionsList = json_encode($Sessions_list_arr);

$query_Sess = "UPDATE `program` SET `sessions`='$strSessionsList' WHERE `programName`='$programName'";
mysqli_query($conn, $query_Sess);

    }



    ?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
        <!-- Load the jQuery library from a CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Your script that uses jQuery -->
    <script>
document.addEventListener("DOMContentLoaded", function() {
    
    const AddSession = document.getElementById("AddSession");
    document.getElementById("CreateSession").addEventListener("click", ()=>{
        if(AddSession.style.display === "block") {
            AddSession.style.display = "none";
        } else{
            AddSession.style.display = "block";
        }   
    });
  });
  
  /*  $(document).ready(function() {
    // Add an event listener to the room dropdown
    $('#roomNumber').on('change', function() {
      // Get the selected room name
      var selectedRoom = $(this).val();
      
      // Check if a room is selected
      if (selectedRoom !== '') {
        // Send an AJAX request to the server with the selected room name as a parameter
        $.ajax({
          url: 'getSpots.php',  // The URL of the PHP script that retrieves the spot data
          type: 'POST',  // Use the HTTP POST method to send the request
          data: {roomNumber: selectedRoom},  // The data to send with the request (in this case, the selected room name)
          success: function(data) {
            // This function is called when the AJAX request succeeds (i.e., the server responds with a success status code)
            
            // Parse the JSON data returned by the server into a JavaScript object
            var spots = JSON.parse(data);
            
            // Create an empty string to store the HTML code for the spots dropdown
            var spotsHtml = '<option value="">Select A Spot...</option>';
            
            // Loop through the spots and create an HTML option element for each one
            $.each(spots, function(index, spot) {
              spotsHtml += '<option value="' + spot.name + '">' + spot.name + '</option>';
            });
            
            // Add the HTML code for the spots dropdown to the container element on the web page
            $('#spotName').html(spotsHtml);
          }
        });
      } else {
        // If no room is selected, clear the container element
        $('#spotName').html('<option value="">Select A Spot...</option>');
      }
    });
  });*/
    </script>

  
    </head>
<body id="page">

<div id="menu">
    <p style="text-align:left;position:fixed;">
                Hello <?php echo $user_data['name']; ?>
               </p>

    <h1>YMCA</h1>
    <ul>
        <li><A href="Program.php">Create Program</A></li>
        <li><A id="CreateSession">Create Sessions</A></li>
        <li><A href="home.php">Sign-out</A></li>
    </ul>
</div>

<div class="content">   

                <div id="Img">
                    <img src="YMCA-Logo.png" height = "20%" alt="Image of YMCA Logo">
                   
                    <header style=" font-size: 30px">
                        Welcome to the Llanfairpwllgwyngyllgogerychwyrndrobwllllantysiliogogogoch YMCA!
                    </header>
                    
                </div>
                <table height="500px">
                    <tr>
                    <td style="position:static;" width="50%" >
                    <p style=" font-size: 22px; top:50px;">
                        <br>
                        <br>
                     We're part of a nonprofit organization of over 2,700 Ys located in 10,000 communities 
                     across the United States dedicated to strengthening the communities that they serve. 
                     With a focus on developing the potential of youth, improving individual health and well-being, 
                     and giving back to and supporting communities, your participation brings about meaningful change not just within yourself, 
                     but also in your community.
                
                        <br>
               
                    </p>
                    </td>
                   

            <td style="position:absolute; left:60%;">

            <div id="AddSession" style="display: none"> 
                    <h2>Add Session:</h2>
                    
                        <form method="post">
                     Program Name: 
                        <?php 
                        echo "<select id='program' name='program' size='1'>";
                        echo "<option value=''>Select A Program...</option>"; 
                        while ($row = mysqli_fetch_assoc($result)) {
                       echo "<option value='" . $row['programName'] . "'>" . $row['programName'] . "</option>";
}
echo "</select>";
?>
<br><br>
Session Name: <input type="text" id="sessionName" name="sessionName" size="10px" maxlength="30"><br><br>

<br><br>
<input type="checkbox" id="monday" name="days[]" value="monday">
<label for="monday">Monday</label>
<br>
<input type="checkbox" id="tuesday" name="days[]" value="tuesday">
<label for="tuesday">Tuesday</label>
<br>
<input type="checkbox" id="wednesday" name="days[]" value="wednesday">
<label for="wednesday">Wednesday</label>
<br>
<input type="checkbox" id="thursday" name="days[]" value="thursday">
<label for="thursday">Thursday</label>
<br>
<input type="checkbox" id="friday" name="days[]" value="friday">
<label for="friday">Friday</label>
<br>
<input type="checkbox" id="saturday" name="days[]" value="saturday">
<label for="saturday">Saturday</label>
<br>
<input type="checkbox" id="sunday" name="days[]" value="sunday">
<label for="sunday">Sunday</label>
<br><br>

Start Time: <input type="time" id="start" name="start" size="10px">

End Time: <input type="time" id="end" name="end" size="10px"><br><br>



Start Date: <input type="date" id="startdate" name="startdate" size="10px" maxlength="20">

End Date: <input type="date" id="enddate" name="enddate" size="10px" maxlength="20"><br><br>

<label for="roomNumber">Room Number:</label>
<select id="roomNumber" name="roomNumber">
  <option value="">Select A Room...</option>
  <?php 
  while ($row = mysqli_fetch_assoc($result1)) {
    echo "<option value='" . $row['roomNumber'] . "'>" . $row['roomNumber'] . "</option>";
  }
  ?>
  </select>


<!--<div id="spotsContainer">
    Spot: <select id="spotsName" name="spotName"></select>
  </div>-->


<br><br>

Member Cost: <input type="text" id="Mcost" name="Mcost" maxlength="10">

Nonmember Cost: <input type="text" id="Ncost" name="Ucost" height="150px" maxlength="10"><br><br>

Capacity: <input type="number" id="capacity" name="capacity" height="150px" maxlength="30">

Prerequisite Course(s): <input type="text" id="prereq" name="prereq" maxlength="50"><br><br>

<input type="submit" id="add" value="Add Sessions" />

                        <br><br><br>
    
                    </form>
    
                </div>

                </td>
                </tr>
                </table>
        </div>
</body>
</html>