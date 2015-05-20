<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password);
mysql_select_db('teamtriv_projectDB');

$data = json_decode(file_get_contents("php://input"));

$team1 = $data -> team1; 
$team2 = $data -> team2;
$score = $data -> score;
$totalQuestions = $data -> TotalQuestions;
$t1Left = $data -> Team1Left;
$t2Left = $data -> Team2Left;


$sql = "INSERT INTO Matches(team1, team2, score, totalQuestions, team1Left, team2Left) VALUES ('".$team1."','" .$team2."'," .$score.",".$totalQuestions.",".$t1Left.",".$t2Left.")";

echo $sql;
mysql_query($sql);
mysql_close();

?>