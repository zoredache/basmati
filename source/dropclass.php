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

  $SchoolID = $_SESSION['SchoolID'];
  $UserID = $_SESSION['UserID'];
  $dropid = $_GET['dropid'];

 if ($_SESSION['LoginType'] != "T" && $_SESSION['LoginType'] != "A" . $districtid){
   echo("You must log-in to use this feature.");
   exit;
 }
  if ($UserID == ""){
   echo ("Invalid Username...");
   exit;
  }

  if ($dropid == "") {
    echo ("Nothing to delete!");
    exit;
  }

  $cc = $dropid;
  $query[0] = "DELETE from COURSEINFO where cc = '" . $cc . "' and schoolid = '" . $SchoolID . "';";
  $query[1] = "DELETE from GMSCORES where cc = '" . $cc . "' and schoolid = '" . $SchoolID . "';";
  $query[2] = "DELETE from PRIVNOTES where cc = '" . $cc . "' and schoolid = '" . $SchoolID . "';";


  if ($datamethod == "odbc"){
   $link = odbc_connect($databasename,$datausername,$datapassword);
   $sql_statement = odbc_prepare($link,$query[0]);
   $sql_result = odbc_execute($sql_statement);
   $sql_statement = odbc_prepare($link,$query[1]);
   $sql_result = odbc_execute($sql_statement);
   odbc_free_result($sql_statement);
   odbc_close($link);
} //odbc stuff...


 if ($datamethod == "mysql"){
   fnOpenDB();
   $sql_result = mysql_query($query[0],$link);
   $sql_result = mysql_query($query[1],$link);
   $sql_result = mysql_query($query[2],$link);

   fnCloseDB();
 } // end of mysql




 echo ("Your class ($cc) has been removed from the system.");




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
