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
// $Id: facultylogin.php,v 1.1 2001/09/22 22:17:58 basmati Exp $

 global $school_id;
 $uname = $HTTP_POST_VARS['uname'];
 $pword = $HTTP_POST_VARS['pword'];

 include ("basmaticonstants.php");
 include ("basmatifunctions.php");

 if (trim($emaildomain) != "") {
  	$uname = trim($uname) . "@" . $emaildomain;
 }

 fnopenDB();
 if (VerifyAccount()=="EXIT"){
  setcookie("LoginType","");
  echo("<font color=red>BAD USERNAME or PASSWORD</FONT>");
  exit;
 }
//Start Session or use cookies...
  session_register("LoginType");
  session_register("SchoolID");
  session_register("UserID");
  $LoginType = "T";
  $SchoolID = $school_id;
  $UserID = $uname;
  $HTTP_SESSION_VARS['LoginType'] = "T";
  $HTTP_SESSION_VARS['SchoolID'] = $school_id;
  $HTTP_SESSION_VARS['UserID'] = $uname;


 echo("<body bgcolor=white><center><img src=basmati.jpg><br><font size=+1 color=green>You are now logged in.</font></center>");
 echo "<hr>";
 echo $announcement;

  fncloseDB();
 //Write the information to a log file...
  writelog("IN-FAC",$uname,$school_id);




function VerifyAccount(){
 global $classinfo;
 global $datamethod,$link;
 global $uname, $pword;
 global $school_id;
 $sql = "select * from CLIENTS where client_id = '" . trim($uname).
        "' and client_pw = '" . trim($pword) . "'";
 if ($datamethod == "odbc"){
  $sql_statement = odbc_prepare($link,$sql);
  $sql_result = odbc_execute($sql_statement);
  $school_id = odbc_result($sql_statement,client_school);
  odbc_free_result($sql_statement);
 } // end of odbc
 if ($datamethod == "mysql"){
  $school_id = "";
  $sql_result = mysql_query($sql,$link);
  $rowcount = (mysql_num_rows($sql_result));
  if ($rowcount != 0){

    $school_id = mysql_result($sql_result,0,'client_school');
    mysql_free_result($sql_result);
   }
  } // end of mysql

 if($school_id == ""){
  return "EXIT";
 }
  return $school_id;
}




function fnOpenDB(){
 global $link;
 include ("basmaticonstants.php");
 if ($datamethod == "odbc"){
  $link = odbc_connect($databasename,$datausername,$datapassword);
 } // end of odbc
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

 if ($datamethod == "odbc"){
   odbc_close($link);
 } // end of odbc
 if ($datamethod == "mysql"){
   mysql_close($link);
 }
}


?>


