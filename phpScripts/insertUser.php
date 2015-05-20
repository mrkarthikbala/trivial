<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');

$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$email = $data ->email;
$password = $data->password;

echo $data->username;
$sql = "INSERT INTO Users(email, username, password, MMR) VALUES ('".$email."', '".$username."', '".$password."', '500')";

mysql_query($sql);
mysql_close();

?>