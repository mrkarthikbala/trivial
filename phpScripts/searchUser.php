<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');
$data = json_decode(file_get_contents("php://input"));

$query = "SELECT * FROM Users WHERE username= '".$data->username."'"; //You don't need a ; like you do in SQL

$result = mysql_query($query);
if( mysql_num_rows($result) == 0) echo "None-Username";
else{
$row = mysql_fetch_array($result);  //Creates a loop to loop through results
    $email = $row["email"];
    $name = $row["username"];
    $MMR = $row["MMR"];
     
     $ar = array($email, $name, $MMR);
     echo json_encode($ar);
}
mysql_close();



?>














