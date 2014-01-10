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

//This file displays results of a SQL query

require_once('global.php');

$returnstyle = $_GET['returnstyle'];
$groupid = $_GET['groupid'];
$percent = $_GET['percent'];
$groupcriteria = $_GET['groupcriteria'];

//Basmati Conversions...
if ($datamethod == "odbc") {
  echo ("This feature is only available with the MySQL database server.");
  exit;
}
$dbserv = $databaseserver;
$dbuser = $datausername;
$dbpass = $datapassword;
$dbname = $databasename;



//Connect to the database...

 //Connect to the database
 $link = mysql_connect($dbserv,$dbuser,$dbpass);

 //Select the appropriate database
 if (!mysql_select_db($dbname,$link)){
  echo("Can't connect to the database");
  exit;
 }

//Pull out the sidlist and schoolid from the GROUPS table...
  $sql = "SELECT * from GROUPS where groupname = '$groupid';";
  $result = mysql_query($sql,$link);
  $nresults = mysql_num_rows($result);
  if ($nresults == 0) {
  	echo "Could not locate a group name called '$groupid'.";
    exit;
  }
  $sidlist = mysql_result($result,0,groupsids);
  $schoolid = mysql_result($result,0,Schoolid);




if ($groupcriteria == 1){
	$symbol = ">=";
} else {
	$symbol = "<=";
}


//$sidlist = "8000,8001,8002";
//$schoolid = "HS";


$sqltext = "SELECT PERSONAL.sid as sid, PERSONAL.last, PERSONAL.first, GMSCORES.cc as cc,
           COURSEINFO.facultyname, COURSEINFO.coursename, GMSCORES.grade as lettergrade, GMSCORES.percent as percent, COURSEINFO.modified from PERSONAL
           inner join GMSCORES on GMSCORES.sid = PERSONAL.sid inner join COURSEINFO on COURSEINFO.cc
           = GMSCORES.cc where GMSCORES.sid in ($sidlist) and PERSONAL.schoolid = '$schoolid' and
           GMSCORES.schoolid = '$schoolid' and COURSEINFO.schoolid = '$schoolid' and GMSCORES.percent $symbol $percent order by last, first, sid, cc;";



if ($_SESSION['LoginType'] != "A" && $_SESSION['LoginType'] != "T"){
  echo "<body bgcolor=#cacaff><font face='verdana,arial,helvetica'><b>You must log-in to use this feature.</font>";
  exit;
}

if (strtoupper(substr($sqltext,0,6))!= "SELECT"){
  echo "<body bgcolor=#cacaff><font face='verdana,arial,helvetica'><b>You may only submit SELECT style queries using this tool.</font>";
  exit;
}


//Take care of quote issue with SQL query...
 $slash = addslashes("\\");
 $sqltext = ereg_replace($slash,$emptys,$sqltext);



//Headers, etc...
 echo ("<body bgcolor=#cacaff>");
 echo ('<font face="verdana, arial,helvetica" size=2>');
 //echo ('<b>'.$sqltext.'</b><hr color=black  align=left>');
 echo ('<font face="verdana, arial,helvetica" size=1>');

 echo ('
  <style type="text/css">
  <!--
    A:link {color:"#ff0000";}
    A:visited {color:"#ff0000";}
    A:hover {color:"#008800";}
   -->
  </style>');

//Determine the table name by searching for the word after "FROM"
  $fromloc = strpos(strtolower($sqltext),"f");
  $newtext = trim(substr($sqltext,$fromloc+4)). chr(32);
  for ($i = 0; $i <= strlen($newtext); $i++){
    if (substr($newtext,$i,1) == ";" || substr($newtext,$i,1) == " ") {
     $choploc = $i;
     break;
    }
  }
  $tablename = substr($newtext,0,$choploc);



  $sql = $sqltext;
  $result = mysql_query($sql,$link);
  $nresults = mysql_num_rows($result);
  echo ("<b>Your query for the group '$groupid' produced $nresults result(s)</b>");
  echo ("<hr color=black>");

//Pluck off the table-name for use in case we need to edit the results...


//Start printing the table...
  $idrow = -1;
  echo ("<table border=0 cellspacing=1 cellpadding=1>");
  echo ("<tr bgcolor=white>");
  for ($i=0;$i<mysql_num_fields($result);$i++){
   echo ("<td><font size=1><b>" . mysql_field_name($result,$i). "</b></font></td>");
   if (mysql_field_name($result,$i) == "id") $idrow = $i;
  }
  echo ("</tr>");

   for ($r=0;$r<$nresults;$r++){
        $rowcounter = !$rowcounter;
        if ($rowcounter){
          $rowcolor = "#efffff" ;
        } else {
          $rowcolor = "#ffefff";
        }
        echo ("<tr bgcolor=$rowcolor>");
         for ($i=0;$i<mysql_num_fields($result);$i++){
          echo ("<td><font size=1>");
          if ($i == $idrow) echo ("<a href=sqledit.php?tablename=$tablename&editrow=". mysql_result($result,$r,$i).">");
          echo  mysql_result($result,$r,$i);
          if ($i == $idrow) echo ("</a>");
          echo ("</font></td>");
        }
        echo ("</tr>");


   }
   echo ("</table>");

 mysql_close($link);
?>
