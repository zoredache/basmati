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

$admuser = "";
$admpass = "";

require("basmaticonstants.php");
require("basmatifunctions.php");

//Allows for register_globals=off
$uname = $HTTP_POST_VARS['uname'];
$pword = $HTTP_POST_VARS['pword'];


if ($uname == $admuser && $pword == $admpass){
  //set cookies
  $_SESSION['LoginType'] = "A" . $districtid;

  echo ("<body bgcolor=white>");
  echo ("<center><img src=basmati.gif><br><font color=green>You are now logged in.</font></center>");
  //Write log file...
  writelog("IN-ADM","admin","n/a");
} else {
  $_SESSION['LoginType'] = "";
  echo ("Invalid Login.");
}

