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
// $Id: addschool.php,v 1.2 2002/01/16 22:08:25 basmati Exp $

require ("basmaticonstants.php");

session_start();

//Allows for register_globals=off
$school_id = $HTTP_POST_VARS['school_id'];
$school_name = $HTTP_POST_VARS['school_name'];
$school_state = $HTTP_POST_VARS['school_state'];
$school_city = $HTTP_POST_VARS['school_city'];
$school_url = $HTTP_POST_VARS['school_url'];

if ($_SESSION['LoginType'] != "A" . $districtid){
	echo("You must log-in to use this feature.");
    exit;
}
?>
	<html><head><title>Creating Basmati Database for MySQL</title>
	</head>
	<body bgolor="white">
	<h2>Adding data...</h2>
<?php

function printError($errorMesg)
{
 printf("<br><font color=red> %s <br>",$errorMesg);
}

$sql = "
  INSERT INTO SCHOOLS (
    school_id,
    school_name,
    school_state,
    school_city,
    school_url)
  VALUES
   ('".$school_id."',
    '".$school_name."',
    '".$school_state."',
    '".$school_city."',
    '".$school_url."')
   ;";

if ($datamethod=="mysql"){

 	echo("Attempting to connect to MySQL");
 	$link = mysql_connect($databaseserver,$datausername,$datapassword)
    	or die("Couldn't connect to server");
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
 	echo("<br>Added data into  <b>SCHOOLS</b>");

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
 	echo("<br>Added data into <b>SCHOOLS</b>");
} // end of odbc

?>
	<p>
	<font color=red size=+2><br>Finished!</font>



