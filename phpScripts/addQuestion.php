<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');

$data = json_decode(file_get_contents("php://input"));

$subject = $data->subject;
$type = $data ->type;
$question = $data->question;
$A= $data->A;
$B= $data->B;
$C= $data->C;
$D= $data->D;
$difficulty = $data -> difficulty;
$answer = $data->answer;

if(empty($subject))
{
	echo "Please select a subject";

	die();
}
if(empty($type))
{
	echo "Please select a type";
	die();
}
if(empty($question))
{
	echo "Please enter a question";
	die();
}
if (empty($difficulty) || intval($difficulty) <= 0 || intval($difficulty) > 1000){
	echo "Did you enter a difficulty between 1 and 1000?";
	die();
}
else if($type == "Multiple Choice")
{
	if ($A != "0" and empty($A) )
	{
		echo "Please enter answer option A";
		die();
		
	}
	if ($B != "0" and empty($B))
	{
		echo "Please enter answer option B";
		die();
	}
	if ($C != "0" and empty($C))
	{
		echo "Please enter something for C option";
		die();
	}
	if ($D != "0" and empty($D))
	{
		echo "Please enter something for D option";
		die();
	}
}
if ($answer != "0" and empty($answer))
{
	echo "Please enter an answer.";
	die();
}

$sql = "";
if ($type == "Multiple Choice")
{
	$sql = "INSERT INTO 
	MCQuestions (answer, choiceA, choiceB, choiceC, choiceD, question, difficulty, subject) 
	VALUES (\"".$answer."\", '".$A."', '".$B."', '".$C."', '".$D."', \"".$question."\", ".intval($difficulty).", '".$subject."')";
	echo "Multiple Choice Added!";
}
else if ($type == "Short Answer")
{
	$sql = "INSERT INTO 
	SAQuestions(question, answer, difficulty, subject) 
	VALUES(\"".$question."\", \"".$answer."\", ".intval($difficulty).", '".$subject."')";
	
	echo "Short Answer Added";
	
}

mysql_query($sql);
mysql_close();

?>