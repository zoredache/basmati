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
// $Id: adminlogin.php,v 1.1 2001/09/22 22:17:45 basmati Exp $

$admuser = "";
$admpass = "";

require("basmaticonstants.php");
require("basmatifunctions.php");

//Allows for register_globals=off
$uname = $HTTP_POST_VARS['uname'];
$pword = $HTTP_POST_VARS['pword'];


if ($uname == $admuser && $pword == $admpass){
  //set cookies
  session_register("LoginType");
  $LoginType = "A";
  $HTTP_SESSION_VARS['LoginType'] = "A";
  //setcookie("LoginType","A");
  echo ("<body bgcolor=white>");
  echo ("<center><img src=basmati.jpg><br><font color=green>You are now logged in.</font></center>");
  //Write log file...
  writelog("IN-ADM","admin","n/a");
} else {
  session_register("LoginType");
  $LoginType = "";
  //setcookie("LoginType","");
  echo ("Invalid Login.");
}

?>

