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

require("../basmaticonstants.php");

if ($_SESSION['LoginType'] != "A" . $districtid){
  echo "<body bgcolor=#cacaff><font face='verdana,arial,helvetica'><b>You must log-in to use this feature.</font>";
  exit;
}

//Process uploaded file...
$delim = chr(9);
$nl = chr(13);

$file = $_FILES['userfile']['tmp_name'];


if ($file=="none" || $file == ""){
  echo("You did not submit a file.");
  exit;
}

if (!($fp = fopen($file,"r"))){
  echo("could not open file for reading");
  exit;
}

header("Content-type:other/unknown");
header("Content-Disposition:attachment; filename=convertedstudents.txt");
$tab = chr(9);
while (!feof($fp)){
 $stuff =  fgetcsv($fp,4096,$tab);
 $sid= $stuff[0];
 $last = $stuff[1];
 $first = $stuff[2];
 $grade = $stuff[3];
 echo $sid . $tab . $last . $tab . $first . $tab . $grade . $tab . rpass(6) . $tab . "0" . $tab . chr(13) . chr(10);
}
fclose($fp);

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
