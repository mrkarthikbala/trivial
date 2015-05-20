 <?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');
$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$password = $data->password;

$sql = "SELECT * FROM Users WHERE username= '".$username."'";

$result = mysql_query($sql);
if (mysql_num_rows($result) == 0){
	echo -1;
}
else{
	$row = mysql_fetch_array($result);
	$p = $row["password"];
	if ($p == $password) echo $row["MMR"];
	else echo 0;	
  
}


mysql_close();



?>