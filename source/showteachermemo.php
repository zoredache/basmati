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
// $Id: showteachermemo.php,v 1.1 2001/10/10 03:05:45 basmati Exp $

session_start();

 include ("basmaticonstants.php");
 $TchrUserID = $HTTP_POST_VARS['TchrUserID'];
 $Namesake = $HTTP_POST_VARS['Namesake'];


 echo ("<html><body bgcolor=white>");


//Pull existing contents from database
 $sql_query = "SELECT webinfo, webinfodate FROM CLIENTS WHERE client_id = '" . $TchrUserID . "'";
 $mysql_query = "SELECT webinfo, webinfodate FROM CLIENTS WHERE client_id = '" . $TchrUserID . "'";


 if ($datamethod == "odbc"){
     $link = odbc_connect($databasename,$datausername,$datapassword);
  $sql_statement = odbc_prepare($link,$sql_query);
  $sql_result = odbc_execute($sql_statement);

  if (odbc_num_rows($sql_statement)!=0){
   $nfields = odbc_num_fields($sql_statement);
   for ($i=1; $i<=odbc_num_fields($sql_statement);$i++){
     $titles[$i] = odbc_field_name($sql_statement,$i);
    } //for
   while (odbc_fetch_row($sql_statement)){
    $nrows++;
    for ($i=1; $i<=odbc_num_fields($sql_statement);$i++){
     $ary[$nrows][$i] = odbc_result($sql_statement,$i);
    } //for
   } //while
  } //If numrows != 0
  odbc_free_result($sql_statement);
  odbc_close($link);
  $commenttext = $ary[1][1];
  $commentdate = $ary[1][2];
} //odbc stuff...

 if ($datamethod == "mysql"){
  fnOpenDB();
  $sql_result = mysql_query($mysql_query,$link);
  echo mysql_error($link);
  if (mysql_num_rows($sql_result)!=0){
    $nfields = mysql_num_fields($sql_result);
    while (mysql_fetch_row($sql_result)){
    $nrows++;
     for ($i=0;$i<$nfields;$i++){
       $ary[$nrows][$i+1] = mysql_result($sql_result,$nrows-1,$i);
     } // for
    } //while
  $commenttext = $ary[1][1];
  $commentdate = $ary[1][2];

    mysql_free_result($sql_result);
   }//If numrows != 0
   fnCloseDB();
  } // end of mysql



 echo ("<i><font size=+1>$Namesake submitted the following comments on $commentdate...</font></i><hr><pre>");
 echo (stripslashes(stripslashes($commenttext)));
 echo ("</pre><br>");


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

