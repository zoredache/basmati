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
// $Id: randomizepasswords.php,v 1.2 2002/01/16 22:08:26 basmati Exp $


//Check security
$LoginType = "";
session_start();

$LoginType = $HTTP_SESSION_VARS['LoginType'];
$defaultpw = $HTTP_POST_VARS['defaultpw'];



//Connect to the database...
 require("../basmaticonstants.php");



if ($LoginType != "A" . $districtid){

  echo "<body bgcolor=#cacaff><font face='verdana,arial,helvetica'><b>You must log-in to use this feature.</font>";

  exit;

}


 //Connect to the database

 $link = mysql_connect($databaseserver,$datausername,$datapassword);



 //Select the appropriate database

 if (!mysql_select_db($databasename,$link)){

  echo("Can't connect to the database");

  exit;

 }



//Indicate success..

  //echo "Connected to database using $link";


$delim = chr(9);
$q = chr(34);
$nl = chr(13);

$nchanges = 0;

//Create a query to find random passwords in student table...
	$sql = "SELECT id from PERSONAL where password = $q$defaultpw$q;";
    $result = mysql_query($sql,$link);
    for ($i=0; $i < mysql_num_rows($result); $i++){
    	$idtochange =  mysql_result($result,$i,id);
        $password = rpass(6);
        $sql2 = "UPDATE PERSONAL set password = $q$password$q where id = $idtochange;";
        if (mysql_query($sql2,$link)){
        	$nchanges++;
        }
    }

//Close the database

 mysql_close($link);

 echo "A total of $nchanges records have been changed.";



function rpass($nchar) {

//Initialize Random Number Generator

  srand((double)microtime() * 1000000);



  $possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

  $pass = "";

  for ($n=0;$n<$nchar;$n++){

    $pass .= substr($possible,(rand()%strlen($possible)),1);

  }

  return ($pass);

}

?>




