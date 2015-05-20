 <?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');

$data = json_decode(file_get_contents("php://input"));

$subject = $data->subject;
$type = $data ->type;
$keyword = $data->keyword;
$limit = $data->limit;

$sql = "";

if ($type == "Multiple Choice" or $type == "")
{
	$sql = $sql . "SELECT * FROM MCQuestions ";
}
else {
	$sql = $sql . "SELECT * FROM SAQuestions ";
}
$filters = "";
if ( !empty($subject)){
	$filters = $filters . "subject LIKE '%" . $subject . "%' ";
}
if (!empty($keyword)){
	if(!empty($filters)){
		$filters = $filters . " and ";
	}
	$filters = $filters . " question LIKE '%" . $keyword. "%' ";
}
if (!empty($filters)){
	$sql = $sql . "WHERE " . $filters;
}
$sql = $sql . "ORDER BY RAND() ";
if (!empty($limit)){
	$sql = $sql . "LIMIT " . $limit;
}

//echo $sql;
$result = mysql_query($sql);
//echo $result;


//Make 2d array of questions
$arr = array();
while($line = mysql_fetch_array($result, MYSQL_ASSOC))
{
	$arr[] = $line;
	//echo $line["question"] . '<br/>';
}
//echo "t";
echo json_encode($arr);

mysql_close();

?>