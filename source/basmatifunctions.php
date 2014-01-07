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

//Common Basmati Functions

/////////////////////////////////
////fn_writelog//////////////////
/////////////////////////////////
function writelog($logtype,$userid,$schoolid){
    require("basmaticonstants.php");
    if ($logevents == 1){

        //Open the database and write the event...
        if ($datamethod == "mysql"){
            $link = mysql_connect($databaseserver,$datausername,$datapassword)
                or die ("Couldn't connect to server");
            if (! mysql_select_db($databasename,$link)){
                echo ("<B><br>Error:  Cannot Select DB</b> -- please contact administrator.");
            }
        }
        $ts = date("Y-m-d H:i:s", time());
        $ip = getenv("REMOTE_ADDR");
        $sql = "INSERT into EVENTLOG (eventid,schoolid,user,ipaddr) VALUES".
               "('$logtype','$schoolid','$userid','$ip');";
        mysql_query($sql,$link);
        mysql_close($link);
    }
}
?>
