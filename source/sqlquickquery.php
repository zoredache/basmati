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
// $Id: sqlquickquery.php,v 1.1 2001/10/10 03:05:45 basmati Exp $

//Allows for register_globals=off
$sqltext = $HTTP_POST_VARS['sqltext'];

  include("basmaticonstants.php");

  if ($datamethod == "mysql"){
    $sqltext = urlencode($sqltext);
    //echo "$sqltext going to sqlqery.php";
    //exit;
    header("Location: sqlquery.php?sqltext=$sqltext");
  }
  if ($datamethod == "odbc"){
    $sqltext = urlencode($sqltext);
    header("Location: submitquery.php?sqltext=$sqltext&returnstyle=returnhtml");
  }
?>


