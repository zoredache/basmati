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
// $Id: submitfile.php,v 1.1 2001/09/22 22:18:06 basmati Exp $

$LoginType = "";
session_start();
?>
<html>
<head>
<title>Submit a grade file...</title>
</head>
<?php
 $LoginType = $HTTP_SESSION_VARS['LoginType'];
 if ($LoginType != "T" && $LoginType != "A"){
   echo("You must log-in to use this feature.");
   exit;
 }
?>
<body bgcolor=white>
<h1>Submit file...</h1>

<form method=post action=uploadfile.php ENCTYPE="multipart/form-data">
Locate your export file...
<br> <input type=file name="userfile" value="Locate File">
<p>
Send the file to Basmati...
<br>
<input type=submit value="Submit File to Basmati">
<p>

</form>
</body>
</html>


