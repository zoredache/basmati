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

//Basmati Conversions...
$tablename = $_GET['tablename'];
$editrow = $_GET['editrow'];

$dbserv = $databaseserver;
$dbuser = $datausername;
$dbpass = $datapassword;
$dbname = $databasename;

if ($_SESSION['LoginType'] != "A" . $districtid){
  echo "<body bgcolor=#cacaff><font face='verdana,arial,helvetica'><b>You must log-in to use this feature.</font>";
  exit;
}

$sql = "DELETE FROM $tablename  where id=$editrow;";
//echo $sql;

 //Connect to the database
 $link = mysql_connect($dbserv,$dbuser,$dbpass);

 //Select the appropriate database
 if (!mysql_select_db($dbname,$link)){
  echo("Can't connect to the database");
  exit;
 }


  $result = mysql_query($sql,$link);
  echo "Your record has been deleted.";
 // echo $result;
 // echo $sql;
  mysql_close($link);

?>
