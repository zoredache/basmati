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
// $Id: privatenotesubmit.php,v 1.1 2001/11/01 20:51:27 basmati Exp $

 $LoginType = "";
 session_start();
  $LoginType = $HTTP_SESSION_VARS['LoginType'];
  $SchoolID = $HTTP_SESSION_VARS['SchoolID'];
  $UserID = $HTTP_SESSION_VARS['UserID'];
  $cc = $HTTP_POST_VARS['coursecode'];
  $sid = $HTTP_POST_VARS['studentid'];
  $clearme = $HTTP_POST_VARS['clearme'];
  $notes = $HTTP_POST_VARS['notes']; 
  $noteid = $HTTP_POST_VARS['noteid'];


 include ("basmaticonstants.php");
 if ($LoginType != "T" && $LoginType != "A"){
   echo("You must log-in to use this feature.");
   exit;
 }


 if ($datamethod == "odbc"){ 
	echo "This feature is only availble in the MySQL version...";
	exit;
 }


if ($datamethod == "mysql"){
  fnOpenDB();
  if ($noteid != -1){  //delete the old note if one exists...
	  $mysql_query = "DELETE from PRIVNOTES where id = $noteid;";
	  $sql_result = mysql_query($mysql_query,$link);
  }
 if ($clearme != "on"){
	  $mysql_query = "INSERT into PRIVNOTES (sid, cc, schoolid, notes) values ($sid, '$cc', '$SchoolID', '$notes');";
	  $sql_result = mysql_query($mysql_query,$link);
 }
} // end of mysql

echo '<LINK rel="stylesheet" type="text/css" href="style.css" title="style1">';
echo "Your note has been submitted...";
//echo $mysql_query;

function fnOpenDB(){
 global $link;
 include ("basmaticonstants.php");
 if ($datamethod == "mysql"){
  $link = mysql_connect($databaseserver,$datausername,$datapassword) or die
("Couldn't connect to server");
  if (! mysql_select_db($databasename,$link)){
   echo ("<B><br>Error:  Cannot Select DB</b> -- please contact administrator.");
  }
 }
}


function fnCloseDB(){
 global $link;

  if ($datamethod == "mysql"){
   mysql_close($link);
 }
}




?>