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
// $Id: showclasses.php,v 1.1 2001/09/22 22:18:02 basmati Exp $

 $LoginType = "";
 session_start();

  $LoginType = $HTTP_SESSION_VARS['LoginType'];
  $SchoolID =    $HTTP_SESSION_VARS['SchoolID'];
  $UserID = $HTTP_SESSION_VARS['UserID'];

 include ("basmaticonstants.php");
 if ($LoginType != "T" && $LoginType != "A"){
   echo("You must log-in to use this feature.");
   exit;
 }
 echo "<body bgcolor=white>Current User: " .$UserID. " (". $SchoolID . ")<p>";


//Get Data into an array...
  $sql_query = "SELECT cc as COURSECODE, coursename as [CLASS NAME], facultyname as [FACULTY NAME], PERIOD, modified as [LAST UPDATE] FROM COURSEINFO WHERE email = '" . trim($UserID) . "' order by cc;";
  $mysql_query = "SELECT cc as COURSECODE, coursename as 'CLASS NAME', facultyname as 'Faculty Name' , period, modified as 'Last Updated' FROM COURSEINFO WHERE email = '" . trim($UserID) . "' order by cc;";
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
} //odbc stuff...


 if ($datamethod == "mysql"){
  fnOpenDB();
  $sql_result = mysql_query($mysql_query,$link);
  echo mysql_error($link);
  if (mysql_num_rows($sql_result)!=0){
    $nfields = mysql_num_fields($sql_result);
    for ($i=0;$i<$nfields;$i++){
      $titles[$i+1] = mysql_field_name($sql_result,$i);
    } // for
    while (mysql_fetch_row($sql_result)){
    $nrows++;
     for ($i=0;$i<$nfields;$i++){
       $ary[$nrows][$i+1] = mysql_result($sql_result,$nrows-1,$i);
     } // for
    } //while
    mysql_free_result($sql_result);
   }//If numrows != 0
   fnCloseDB();
  } // end of mysql


//Now print the table (entirely contained in $ary...
  echo("<table border=0><tr bgcolor=yellow>");

//First the headers...
  for ($f =1;$f<=$nfields;$f++){
   echo("<th>".$titles[$f]."</th>");
  }

  echo ("<th>Get Passwords</th>");
  echo ("<th>Remove<br>Class</th>");

//Now the data...
  for ($r=1;$r<=$nrows;$r++){
   if (intval($r/2) == $r/2){
     $rowcolor = "99ff99";
    } else {
     $rowcolor="9999ff";
    }
   echo ("<tr bgcolor=$rowcolor>");
   for ($f=1;$f<=$nfields;$f++){
    echo("<td>".$ary[$r][$f]."</td>");
   }
   echo("<form method=get action=getpasswords.php><td valign=middle><input type=submit value=Passwords><input type=hidden name=cc value=" .$ary[$r][1]. "></td></form>");
   echo("<form><td valign=middle align=center><input type=button onClick = confirmation('" . $ary[$r][1]. "') value=DEL></td></form>");

   echo ("</tr>");
  }

  echo ("</table>");


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

<script language=JavaScript>
 function confirmation(classcode){
  yn = confirm("You do not need to drop a class if you simply want to update a class.  You should only use this utility when you no longer want the class in the system permanently.  Do you really want to remove this class from the system? (" + classcode + ")");
  if (!yn) {
    alert("Course was not dropped.");
  } else {
    document.location = "dropclass.php?dropid=" + classcode;
  }

 }

</script>


