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

//For some reason, the Windows version needs to initialize a session before logging in???
 $_SESSION['LoginType'] = "Z";




  $sql_query = "SELECT * from SCHOOLS order by school_name;";
  $mysql_query = $sql_query;
//   echo $mysql_query;
  if ($datamethod == "odbc"){
  $link = odbc_connect($databasename,$datausername,$datapassword);
  $sql_statement = odbc_prepare($link,$sql_query);
  $sql_result = odbc_execute($sql_statement);
  if (odbc_num_rows($sql_statement)!=0){;
   while (odbc_fetch_row($sql_statement)){
    $row_n++;


    for ($i = 1; $i <= odbc_num_fields($sql_statement);$i++){
     //echo (odbc_field_name($sql_statement,$i));
     $grade_array[odbc_field_name($sql_statement,$i)][$row_n]=odbc_result($sql_statement,odbc_field_name($sql_statement,$i));
    }

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

?>
<html>
<head>
<title>Login to Basmati</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body background="bbrick.jpg" link="white" vlink="white">

<center><font size="+2" color="yellow">Login to Basmati</font></center>
<p>
<form method="post" action="studentlogin.php">
<table border="1" cellpadding="30" align="center">
<tr>
<td><center><input type="text" size="6" name="sid">
<font color="yellow" size="+1">
<br>
<b>Student ID</b>
<br><br>
<input type="password" size = "6" name="pword">
<br>Password</b></font></center>
</td>

<?php

if ($usetextbox == 0) {
 echo("<td><font color=\"yellow\"><b>Select School</b><br><font color=\"white\">");
 for ($i=1;$i <= $row_n; $i++){
  $ck = "";
  if ($i==1){
   $ck=" checked";
  }
  echo("<input type=\"radio\" name=\"radio1\" value=\"". $grade_array[school_id][$i] ."\"". $ck . ">".PHP_EOL);
  echo("<b>" . $grade_array[school_name][$i] . "</b><br>");
 }
} else {
  echo("<td><font color=\"yellow\"><center><input type=text name=\"radio1\"><br><b>School ID</b></center>");
}
?>
<p>
<input type="submit" value="Check Grades">

</font></td></tr>
</table>
</form>

<?php
 if ($privacyurl != "") {
	echo "<P><CENTER><A HREF=\"$privacyurl\">Privacy Policy</a>";
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
