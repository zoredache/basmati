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
// $Id: getpasswords.php,v 1.6 2003/12/23 19:20:31 basmati Exp $

 session_start();
  $SchoolID =    $_SESSION['SchoolID'];
  $UserID = $_SESSION['UserID'];
  $cc = $HTTP_GET_VARS['cc'];
  $reporttype = $HTTP_GET_VARS['reporttype'];

 include ("basmaticonstants.php");
 if ($_SESSION['LoginType'] != "T" && $_SESSION['LoginType'] != "A"){
   echo("You must log-in to use this feature.");
   exit;
 }

 if ($reporttype != "GMIMPORT" ){
  $sql_query = "SELECT PERSONAL.sid, last, first, password, GMSCORES.grade as Score from PERSONAL left join GMSCORES on PERSONAL.sid = GMSCORES.sid where GMSCORES.cc = '" . $cc . "' and PERSONAL.schoolid = '" . $SchoolID . "';";
  $mysql_query  = "SELECT PERSONAL.sid, last, first, password, GMSCORES.grade as Score from PERSONAL inner join GMSCORES on PERSONAL.sid = GMSCORES.sid where GMSCORES.cc = '" . $cc . "' and PERSONAL.schoolid = '" . $SchoolID . "' and GMSCORES.schoolid = '". $SchoolID . "' order by last, first, sid;";
 } else {
   $sql_query = "SELECT DISTINCT PERSONAL.sid, password from PERSONAL left join GMSCORES on PERSONAL.sid = GMSCORES.sid where GMSCORES.cc = '" . $cc . "' and PERSONAL.schoolid = '" . $SchoolID . "';";
   $mysql_query  = "SELECT DISTINCT PERSONAL.sid, password from PERSONAL inner join GMSCORES on PERSONAL.sid = GMSCORES.sid where GMSCORES.cc = '" . $cc . "' and PERSONAL.schoolid = '" . $SchoolID . "' and GMSCORES.schoolid = '" . $SchoolID . "';";
 }


  if ($datamethod == "odbc"){
  $link = odbc_connect($databasename,$datausername,$datapassword);
  $sql_statement = odbc_prepare($link,$sql_query);
  $sql_result = odbc_execute($sql_statement);
  echo("</tr>");
  if (odbc_num_rows($sql_statement)!=0){
   $nfields = odbc_num_fields($sql_statement);
   for ($i=1; $i<=odbc_num_fields($sql_statement);$i++){
     $titles[$i] = odbc_field_name($sql_statement,$i);
    } //for
   while (odbc_fetch_row($sql_statement)){
    $nrows++;
    for ($i=1; $i<=odbc_num_fields($sql_statement);$i++){
     $ary[$nrows][$i] = odbc_result($sql_statement,$i);
    } //for
   } //while
  } //If numrows != 0
  odbc_free_result($sql_statement);
  odbc_close($link);
} //odbc stuff...


 if ($datamethod == "mysql"){
  fnOpenDB();
  $sql_result = mysql_query($mysql_query,$link);
  echo mysql_error($link);
  if (mysql_num_rows($sql_result)!=0){
    $nfields = mysql_num_fields($sql_result);
    for ($i=0;$i<$nfields;$i++){
      $titles[$i+1] = mysql_field_name($sql_result,$i);
    } // for
    while (mysql_fetch_row($sql_result)){
    $nrows++;
     for ($i=0;$i<$nfields;$i++){
       $ary[$nrows][$i+1] = mysql_result($sql_result,$nrows-1,$i);
     } // for
    } //while
    mysql_free_result($sql_result);
   }//If numrows != 0
   fnCloseDB();
  } // end of mysql





if ($reporttype != "GMIMPORT" ){

//Now print the table (entirely contained in $ary...
  echo '<LINK rel="stylesheet" type="text/css" href="style.css" title="style1">';
  echo("<b>Passwords for $cc</b><hr>");
  echo("<table border=0><tr bgcolor=yellow>");

//First the headers...
  for ($f =1;$f<=$nfields;$f++){
   echo("<th><font size=-1>".$titles[$f]."</font></th>");
  }

  echo ("<th><font size=-1>Details</font></th>");
  echo ("<th><font size=-1>Private Notes</font></th>");

//Now the data...
  for ($r=1;$r<=$nrows;$r++){
   if (intval($r/2) == $r/2){
     $rowcolor = "99ff99";
    } else {
     $rowcolor="9999ff";
    }
   echo ("<tr bgcolor=$rowcolor>");
   for ($f=1;$f<=$nfields;$f++){
    echo("<td><font size=-1>".$ary[$r][$f]."&nbsp</font></td>");
   }
   echo("<form method=get action=showreportcard.php><td valign=top><input type=submit value=Details><input type=hidden name = sid value=" .$ary[$r][1]. "></td></form>");
   echo("<form method=get action=privatenotes.php><td valign=top><input type=hidden name=cc value = $cc><input type=submit value='Private Notes'><input type=hidden name = sid value=" .$ary[$r][1]. "></td></form>");
   echo ("</tr>");
  }

  echo ("</table>");

  echo("<hr>");
  echo("<form method=get action=getpasswords.php target=new>");
  echo("<input type=hidden name = cc value=$cc><input type=hidden name=reporttype value=GMIMPORT>");
  echo("<input type=submit value=\"Obtain Importable List of Passwords\">");
  echo("</form>");

} // reporttype != GMIMPORT


if ($reporttype == "GMIMPORT" ){

//Now print the table (entirely contained in $ary...
  echo("<body bgcolor=white><pre>");

//Now the data...
  for ($r=1;$r<=$nrows;$r++){

    echo($ary[$r][1] . chr(9) . $ary[$r][2].chr(13));

  }
  echo ("</pre>");
} // reporttype == GMIMPORT





function fnOpenDB(){
 global $link;
 include ("basmaticonstants.php");
 if ($datamethod == "mysql"){
  $link = mysql_connect($databaseserver,$datausername,$datapassword) or die
("Couldn't connect to server");
  if (! mysql_select_db($databasename,$link)){
   echo ("<B><br>Error:  Cannot Select DB</b> -- please contact administrator.");
  }
 }
}


function fnCloseDB(){
 global $link;

  if ($datamethod == "mysql"){
   mysql_close($link);
 }
}




?>





