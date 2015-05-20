<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password);
mysql_select_db('teamtriv_projectDB');

$data = json_decode(file_get_contents("php://input"));

$teamname = $data -> teamName; 
$emails = $data -> emails;

if(empty($teamname)){
	echo "Teams must have a name.";
	die ();
}
if (empty($emails[0])){
	echo "Teams must have at least one member.";
	die();
}
//do a sql query
echo $teamname;
$sql = "INSERT INTO Teams(teamName, emailA, emailB, emailC, emailD, emailE) VALUES ('".$teamname."', '".$emails[0]."', '".$emails[1]."', '".$emails[2]."',

		'".$emails[3]."','".$emails[4]."')";

mysql_query($sql);
mysql_close();

?>