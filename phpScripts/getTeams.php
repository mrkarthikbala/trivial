<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');

$query = "SELECT DISTINCT Teams.teamName FROM Teams";
$result = mysql_query($query);

$solutions = array();
while ($row = mysql_fetch_array($result)){
 $solutions[] = $row['teamName'];
}
echo json_encode($solutions);
mysql_close();



?>