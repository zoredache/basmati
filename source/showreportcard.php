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

  if ($_SESSION['LoginType'] == "S") {
  	$sid = $_SESSION['sid'];
  } else {
  	$sid = $HTTP_GET_VARS['sid'];
  }

  $_SESSION['CurrentSID'] = $sid;

  include ("basmaticonstants.php");

  $loginvalue = 0;
  if ($_SESSION['LoginType'] == "T") {
    $loginvalue = 1;
  }
  if ($_SESSION['LoginType'] == "S") {
    $loginvalue = 1;
  }
 if ($_SESSION['LoginType'] == "A") {
    $loginvalue = 1;
  }

 if ($loginvalue != 1){
   echo("You must log-in to use this feature.");
   exit;
 }


  $school = $_SESSION['SchoolID'];
  $sid = intval($sid);
  //check for invalid student ID
  if (intval($sid)<=0){
   echo("Invalid Student ID");
   exit;
  }



  echo("<body background=cork.jpg>");
  echo '<LINK rel="stylesheet" type="text/css" href="style.css" title="style1">';
  echo("<font size=+2 color=white>");
  echo("<center><b>Basmati Grade Report for $sid</b><p>");

 //determine school...
  $sql_query = "SELECT * from SCHOOLS where school_id = '" . $school ."'";
   //echo $sql_query;
   if ($datamethod == "odbc"){
   $link = odbc_connect($databasename,$datausername,$datapassword);
   $sql_statement = odbc_prepare($link,$sql_query);
   $sql_result = odbc_execute($sql_statement);
   if (odbc_num_rows($sql_statement)!=0){
     odbc_fetch_row($sql_statement);
     $schoolname = odbc_result($sql_statement,school_name);
     odbc_free_result($sql_statement);
    }
   }
   if ($datamethod == "mysql"){
    fnOpenDB();
    $sql_result = mysql_query($sql_query,$link);
    $schoolname = mysql_result($sql_result,0,school_name);
    fnCloseDB();
   }


  echo("</font><font color=white><i> $schoolname </font></i></center><p>");



  $sql_query = "SELECT * from COURSEINFO inner join GMSCORES on COURSEINFO.cc = GMSCORES.cc where GMSCORES.sid = "  . $sid. " and GMSCORES.schoolid = '" . $school . "' order by GMSCORES.cc;";
  $mysql_query = "SELECT COURSEINFO.cc as cc, COURSEINFO.coursename as coursename, COURSEINFO.facultyname as facultyname,
                 COURSEINFO.modified as 'modified', GMSCORES.grade as grade, GMSCORES.percent as percent, COURSEINFO.email as email, COURSEINFO.misc as misc
                 FROM COURSEINFO inner join GMSCORES on GMSCORES.cc = COURSEINFO.cc and GMSCORES.schoolid = COURSEINFO.schoolid where GMSCORES.sid = "  . $sid. " and GMSCORES.schoolid = '" . $school . "' order by GMSCORES.cc;";


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
    $hyperlink = odbc_result($sql_statement,email);
    $grade_array["hmail"][$row_n] = $hyperlink;

    if (!eregi("http://",$hyperlink)){
      $hyperlink = "mailto:" . $hyperlink;
    }
    $grade_array["email"][$row_n] = $hyperlink;

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
    for($r=0;$r<mysql_num_rows($sql_result);$r++){
     $nrows++;
     for ($i=0;$i<$nfields;$i++){
       $fieldname = mysql_field_name($sql_result,$i);
       $fieldvalu = mysql_result($sql_result,$r,mysql_field_name($sql_result,$i));
       $grade_array[$fieldname][$nrows]=$fieldvalu;
     } // for i
       $hyperlink = mysql_result($sql_result,$r,email);
       $grade_array["hmail"][$nrows] = $hyperlink;

       if (!eregi("http://",$hyperlink)){
        $hyperlink = "mailto:" . $hyperlink;
       }
       $grade_array[email][$nrows] = $hyperlink;

    } //for $r
    mysql_free_result($sql_result);
   }//If numrows != 0
   fnCloseDB();
   $row_n = $nrows;
  } // end of mysql


//echo("<hr>".$grade_array[email][1]."<hr>");





//Now that we have an array, cycle through and create table... (independent of database)

  echo("<center>");
  echo("<table border=1 bgcolor=white><tr bgcolor=#ffff99>");
  echo("<td><b>Class Name</b></td><td><b>Instructor</b></td><td><b>Last
Updated</b></td><td><b>Grade</b></td><td><b>Gradebook</b></td><td><b>Class<br>Notes</b></td>");
   echo("</tr>");


  for ($i = 1; $i <= $row_n ;$i++){
//Apply any of the misc3 tags...
    $hlink = "";

    if (eregi("-mail",$grade_array[misc][$i])){
     $hlink = $grade_array[facultyname][$i];
    } else{
     $hlink = "<a href=" . $grade_array[email][$i]. ">".$grade_array[facultyname][$i]."</a>";
    }
    if (eregi("-percent",$grade_array[misc][$i])){
      $grade_array[percent][$i] = " ";
    } else {
      $grade_array[percent][$i] = $grade_array[percent][$i] . "%";
    }

    echo("<tr bgcolor=white>");
    echo("<td>" . $grade_array[coursename][$i] . "<br><font size=-1>(" . $grade_array[cc][$i].  ")</font></td>");
    echo("<td>". $hlink . "</td>");
    echo("<td  align=center width=80>" . $grade_array[modified][$i] . "</td>");
    echo("<td align=center><font size=+2>" . $grade_array[grade][$i] . "</font><br>");
    echo("" . $grade_array[percent][$i]. "</font></td>");
    echo("<td align=center valign=center><form method=get action=showgradebook.php><input type=hidden name=cc value =\"".$grade_array[cc][$i]."\">");
    echo("<b><Br><input type=image src = gbook.gif>"   . "</form></td>");
    echo("<td><center><form method=post action=showteachermemo.php target=new><input type=submit value=View>");
    echo("<input type=hidden value=" . chr(34) . $grade_array[hmail][$i]. chr(34) . " name=TchrUserID></td>");
    echo("<input type=hidden value=". chr(34) . $hlink . chr(34) . " name=Namesake></td>  </form>");


    echo("</tr>");
  }
  echo ("</table>");

   if ($enableplugins == 1){
       include("plugins.php");
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
