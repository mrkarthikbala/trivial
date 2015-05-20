<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');
$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$query = "DELETE FROM Users WHERE username='".$username."'";

mysql_query($query);
mysql_close();



?>