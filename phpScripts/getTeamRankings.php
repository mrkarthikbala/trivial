<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');

$query = "SELECT Teams.teamName FROM Teams";
$result = mysql_query($query);

while($row = mysql_fetch_array($result)){
	echo $row;
	}

mysql_close();



?>