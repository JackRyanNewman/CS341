<?php
session_start();
include("connections.php");
include("functions.php");

//Used to get data for the programs

$result1 = progam_query($conn);
$countP = mysqli_num_rows($result1);

//Used to get data for the sessions avalible
$result = sessions_query($conn);
$count = mysqli_num_rows($result);
if($result1 && mysqli_num_rows($result1)>0)
{
    $program_data = mysqli_fetch_assoc($result1);
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
        <li><A href="Sign-up.php">Sign-up</A></li>
        <li><A href="login.php">Login</A></li>
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
                        echo "<br><br>";
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
    }
}
                    }
                            ?>

                    
                    </form>
                          
                </tr>
                </table>
    
    
        </div>
</body>
</html>