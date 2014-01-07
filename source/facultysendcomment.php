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

session_start();
  $SchoolID =    $_SESSION['SchoolID'];
  $UserID = $_SESSION['UserID'];
  $comment = $HTTP_POST_VARS['comment'];

 include ("basmaticonstants.php");


 if ($_SESSION['LoginType'] != "T" && $_SESSION['LoginType'] != "A"){
   echo("You must log-in to use this feature.");
   exit;
 }

 echo ("<html>");
 echo '<LINK rel="stylesheet" type="text/css" href="style.css" title="style1">';
 echo ("<font size=+1>Memo-Maker</font><hr>");


//Pull existing contents from database
 $comment = ereg_replace("'","`",$comment);
 $sql_query = sprintf("UPDATE CLIENTS SET webinfo = '%s' WHERE client_id = '" . $UserID . "'" ,addslashes($comment));
 $mysql_query = sprintf("UPDATE CLIENTS SET webinfo = '%s' WHERE client_id = '" . $UserID . "'" ,addslashes($comment));

 if ($datamethod == "odbc"){
     $link = odbc_connect($databasename,$datausername,$datapassword);
  $sql_statement = odbc_prepare($link,$sql_query);
  $sql_result = odbc_execute($sql_statement);

  odbc_free_result($sql_statement);
  odbc_close($link);
//Also change the date
$sql_query = sprintf("UPDATE CLIENTS SET webinfodate = '%s' WHERE client_id = '" . $UserID . "'" ,strftime(date("m/d/Y h:iA")));

  $sql_statement = odbc_prepare($link,$sql_query);
  $sql_result = odbc_execute($sql_statement);


} //odbc stuff...

 if ($datamethod == "mysql"){
  fnOpenDB();
  $sql_result = mysql_query($mysql_query,$link);
  echo mysql_error($link);

 // Also Change the date
   $mysql_query = sprintf("UPDATE CLIENTS SET webinfodate = '%s' WHERE client_id = '" . $UserID . "'" ,strftime(date("Y/m/d h:iA")));
   $sql_result = mysql_query($mysql_query,$link);
    echo mysql_error($link);

   fnCloseDB();
  } // end of mysql




  echo("<i>The following comments were submitted...</i><p><pre>");
  echo stripslashes($comment);
  echo ("</pre>");



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
