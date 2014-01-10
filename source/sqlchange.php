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

require_once('global.php');

//This file allows the editing of a particular row in a table

//Basmati Conversions...

$dt = $_POST['dt'];
$dv = $_POST['dv'];
$dn = $_POST['dn'];
$maxval = $_POST['maxval'];
$tablename = $_POST['tablename'];
$editrow = $_POST['editrow'];


$dbserv = $databaseserver;
$dbuser = $datausername;
$dbpass = $datapassword;
$dbname = $databasename;

if ($_SESSION['LoginType'] != "A" . $districtid){
  echo "<body bgcolor=#cacaff><font face='verdana,arial,helvetica'><b>You must log-in to use this feature.</font>";
  exit;
}

//Start building SQL query to edit....
//Determine the list of fields...
//Go through the list of values... if string type, add tick marks
  for ($i=0;$i<$maxval;$i++){
   if ($dt[$i] == "string" || $dt[$i] == "blob" || $dt[$i] == "date" || $dt[$i] = "timestamp") {
     $dv[$i] = chr(34) . $dv[$i] . chr(34);
   }
   $editlist[$i] = $dn[$i] . "=" . $dv[$i];
  }
  $vallist = implode($editlist,",");

$sql = "UPDATE $tablename set $vallist where id=$editrow;";

 //Connect to the database
 $link = mysql_connect($dbserv,$dbuser,$dbpass);

 //Select the appropriate database
 if (!mysql_select_db($dbname,$link)){
  echo("Can't connect to the database");
  exit;
 }


  $result = mysql_query($sql,$link);
  echo "Your data has been submitted.";
 // echo $result;
 // echo $sql;
  mysql_close($link);

?>
