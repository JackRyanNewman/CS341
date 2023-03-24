<?php
session_start();
include("connections.php");
include("functions.php");

$user_data = check_login($conn);

$result1 = progam_query($conn);
$countP = mysqli_num_rows($result1);

$result = sessions_query($conn);
$count = mysqli_num_rows($result);
if($result1 && mysqli_num_rows($result1)>0)
{
    $program_data = mysqli_fetch_assoc($result1);
}

if($_SERVER['REQUEST_METHOD']=="POST") {

    $sess_id = $_POST['sessionID'];

    $sess = $user_data['currSessions'];
    $array_sess = json_decode($sess);
    $array_sess[sizeof($array_sess)] = $sess_id;
    $json_sess_arr = json_encode($array_sess);

    $user_nam = $user_data['username'];
    $query_Sess = "UPDATE people SET currSessions='$json_sess_arr' WHERE `username`='$user_nam'";

    mysqli_query($conn, $query_Sess);

    echo $query_Sess;
    header("Location: User.php");
}

    ?>

<html>
    <head>  
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js"></script>
    </head>
<body id="page">

<div id="menu">
        <p style="text-align:left;position:fixed;">
                Hello <?php echo $user_data['name']; ?>
               </p>

    <h1>YMCA</h1>
    <ul>
        <li><A href="Register.php">Register</A></li>
        <li><A href="Account.php">Account</A></li>
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
                <table>
                    <tr>
                    <td width="40%">
                    <p style=" font-size: 20px">
                        <br>
                        <br>
                     We're part of a nonprofit organization of over 2,700 Ys located in 10,000 communities 
                     across the United States dedicated to strengthening the communities that they serve. 
                     With a focus on developing the potential of youth, improving individual health and well-being, 
                     and giving back to and supporting communities, your participation brings about meaningful change not just within yourself, 
                     but also in your community.

                     <?php ; ?>
                
                        <br>
               
                    </p>
                    </td>
                    <td style="position:fixed; right:15%; top:250px">
                    
                </div>
                    
                    <td width="50%">
                    <p style=" font-size: 18px" class="content">
                    <h1 style=" font-size: 28px"> Our Programs:  </h1>
                    <h1 style=" font-size: 20px"> Sessions count: <?php echo $count?> </h1>
                    </header>
                        

                    <?php 
                    for($j=0; $j<$countP; $j++){
                        $query_prog = "SELECT * from program LIMIT 1 OFFSET $j";
                        $result_prog = mysqli_query($conn, $query_prog);
                        $resultPro = mysqli_fetch_assoc($result_prog);
                        echo "<h3>"; 
                        echo $resultPro['programName'];  
                        echo"</h3>";
                        $json_sessions = json_decode($resultPro['sessions']);
                        if(!empty($json_sessions)){
                            
                            foreach($json_sessions as $item) { //foreach element in $arr
                                $sessions_query = "SELECT * FROM sessions WHERE sessionID = {$item->sessionID};";
                                $resultSessionQuery = mysqli_query($conn,$sessions_query);
                                $session_data = mysqli_fetch_assoc($resultSessionQuery);
                                echo"
        <br><br> ".($session_data['sessionName']).": <br>
        <br>
        Where: Room ".$session_data['roomNumber']."
        <br>
        Capacity: ".$session_data['capacity']."
        <br> 
        Level required: ".$session_data['level']."
        <br>
        Cost: 
        <br>
        
       
        Member: ".$session_data['feeMem']."$
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        Nonmember:".$session_data['feeNonMem']."$
        <br>";
 
    echo "<form method='post'>";
    echo "<select id='test' name='sessionID' size='1' style='display: none;'>";

    
    echo '<option value="' . $session_data['sessionID'] . '">' . $session_data['sessionID'] . '</option>';

    echo "</select>";
    echo "<input type='submit' id='signup' value='Sign up'>";
    echo "</form>";
    }
    echo "<br>";
}
                    }
                            ?>

                    
                    </form>
                          
                </tr>
                </table>
    
    
        </div>
</body>
</html>