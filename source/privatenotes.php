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
  $cc = $_GET['cc'];
  $sid = $_GET['sid'];

 include ("basmaticonstants.php");
 if ($_SESSION['LoginType'] != "T" && $_SESSION['LoginType'] != "A"){
   echo("You must log-in to use this feature.");
   exit;
 }

  echo '<LINK rel="stylesheet" type="text/css" href="style.css" title="style1">';
  echo "<b>Private Note for student #$sid for course ID $cc</b>";
  $mysql_query  = "SELECT * from PRIVNOTES where sid = $sid and cc = '$cc' and schoolid = '$SchoolID';";

 if ($datamethod == "odbc"){ 
	echo "This feature is only availble in the MySQL version...";
	exit;
 }


 if ($datamethod == "mysql"){
  fnOpenDB();
  $sql_result = mysql_query($mysql_query,$link);
//  echo mysql_error($link);
//  echo mysql_num_rows($sql_result);
  if (mysql_num_rows($sql_result)!=0){
   $note = mysql_result($sql_result,0,notes);
   $noteid = mysql_result($sql_result,0,id);
   fnCloseDB();
  } else {
   $note = "";
   $noteid = -1;
  }

 echo "<form name=form1 method=post action=privatenotesubmit.php>";
 echo "<textarea rows=20 cols=40 name=notes>$note</textarea>";
 echo "<br>";
 echo "<input type=submit value='Submit Private Note to Student'>";
 echo "<input type=checkbox name = clearme>Clear Note for This Student";
 echo "<input type=hidden name='coursecode' value='$cc'>";
 echo "<input type=hidden name='studentid' value='$sid'>";
 echo "<input type=hidden name='noteid' value = '$noteid'>";
 echo "</form>";


 } // end of mysql









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


<hr>
Quick Notes...
<br>
<table border=1>
<tr>
<td><input type=button onClick=Quicknote("EAE") value='EAE'></td>
<td><input type=button onClick=Quicknote("EWH") value='EWH'></td>
<td><input type=button onClick=Quicknote("WII") value='WII'></td>
<td><input type=button onClick=Quicknote("WNI") value='WNI'></td>
<td><input type=button onClick=Quicknote("FOT") value='FOT'></td>
<td><input type=button onClick=Quicknote("FDR") value='FDR'></td>
</tr>
<tr>
<td><input type=button onClick=Quicknote("LOE") value='LOE'></td>
<td><input type=button onClick=Quicknote("FNM") value='FNM'></td>
<td><input type=button onClick=Quicknote("FAD") value='FAD'></td>
<td><input type=button onClick=Quicknote("MIA") value='MIA'></td>
<td><input type=button onClick=Quicknote("PAT") value='PAT'></td>
<td><input type=button onClick=Quicknote("CLR") value='CLR'></td>
</tr>
</table>


<script language=javascript>
function Quicknote(j){

       if (j == "EAE") document.form1.notes.value = document.form1.notes.value + "Excellent attitude and effort. ";
       if (j == "EWH") document.form1.notes.value = document.form1.notes.value + "Excellent work habits. ";
       if (j == "WII") document.form1.notes.value = document.form1.notes.value + "Work is improving. ";
       if (j == "WNI") document.form1.notes.value = document.form1.notes.value + "Work habits need improving. ";
       if (j == "FOT") document.form1.notes.value = document.form1.notes.value + "Frequently off task. ";
       if (j == "FDR") document.form1.notes.value = document.form1.notes.value + "Frequently disrespectful. ";

       if (j == "LOE") document.form1.notes.value = document.form1.notes.value + "Lack of effort. ";
       if (j == "FNM") document.form1.notes.value = document.form1.notes.value + "Forgets needed materials. ";
       if (j == "FAD") document.form1.notes.value = document.form1.notes.value + "Frequently a disturbance. ";
       if (j == "MIA") document.form1.notes.value = document.form1.notes.value + "Missing or incomplete assignments. ";
       if (j == "PAT") document.form1.notes.value = document.form1.notes.value + "Poor attendance or tardiness. ";
       if (j == "CLR") document.form1.notes.value =  "";
}

</script>
<hr>
<i>Information about Private Notes:</i>  If possible, please do not use the student's name in these
notes.  To delete an entire class' private notes, click the "Show Current Classes" link and push
the "DEL" button under the "Remove Class" column.
