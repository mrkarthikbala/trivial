<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');

$query = "SELECT * FROM Matches WHERE team1Left + team2Left = 0"; //You don't need a ; like you do in SQL
$result = mysql_query($query);
echo "<table class=\"table\">
<tr>
<th>Team 1</th>
<th>Team 2</th>
<th>Winner</th>
</tr>";

if (mysql_num_rows($result) <1) die();

$row = mysql_fetch_array($result);
do {
    echo "<tr>";
    echo "<td>" . $row['team1'] . "</td>";
    echo "<td>" . $row['team2'] . "</td>";
    if ($row["score"] > 0){
    	echo "<td>" . $row['team1'] . "</td>";
    }
    else if ($row["score"] == 0){
   	 echo "<td>Tie</td>";
    }
    else{
    	echo "<td>" . $row['team2'] . "</td>";
    	}
    	
    echo "</tr>";
} while($row = mysql_fetch_array($result)) ;

echo "</table>";
mysql_close();



?>