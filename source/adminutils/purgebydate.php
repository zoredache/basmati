<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
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
//
// $Id: purgebydate.php,v 1.1 2002/01/22 18:27:04 basmati Exp $

//Check security
$LoginType = "";
session_start();

$LoginType = $HTTP_SESSION_VARS['LoginType'];
$schoolid = $HTTP_POST_VARS['schoolid'];
$purgedate = $HTTP_POST_VARS['purgedate'];
$act = $HTTP_POST_VARS['act'];

 require("../basmaticonstants.php");

if ($LoginType != "A" . $districtid){
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

if ($act == "confirm"){

 //Remove the old record...
   $sql = "SELECT * from COURSEINFO where LastUpdate < '$purgedate' and schoolid = '$schoolid';";
   $resource = mysql_query($sql,$link);
   $n = mysql_num_rows($resource);
   if ($n > 1) echo "<b>You are about to delete the following classes:</b>";
   echo "<table border=1>";
   echo "<tr><th>CC</th><th>Instructor</th><th>Last Updated</th></tr>";
   for ($i = 0; $i < $n; $i++) {
	echo "<tr>";
	echo "<td>" .  (mysql_result($resource,$i,cc) . "</td>");
	echo "<td>" .  (mysql_result($resource,$i,Facultyname) . "</td>");
	echo "<td>" .  (mysql_result($resource,$i,LastUpdate) . "</td>");
	echo "</tr>";
	
   }
   echo "</table>";
   echo "<form method=post action=purgebydate.php>";
   echo "<input type=hidden name=act value=doit>";
   echo "<input type=hidden name=schoolid value=$schoolid>";
   echo "<input type=hidden name=purgedate value=$purgedate>";
   echo "<input type=submit value='PURGE!'>";
   echo "</form>";

} //$act = confirm


if ($act == "doit"){

 //Remove the old record...
   $sql = "SELECT * from COURSEINFO where LastUpdate < '$purgedate' and schoolid = '$schoolid';";
   $resource = mysql_query($sql,$link);
   $n = mysql_num_rows($resource);
   if ($n > 1) echo "<b>The following classes have been deleted:</b><br>";
   for ($i = 0; $i < $n; $i++) {
 	$cc = mysql_result($resource,$i,cc);
	$sql_del = "DELETE from GMSCORES where cc = '$cc' and schoolid = '$schoolid';";
	echo "$cc at $schoolid <br>";
	$delresource = mysql_query($sql_del,$link);
   }
   //Now delete the all of the courseinfo...
   $sqldel = "DELETE from COURSEINFO where LastUpdate < '$purgedate' and schoolid = '$schoolid';";
   $resource = mysql_query($sqldel,$link);
   echo "<font color=red size=+1>DONE!</font>";

} //$act = doit




//Close the database
 mysql_close($link);

?>


