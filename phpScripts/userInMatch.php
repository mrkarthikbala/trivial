<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');
$data = json_decode(file_get_contents("php://input"));
$uname = $data -> username;

$query = "SELECT * FROM Users, Teams, Matches WHERE Users.username = '".$uname."' AND (Users.email = Teams.emailA OR Users.email = Teams.emailB OR Users.email = Teams.emailC OR Users.email = Teams.emailD OR Users.email = Teams.emailE) AND (Teams.teamname = Matches.team1 OR Teams.teamname = Matches.team2)";
			
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
	echo "None";
 }
 else{
$row = mysql_fetch_array($result);   //Creates a loop to loop through results
   
    $name = $row["username"];
    $score = $row["score"];
    $teamname = $row["teamname"];
    $team1 = $row["team1"];
    $team2 = $row["team2"];
    $mid = $row["matchID"];
    if ($teamname == $team1){
    	$on = "On Team 1";
    	$left = $row["team1Left"];
    }
    else{
    	$on = "On Team 2";
    	$left = $row["team2Left"];
    	}
    
     
     $ar = array($mid, $score, $left, $on);
     echo json_encode($ar);
}
mysql_close();



?>