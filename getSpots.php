<?php

session_start();
include("connections.php");
include("functions.php");


$roomName = mysqli_real_escape_string($conn, $_POST['roomName']);
// Retrieve the spot data for the selected room from the database
    $query = "SELECT spots FROM rooms WHERE roomName = '$roomName'";
    $result = mysqli_query($conn, $query);

    if (count($spots) === 0) {
        die('No spots found for room: ' . $roomName);
      }

    // Convert the result set to an array of objects
    $spotsStr = mysqli_fetch_assoc($result);
    $spots = array();
    $spotsArr = json_decode($spotsStr);
    for($i=0; $i<sizeof($spotsArr); $i++){
        $query1 = "SELECT * FROM spots WHERE spotsID = '$spotsArr[$i]'";
        $result1 = mysqli_query($conn, $query1);
        $spotRow = mysqli_fetch_assoc($result1);
        $spots[] = $spotRow;
    }

// Encode the spots data in JSON format and send it back to the client
echo json_encode($spots);

?>
