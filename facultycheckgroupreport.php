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
// $Id: facultycheckgroupreport.php,v 1.1 2001/09/22 22:17:58 basmati Exp $

 $LoginType = "";
 session_start();

  $LoginType = $HTTP_SESSION_VARS['LoginType'];
  $SchoolID =    $HTTP_SESSION_VARS['SchoolID'];
  $UserID = $HTTP_SESSION_VARS['UserID'];

 if ($LoginType != "T" && $LoginType != "A"){
   echo("You must log-in to use this feature.");
   exit;
 }
?>
 <body bgcolor=white>
 <form method=get action=showgroupreport.php>
 <table border = 0>
 <tr>
 <td>Group ID</td><td><input type=text name=groupid> </td>
 <tr>
 <td>Class Percent is </td>
 <td>
 	 <select name=groupcriteria value=0>
     <option  value=-1>Less than... </option>
     <option selected value=1>Greater than...</option>
     </select>
     <input type=text size=3 name=percent value=0>%

 </tr>
 </table>
 <input type=submit>
 </form>





