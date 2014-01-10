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


//Connect to the database...
//Check security
session_start();
$schoolid = $_POST['schoolid'];

  require("../basmaticonstants.php");

if ($_SESSION['LoginType'] != "A" . $districtid){
  echo "<body bgcolor=#cacaff><font face='verdana,arial,helvetica'><b>You must log-in to use this feature.</font>";
  exit;
}


  set_time_limit(600);  //allow up to 10 minutes for this script to run!
 
 //Connect to the database
 $link = mysql_connect($databaseserver,$datausername,$datapassword);
 
 //Select the appropriate database
 if (!mysql_select_db($databasename,$link)){
  echo("Can't connect to the database");
  exit;
 }
  
//Indicate success..
  echo "Connected to database using $link";
  
  
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


$tab = chr(9);
$q = chr(34);

//Delete any existing comments before importing the new ones...
	$sql = "delete from COMMENTLIST where schoolid = $q$schoolid$q;";
	mysql_query($sql,$link);
	echo "<P>All comments for school '$schoolid' have been deleted... importing new ones.";
 	
echo("<pre>");

//Read the file line-by-line
while (!feof($fp)){
 $stuff =  fgetcsv($fp,4096,$tab);
 
 //Start creating the SQL query that we'll use to populate the database...
 ///Need to deal with school-id as well!!!
 $sql = "INSERT INTO COMMENTLIST (schoolid, commentnum, commenttxt) VALUES ($q$schoolid$q , $stuff[0], $q$stuff[1]$q);";
 	    
     //echo $sql;
     $records++;
     if (!mysql_query($sql,$link)){
       echo "Error adding $stuff[0]-$stuff[1] to the database.<br>";
       $records--;
      }
      ;
 
}

//Close the file
 fclose($fp);

//Close the database
 mysql_close($link);
 
//Report the number of records added to the database...
  echo "</pre>$records record(s) were added to the database.";
?>
