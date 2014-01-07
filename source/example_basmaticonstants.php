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
// $Id: example_basmaticonstants.php,v 1.8 2002/10/21 16:19:42 basmati Exp $

//Database Access, etc
$datamethod = "mysql";  //Set to odbc or mysql depending upon database server
$databasename = "basmati";  //Name of the database
$databaseserver = "localhost"; //Name/IP of the database server
$datausername = "root"; //User account that can access the database
$datapassword = "";     //Password for user that can access the database
$districtid = ""; //If hosting multiple districts, change this to a unique value for each district
$logevents = 1;  //  If 1 will log to EVENTLOG table (only if using MySQL)
$announcement = "Welcome to Basmati!";  //Faculty-level announcement
$usetextbox = 0; //  If zero, will display list of schools, if 1, will require user to enter school id
$enableplugins = 0; // If 1, will allow plugins.php to be included within the showreportcard.php file
$enablefacultyplugins = 0; // If 1, will allow facultyplugins.php to be included within the facultylogin.php file
$emaildomain = "";  // Leave blank if site will host multiple email domains.
$localsubnet = "127.0.0%"; //Used for statistics to determine local access
$enforce_expiration = 0; //Used if expiration dates on faculty accounts should be enforced
$expireurl = ""; //URL to display if account has expired
$privacyurl = ""; //URL to display your privacy policy

//These constants allow access to administrative pages... be sure to change them!
$admuser = "adminuser";
$admpass = "adminpass";

//!!!!Important...
// Please make certain NOTHING is after the line below!
?>
