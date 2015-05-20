<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');

$query = "SELECT teamName, AVG(MMR) AS teamMMR FROM Users JOIN Teams ON (Users.email = Teams.emailA OR Users.email = Teams.emailB OR Users.email= Teams.emailC OR Users.email = Teams.emailD OR Users.email = Teams.emailE) GROUP BY teamName ORDER By teamMMR DESC";

$result = mysql_query($query);
echo "<table class=\"table\">
<tr>
<th>Team Name</th>
<th>MMR</th>
</tr>";

$row = mysql_fetch_array($result);
$class = "success";
do {
    echo "<tr class=".$class.">";
    echo "<td>" . $row['teamName'] . "</td>";
    echo "<td>" . $row['teamMMR'] . "</td>";
    echo "</tr>";
    $class = "info";
} while($row = mysql_fetch_array($result)) ;

echo "</table>";
mysql_close();



?>