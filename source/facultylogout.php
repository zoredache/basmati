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
// $Id: facultylogout.php,v 1.2 2001/11/01 20:51:27 basmati Exp $

session_start();

$LoginType = "";
$SchoolID = "";
$UserID = "";
$HTTP_SESSION_VARS['LoginType'] = "";
$HTTP_SESSION_VARS['SchoolID'] = "";
$HTTP_SESSION_VARS['UserID'] - "";

 echo '<LINK rel="stylesheet" type="text/css" href="style.css" title="style1">';
 echo("<center><img src=basmati.jpg><br>");
 echo("You are now logged out.</center>");



?>
