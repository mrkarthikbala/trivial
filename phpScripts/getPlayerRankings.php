<?php
$servername = "engr-cpanel-mysql.engr.illinois.edu";
$username = "teamtriv_team";
$password = "triv1";


$connection = mysql_connect($servername, $username, $password); 
mysql_select_db('teamtriv_projectDB');

$query = "SELECT * FROM Users ORDER BY MMR DESC"; //You don't need a ; like you do in SQL
$result = mysql_query($query);
echo "<table class=\"table\">
<tr>
<th>Username</th>
<th>MMR</th>
</tr>";

$row = mysql_fetch_array($result);
$class = "success";
do {
    echo "<tr class=".$class.">";
    echo "<td>" . $row['username'] . "</td>";
    echo "<td>" . $row['MMR'] . "</td>";
    echo "</tr>";
    $class = "info";
} while($row = mysql_fetch_array($result)) ;

echo "</table>";
mysql_close();



?>