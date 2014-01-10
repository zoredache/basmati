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

//Check security
session_start();

$schoolid = $_POST['schoolid'];
$defaultpw = $_POST['defaultpw'];

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
$userfile = $HTTP_POST_FILES;
$file = $userfile['userfile']['tmp_name'];


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
//Read the file line-by-line
while (!feof($fp)){
 $stuff =  fgetcsv($fp,4096,$tab);


 //Determine if an id row already exists in STUDENT... if so, we'll add the id to
 //the SQL statement and update the data.  If not, we won't bother adding the id element.
 $sql = "SELECT id FROM PERSONAL where sid = $stuff[0] and schoolid = $q$schoolid$q ";
 //echo "<b>" . $sql . "</b><br>";
 $result = mysql_query($sql,$link);
 if ($result){
 	if (mysql_num_rows($result) !=0){// we have a result... need to specify which record.  Leave password alone!
        $addphrase = "";
        $id = mysql_result($result,0,id);
        $verbphrase = "UPDATE ";
        $wherephrase = " WHERE id = $id ";
 	} else {
     	$verbphrase = "INSERT INTO ";
 		$addphrase = " password = $q$defaultpw$q, ";
        $wherephrase = "";
 	}
 } else {
        $verbphrase = "INSERT INTO ";
 		$addphrase = " password = $q$defaultpw$q, ";
        $wherephrase = "";
 }


 //Start creating the SQL query that we'll use to populate the database...

 if ($stuff[4] == "") {
	$did = 0;
    } else {
	$did = $stuff[4];
 }
 $sql = "$verbphrase PERSONAL SET
     $addphrase
     sid = $stuff[0],
     last =  $q$stuff[1]$q,
     first = $q$stuff[2]$q,
     grade = $stuff[3],
     did = $did,
     schoolid = $q$schoolid$q
     $wherephrase
     ;";

     //echo $sql . "<br>";
     $records++;
     if (!mysql_query($sql,$link)){
       echo "Error adding $stuff[0]-$stuff[1]-$stuff[2] to the student table.<br>";
       $records--;
      }

}

//Close the file
 fclose($fp);

//Close the database
 mysql_close($link);

//Report the number of records added to the database...
  echo "</pre>$records record(s) were added/modified.";

?>
