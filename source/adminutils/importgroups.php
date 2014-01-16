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

require_once('../global.php');

$schoolid = $_POST['schoolid'];
$groupid = $_POST['groupid'];
 require("../basmaticonstants.php");

if ($_SESSION['LoginType'] != "A" . $districtid){
  echo "<body bgcolor=#cacaff><font face='verdana,arial,helvetica'><b>You must log-in to use this feature.</font>";
  exit;
}

//Connect to the database...

 //Connect to the database
 $link = mysql_connect($databaseserver,$datausername,$datapassword);


//Select the appropriate database
 if (!mysql_select_db($databasename,$link)){
  echo("Can't connect to the database");
  exit;
 }

//Indicate success..
  echo "Connected to database using $link";

//If want to clear all data...
//  if ($clearall == "on"){
//    mysql_query("DELETE from student;",$link);
//    mysql_query("DELETE from mg;",$link);
//    echo "<br>Cleared all data!<br>";
//  }

//Process uploaded file...
$delim = chr(9);
$nl = chr(13);

//Check to make sure we received a file
$file = $_FILES['userfile']['tmp_name'];


if ($file=="none" || $file == ""){
  echo("You did not submit a file.");
  exit;
}

//Make sure we can open the file
if (!($fp = fopen($file,"r"))){
  echo("could not open file for reading");
  exit;
}

echo("<pre>");
$tab = chr(9);
$q = chr(34);
//Read the file line-by-line and separate each sid with a comma...
while (!feof($fp)){
 $j++;
 $stuff =  fgetcsv($fp,4096,$tab);
 $singlesid = $stuff[0];
 if (trim($singlesid) != "") $sidlist[$j] = $singlesid;
}
//Remove the final comma...

 $finallist = implode(",", $sidlist);

 //Remove the old record...
   $sql = "DELETE from GROUPS where groupname = $q$groupid$q and schoolid = $q$schoolid$q;";
   mysql_query($sql,$link);
   echo $sql . "<br>";


 //Start creating the SQL query that we'll use to populate the database...
 $sql = "INSERT into GROUPS SET
     groupname = $q$groupid$q,
     groupsids = $q$finallist$q,
     schoolid = $q$schoolid$q
     ;";

     echo $sql . "<br>";
     $records++;
     if (!mysql_query($sql,$link)){
       echo "Error adding $stuff[0]-$stuff[1]-$stuff[2] to the student table.<br>";
       $records--;
      }


//Close the file
 fclose($fp);

//Close the database
 mysql_close($link);

//Report the number of records added to the database...
  echo "</pre>$records record(s) were added/modified.";
?>
