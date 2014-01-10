<?php
// +----------------------------------------------------------------------+
// | Basmati -- Version 2.0P                                              |
// +----------------------------------------------------------------------+
// | Copyright (C) 2000-2001 James B. Bassett (basmatisoftware@msn.com)   |
// +----------------------------------------------------------------------+
// | This program is free software.  You can redistribute in and/or       |
// | modify it under the terms of the GNU General Public License Version  |
// | 2 as published by the Free Software Foundation.                      |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY, without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program;  If not, write to the Free Software         |
// | Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.            |
// +----------------------------------------------------------------------+
// | Authors: James B. Bassett - basmatisoftware@msn.com                  |
// +----------------------------------------------------------------------+

//Check security
session_start();

$schoolid = $_POST['schoolid'];
$cc = $_POST['cc'];

 require("../basmaticonstants.php");

if ($_SESSION['LoginType'] != "A" . $districtid){
  echo "<body bgcolor=#cacaff><font face='verdana,arial,helvetica'><b>You must log-in to use this feature.</font>";
  exit;
}

//Connect to the database...

 //Connect to the database
 $link = mysql_connect($databaseserver,$datausername,$datapassword);


//Select the appropriate database
 if (!mysql_select_db($databasename,$link)){
  echo("Can't connect to the database");
  exit;
 }

//Indicate success..
 // echo "Connected to database using $link";


//Process uploaded file...
$delim = chr(9);
$nl = chr(13);
$q = "\"";

echo "<B>Assignment List</B>";

 //Remove the old record...
   $sql = "SELECT assignlist, assignvals from COURSEINFO where schoolid = '$schoolid' and cc = '$cc';";
   $resource = mysql_query($sql,$link);
	$i = 0;
	$assignlist = mysql_result($resource, $i, assignlist);
	$assignvals = mysql_result($resource, $i, assignvals);
	$al = explode(chr(169),$assignlist);
	$an = explode(chr(169),$assignvals);


	echo "<PRE>";

	for ($i = 0; $i <= sizeof($al); $i++){
		echo $al[$i] . $delim . $an[$i] . $nl;
	}

	echo "</PRE>";

	echo "<B>Student Scores</B>";
	echo "<PRE>";

	$sql = "select sid, scores from GMSCORES where schoolid = '$schoolid' and cc = '$cc'";
    $resource = mysql_query($sql,$link);
    $n = mysql_num_rows($resource);
   for ($i= 0; $i < $n; $i++) {
		$sid = mysql_result($resource, $i, "sid");
		$scores = mysql_result($resource, $i, scores);
		$as = explode(chr(169), $scores);
		echo $sid . $delim;
		for ($j = 0; $j < sizeof($as); $j++){
			echo $q . $as[$j] . $q . $delim;
		}
		echo $nl;
   }

	






//Close the database
 mysql_close($link);

?>
