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

//This file allows the editing of a particular row in a table

//Check security
session_start();
$tablename = $_GET['tablename'];
$editrow = $_GET['editrow'];

//Basmati Conversions...
include("basmaticonstants.php");
$dbserv = $databaseserver;
$dbuser = $datausername;
$dbpass = $datapassword;
$dbname = $databasename;

if ($_SESSION['LoginType'] != "A" . $districtid){
  echo "<body bgcolor=#cacaff><font face='verdana,arial,helvetica'><b>You must log-in to use this feature.</font>";
  exit;
}


//Connect to the database...


 //Connect to the database
 $link = mysql_connect($dbserv,$dbuser,$dbpass);

 //Select the appropriate database
 if (!mysql_select_db($dbname,$link)){
  echo("Can't connect to the database");
  exit;
 }

  $sql = "SELECT * from $tablename where id = $editrow;";
  $result = mysql_query($sql,$link);
  $nresults = mysql_num_rows($result);

  echo ("<form method=post action=sqlchange.php>");
//Start printing the edit boxes...
  echo ("<table border=1 cellspacing=0 cellpadding=0>");
  for ($i=0;$i<mysql_num_fields($result);$i++){
    echo ("<tr>");
    echo "<td bgcolor=#eeeeee><b>" . mysql_field_name($result,$i) . "</b></td>";
    echo "<input type=hidden name=dt[". trim($i) . "] value=" . mysql_field_type($result,$i) . ">";
    echo "<input type=hidden name=dn[". trim($i) . "] value=" . mysql_field_name($result,$i) . ">";
   if (strtolower(mysql_field_name($result,$i)) == "id"){
      echo "<td bgcolor=#eeeeee><b>" . mysql_result($result,0,$i) . "</b></td>";
      echo "<input type=hidden name=dv[". trim($i) . "] value='" . mysql_result($result,0,$i) . "'>";
    } else{
      echo "<td><input type=text name=dv[". trim($i) . "] value='" . mysql_result($result,0,$i) . "'></td>";

    }

    echo ("</tr>");
  }
 echo ("<input type=hidden name=tablename value=$tablename>");
 echo ("<input type=hidden name=editrow value=$editrow>");
 echo ("</table>");
 echo ("<input type=hidden name=maxval value=" . trim($i) . ">");
 echo ("<input type=submit value='Submit Changes'>");
 echo ("</form>");
 echo ("<HR>");
 //echo ("<form method=post action=sqldelete.php>");
 //echo ("<input type=hidden name=tablename value=$tablename>");
 //echo ("<input type=hidden name=editrow value=$editrow>");
 echo ("<input type=button onClick = confirmation($editrow,'$tablename') value='Delete Record'>");
 //echo ("</form>");

 mysql_close($link);


?>

<script language=JavaScript>
 function confirmation(idcode,tname){
  yn = confirm("Do you really want to remove this record from the system? (" + idcode + ")");
  if (!yn) {
    alert("Record was not dropped.");
  } else {
    document.location = "sqldelete.php?editrow=" + idcode + "&tablename=" + tname;
  }

 }

</script>

