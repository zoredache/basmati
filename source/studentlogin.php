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
// $Id: studentlogin.php,v 1.1 2001/10/10 03:05:45 basmati Exp $


  include ("basmaticonstants.php");
  include ("basmatifunctions.php");

  $sid = $HTTP_POST_VARS['sid'];
  $radio1 = $HTTP_POST_VARS['radio1'];
  $pword = $HTTP_POST_VARS['pword'];


  if (trim($sid) == ""){
    echo("You must supply a student ID");
    exit;
  }
  if ($radio1 == ""){
    echo("You must select a school.");
    exit;
  }
  $sid = intval($sid);
  $sidfromform = $sid;

  $sql_query = "SELECT * from PERSONAL where sid = "  . $sid. " and schoolid = '" . $radio1 . "' and password = '$pword';";
  $mysql_query = $sql_query;
  //echo $sql_query;
  if ($datamethod == "odbc"){
  $link = odbc_connect($databasename,$datausername,$datapassword);
  $sql_statement = odbc_prepare($link,$sql_query);
  $sql_result = odbc_execute($sql_statement);
  if (odbc_num_rows($sql_statement)!=0){;
   while (odbc_fetch_row($sql_statement)){
    $row_n++;
//    for ($i = 1; $i <= odbc_num_fields($sql_statement);$i++){
    // echo (odbc_field_name($sql_statement,$i));
//     $grade_array[odbc_field_name($sql_statement,$i)][$row_n]=odbc_result($sql_statement,odbc_field_name($sql_statement,$i));
//    }

  } //If numrows != 0
  odbc_free_result($sql_statement);
  odbc_close($link);
 }
}//end of ODBC

if ($datamethod == "mysql"){
  fnOpenDB();
  $sql_result = mysql_query($mysql_query,$link);
  //echo mysql_error($link);
  if (mysql_num_rows($sql_result)!=0){
    $nfields = mysql_num_fields($sql_result);
    for($r==0;$r<mysql_num_rows($sql_result);$r++){
     $nrows++;
     for ($i=0;$i<$nfields;$i++){
       $fieldname = mysql_field_name($sql_result,$i);
       $fieldvalu = mysql_result($sql_result,$r,mysql_field_name($sql_result,$i));
       $grade_array[$fieldname][$nrows]=$fieldvalu;
     } // for $i
    } //for $r
    mysql_free_result($sql_result);
   }//If numrows != 0
   fnCloseDB();
   $row_n = $nrows;
  } // end of mysql




 if ($row_n != 0){

   session_start();
   $_SESSION['LoginType'] = "S";
   $_SESSION['SchoolID'] = $radio1;
   $_SESSION['sid'] = $sidfromform;
   $_SESSION['CurrentSID'] = $sidfromform;


//Log the login to a file...
   writelog("IN-STU",$sid,$_SESSION['SchoolID']);

   header("Location: showreportcard.php");
 } else {
   echo ("INVALID LOGIN");
 }

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


