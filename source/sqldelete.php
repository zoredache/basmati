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
// $Id: sqldelete.php,v 1.2 2002/01/16 22:08:25 basmati Exp $

//Check security
session_start();

//Basmati Conversions...
include("basmaticonstants.php");
$tablename = $HTTP_GET_VARS['tablename'];
$editrow = $HTTP_GET_VARS['editrow'];

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
