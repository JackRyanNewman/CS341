<?php  

/*  p9_calenar.php
/*	Displays a users calendar(only accessable through account page)
/*
/*	Authors: Travis Wiesner, Jackson Mishuk
/*	Date Created: 04/03/2023
/*	Date Modified: 4/04/2023
*/

include_once("f0_connections.php");
include_once("f1_user.php");
include_once("f2_location.php");


session_start();

$user_data = checkLogin($conn);

$currSessions_json = $user_data['currSessions'];
$currSessions_arr = get_array($currSessions_json);

    
    ?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>

    </head>
<body id="page">

<?php echo showMenu($user_data);?>

<br><br>   

</div>

</body>
<br><br><br><br><br><br><br><br>
</html>

<?php
$start_year = 2023;
$end_year = 2024;
?>
<?php
// Get the selected month and year
if (!empty($_GET["month"]) && !empty($_GET["year"])) {
  $month = $_GET["month"];
  $year = $_GET["year"];
}else{
    $month = date("m");
    $year = date("Y");
}
  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  
  echo "<div style='text-align:center; position: relative; top: -75px;'>";
    if($month == "01"){
        echo "<text style='font-size: 30px;'><b> January $year </b></text><br>";
    } else if($month == "02"){
        echo "<text style='font-size: 30px;'><b> February $year </b></text><br>";
    } else if($month == "03"){
        echo "<text style='font-size: 30px;'><b> March $year </b></text><br>";
    } else if($month == "04"){
        echo "<text style='font-size: 30px;'><b> April $year </b></text><br>";
    } else if($month == "05"){
        echo "<text style='font-size: 30px;'><b> May $year </b></text><br>";
    } else if($month == "06"){
        echo "<text style='font-size: 30px;'><b> June $year </b></text><br>";
    } else if($month == "07"){
        echo "<text style='font-size: 30px;'><b> July $year </b></text><br>";
    } else if($month == "08"){
        echo "<text style='font-size: 30px;'><b> August $year </b></text><br>";
    } else if($month == "09"){
        echo "<text style='font-size: 30px;'><b> September $year </b></text><br>";
    } else if($month == "10"){
        echo "<text style='font-size: 30px;'><b> October $year </b></text><br>";
    } else if($month == "11"){
        echo "<text style='font-size: 30px;'><b> November $year </b></text><br>";
    } else if($month == "12"){
        echo "<text style='font-size: 30px;'><b> December $year </b></text><br>";
    }   
echo "</div>";

// Calculate the number of days in the selected month
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Determine the first day of the month
$first_day = date("w", strtotime("$year-$month-01"));

// Determine the number of blank cells needed at the beginning and end of the calendar
$blank_cells_start = $first_day;
$blank_cells_end = 42 - ($blank_cells_start + $days_in_month);
if ($blank_cells_end == 7) {
  $blank_cells_end = 0;
}

// Generate the calendar
echo "<table bgcolor='grey' align='left'style='position:fixed;left:25%;width:50%;height:60%;top:195px;'>";
echo "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";

$current_day = 1;
for ($i = 0; $i < 6; $i++) {
  echo "<tr>";
  for ($j = 0; $j < 7; $j++) {
    if ($i == 0 && $j < $blank_cells_start) {
      echo "<td style='border:1px solid #000;'></td>";
    } else if ($current_day <= $days_in_month) {
      $date = sprintf("%02d-%02d-%04d", $current_day, $month, $year);
      echo "
      
      <td style='border:1px solid #000;'>
      <button class='dateButton' button onclick='popup()' 
      button style='border: 0; background: grey; width: 50' value='" . $year . "-" . $month . "-" . $current_day . 
      "'>" . $current_day . "</button></td>
      
      </form>";
      $current_day++;
    } else{
        while($j<7){
            echo "<td style='border:1px solid #000;'></td>";
            $j++;
        }
        $i=5;
    }
  }
  echo "</tr>";
}
echo "</table>";

?>



<div id="Popup-info">
<br>
<u>Classes Today on <?php $date ?>
    

</u>
<br><br>
Session Name: 
<br><br>
Time: 
<br><br>
Location:
<br><br>
Instructor:
<br><br>
<button class="close" onclick="closePopup()">X</button>

</div>

<script>
    
function popup() {
  var popup = document.getElementById("Popup-info");
  popup.style.display = "block";
}

function closePopup() {
  var popup = document.getElementById("Popup-info");
  popup.style.display = "none";
}
</script>

<style>

#Popup-info {
  display: none;
  position: fixed;
  font-size: 30px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  padding: 20px;
  background-color: white;
  border: 1px solid black;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  z-index: 1;
  min-width: 40%;
}
.close{
    position: absolute;
    top: 0;
    right: 0;
}

</style>


<html>
   
<form id="dates" form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" style="position:fixed; left:3%">
  <label for="month" >Month:</label>
  <select id="month" name="month">
    <option value="01">January</option>
    <option value="02">February</option>
    <option value="03">March</option>
    <option value="04" selected>April</option>
    <option value="05">May</option>
    <option value="06">June</option>
    <option value="07">July</option>
    <option value="08">August</option>
    <option value="09">September</option>
    <option value="10">October</option>
    <option value="11">November</option>
    <option value="12">December</option>
  </select>
  <br><br>
  <label for="year">Year:</label>
  <select id="year" name="year">
    <?php
    for ($i = $start_year; $i <= $end_year; $i++) {
      echo "<option value=\"$i\">$i</option>";
    }
    ?>
  </select>
  <br><br>
  <button type="submit">Submit</button>
</form>
</div>

</body>
</html>

