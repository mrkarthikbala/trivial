<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";

$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');
$data = json_decode(file_get_contents("php://input"));
if(empty($data->userEmail)){
	echo "Please enter a valid email";
	die ();
}
$query = "SELECT teamName FROM Teams WHERE emailA LIKE '%".$data->userEmail."%' OR emailB LIKE '%".$data->userEmail."%' OR emailC LIKE '%".$data->userEmail."%' OR emailD LIKE '%".$data->userEmail."%' OR emailE LIKE '%".$data->userEmail."%'"; //You don't need a ; like you do in SQL
$result = mysql_query($query);
while($row = mysql_fetch_array($result)){   //Creates a loop to loop through results
    $teamName = $row["teamName"];
     echo $teamName;
}

mysql_close();


?>