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
$sqltext = $_POST['sqltext'];
$returnstyle = $_POST['returnstyle'];

 include ("basmaticonstants.php");
 if ($_SESSION['LoginType'] != "A" . $districtid){
   echo("You must log-in to use this feature.");
   exit;
 }
?>


<html><head><title>Results from Query</title></head>
<body bgcolor=white>
You submitted the following query:<br> <i>
<?php
 $slash = addslashes("\\");
 $sqltext = ereg_replace($slash,$emptys,$sqltext);
 echo($sqltext);
?>
  </i>
<hr>
<?php
 include("basmaticonstants.php");
 if ($datamethod == "odbc"){
  $link = odbc_connect($databasename,$datausername,$datapassword);
  $sql_statement = odbc_prepare($link,$sqltext);
  $sql_result = odbc_execute($sql_statement);
  if ($returnstyle == "returnhtml"){
   echo("<table border=0><tr bgcolor=yellow>");
   for ($i = 1; $i <= odbc_num_fields($sql_statement);$i++){
    echo("<th>".odbc_field_name($sql_statement,$i)."</th>");
   }
   echo("</tr>");
   if (odbc_num_rows($sql_statement)!=0){;
    while (odbc_fetch_row($sql_statement)){
     if ($color){
      echo("<tr bgcolor=#9999ff>");
     }
     if (!$color){
      echo("<tr bgcolor=#99ff99>");
     }
     $color = !$color;
     for ($i=1; $i<=odbc_num_fields($sql_statement);$i++){
      echo("<td>".odbc_result($sql_statement,$i)."</td>");
     }
     echo("</tr>");
     }
    } //If numrows != 0
   echo ("</table>");
   } //if returnstyle...

   if ($returnstyle != "returnhtml"){
    if ($returnstyle == "returntabtext"){
     $delim = chr(9);
    }
    if ($returnstyle == "returncommatext"){
     $delim = ",";
    }
    if ($returnstyle == "returnothertext"){
     $delim = chr($asciidelim);
    }

    echo ("<pre>");
    if (odbc_num_rows($sql_statement)!=0){;
    while (odbc_fetch_row($sql_statement)){
      $lineoftext = "";
      for ($i=1; $i<=odbc_num_fields($sql_statement);$i++){
      $lineoftext = $lineoftext . odbc_result($sql_statement,$i).$delim;
      //echo(odbc_result($sql_statement,$i).$delim);
     }
     $lineoftext = substr($lineoftext,0,strlen($lineoftext)-1);
     echo $lineoftext;
     echo(chr(13));
     }
    } //If numrows != 0
   echo ("</pre>");

   } //!=returnhtml


  odbc_free_result($sql_statement);
  odbc_close($link);
 }  //End of ODBC

 if ($datamethod == "mysql"){
   $mysqltext = $sqltext;
   fnOpenDB();
   $sql_result = mysql_query($mysqltext,$link);

   if (($sql_result)!=1){
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

  if ($returnstyle == "returnhtml"){
  //Now print the table (entirely contained in $ary...
   echo("<table border=0><tr bgcolor=yellow>");

 //First the headers...
   for ($f =1;$f<=$nfields;$f++){
    echo("<th>".$titles[$f]."</th>");
   }


 //Now the data...
   for ($r=1;$r<=$nrows;$r++){
    if (intval($r/2) == $r/2){
      $rowcolor = "99ff99";
     } else {
      $rowcolor="9999ff";
     }
    echo ("<tr bgcolor=$rowcolor>");
    for ($f=1;$f<=$nfields;$f++){
     echo("<td>".$ary[$r][$f]."</td>");
    }
   }

   echo ("</table>");
  } //returnstyle = html


  if ($returnstyle != "returnhtml"){
    if ($returnstyle == "returntabtext"){
     $delim = chr(9);
    }
    if ($returnstyle == "returncommatext"){
     $delim = ",";
    }
    if ($returnstyle == "returnothertext"){
     $delim = chr($asciidelim);
    }
    echo ("<pre>");
    for ($r=1;$r<=$nrows;$r++){
     $lineoftext = "";
     for ($f=1;$f<=$nfields;$f++){
       $lineoftext = $lineoftext . $ary[$r][$f] .$delim;

      //echo("<td>".$ary[$r][$f]."</td>");
     }
     $lineoftext = substr($lineoftext,0,strlen($lineoftext)-1);
     echo $lineoftext;
     echo(chr(13));

   } //for r



  } //$returnstyle != html


    mysql_free_result($sql_result);
   }//If numrows != 0
   fnCloseDB();


} // end of mysql


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
