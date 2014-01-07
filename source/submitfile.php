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

session_start();
?>
<html>
<head>
<title>Submit a grade file...</title>
</head>
<?php

 if ($_SESSION['LoginType'] != "T" && $_SESSION['LoginType'] != "A"){
   echo '<LINK rel="stylesheet" type="text/css" href="style.css" name="style1">';
   echo("You must log-in to use this feature.");
   exit;
 }
?>
<LINK rel="stylesheet" type="text/css" href="style.css" name="style1">

<h3>Submit a Grade Export File...</h3>

<form method=post action=uploadfile.php ENCTYPE="multipart/form-data">
Step 1:  Locate the grade export file on your computer by clicking the "Browse" button...
<br> <input type=file name="userfile" value="Locate File">
<p>
Step 2:  Click "Submit File to Basmati" to send the file to Basmati...
<br>
<input type=submit value="Submit File to Basmati">
<p>

</form>
</body>
</html>
