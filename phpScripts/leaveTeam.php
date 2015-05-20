<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";

$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');
$data = json_decode(file_get_contents("php://input"));

if(empty($data->quitterEmail)){
	echo "Please Enter a Valid Email";
	die();
}

$query = "UPDATE Teams SET emailA = null WHERE emailA LIKE '%".$data->quitterEmail."%'";
$result = mysql_query($query);

$query = "UPDATE Teams SET emailA = null WHERE emailB LIKE '%".$data->quitterEmail."%'";
$result = mysql_query($query);

$query = "UPDATE Teams SET emailA = null WHERE emailC LIKE '%".$data->quitterEmail."%'";
$result = mysql_query($query);

$query = "UPDATE Teams SET emailA = null WHERE emailD LIKE '%".$data->quitterEmail."%'";
$result = mysql_query($query);

$query = "UPDATE Teams SET emailA = null WHERE emailE LIKE '%".$data->quitterEmail."%'";
$result = mysql_query($query);


mysql_close();
?>