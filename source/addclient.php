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
// $Id: addclient.php,v 1.1 2001/10/10 03:05:45 basmati Exp $

$LoginType = "";
session_start();


//Allows for register_globals=off
$LoginType = $HTTP_SESSION_VARS['LoginType'];
$client_id = $HTTP_POST_VARS['client_id'];
$client_pw = $HTTP_POST_VARS['client_pw'];
$client_school = $HTTP_POST_VARS['client_school'];
$client_expdate = $HTTP_POST_VARS['client_expdate'];
$client_fullname = $HTTP_POST_VARS['client_fullname'];



 require ("basmaticonstants.php");
 if ($LoginType != "A"){
   echo("You must log-in to use this feature.");
   exit;
 }
?>


<html><head><title>Creating Basmati Database for MySQL</title>
</head>
<body bgolor=white>
<h2>Adding data...</h2>



<?php

require ("basmaticonstants.php");
function printError($errorMesg)
{
 printf("<br><font color=red> %s <br>",$errorMesg);
}

$sql = "
  INSERT INTO CLIENTS (
    client_id,
    client_pw,
    client_school,
    client_expdate,
    client_fullname)
  VALUES
   ('".$client_id."',
    '".$client_pw."',
    '".$client_school."',
    '".$client_expdate."',
    '".$client_fullname."')
   ;";



if ($datamethod=="mysql"){

 echo("Attempting to connect to MySQL");
 $link = mysql_connect($databaseserver,$datausername,$datapassword) or
die("Couldn't
 connect to server");
 echo("-- connected.");


 echo("<br>Selecting database");
 if (!mysql_select_db($databasename,$link)){
   printError(sprintf("Error in selecting %s database", $databasename));
   printError(sprintf("error: %d %s",mysql_errno($link),mysql_error($link)));
   exit();
 };
 echo("-- OK");



 if (!mysql_query($sql,$link)){
   printError(sprintf("Error in %s", $sql));
   printError(sprintf("error: %d %s",mysql_errno($link),mysql_error($link)));
   exit();
 };
 echo("<br>Added data into  <b>CLIENTS</b>");


 echo("<br>Closing database...");
 if (!mysql_close($link)){
   printError(sprintf("Error in closing database.", $link));
   printError(sprintf("error: %d %s",mysql_errno($link),mysql_error($link)));
   exit();
 };
 echo(" -- closed.");
} //End of mysql


if ($datamethod == "odbc"){
 $link = odbc_connect($databasename,$datausername,$datapassword);
 $sql_statement = odbc_prepare($link,$sql);
 $sql_result = odbc_execute($sql_statement);
 odbc_free_result($sql_statement);
 odbc_close($link);
 echo("<br>Added data into <b>CLIENTS</b>");
} // end of odbc


?>
<p>
<font color=red size=+2><br>Finished!</font>


