<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');


$data = json_decode(file_get_contents("php://input"));

$MMR = $data -> MMR;
$questionType = $data -> qType;
$lo = (intval($MMR)-100);
$hi = (intval($MMR)+100);

//CHANGE BOUNDS ON WHERE BELOW, SAQuestions to question type
$count = mysql_query("SELECT Count(question) as num FROM MCQuestions WHERE difficulty > " .$lo. " AND difficulty < " .$hi);

$numQ=mysql_fetch_assoc($count);


$query = "SELECT * FROM MCQuestions WHERE difficulty > ".$lo." AND difficulty < ".$hi. " limit " .rand(1,$numQ['num']-1). ",1";

$result = mysql_query($query);

$row = mysql_fetch_array($result);
$question = $row["question"];
$answer = $row["answer"];
$choiceA = $row["choiceA"];
$choiceB = $row["choiceB"];
$choiceC = $row["choiceC"];
$choiceD = $row["choiceD"];
$difficulty = $row["difficulty"];
$subject = $row["subject"];

$ar = array($question, $answer, $difficulty, $subject, $choiceA, $choiceB, $choiceC, $choiceD);
echo json_encode($ar);

mysql_close();



?>