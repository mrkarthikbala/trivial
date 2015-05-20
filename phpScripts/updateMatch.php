 <?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');
$data = json_decode(file_get_contents("php://input"));

$mid = $data->matchID;
$score = $data->score;

$sql = "UPDATE Matches SET score=".$score." WHERE matchID=".$mid."";
mysql_query($sql);

if ($data -> team1Left){
	$sql = "UPDATE Matches SET team1Left=".$data ->team1Left." WHERE matchID=".$mid."";
}
else{

	$sql = "UPDATE Matches SET team2Left=".$data ->team2Left." WHERE matchID=".$mid."";
}
mysql_query($sql);
mysql_close();



?>