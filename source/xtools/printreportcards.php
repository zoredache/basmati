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

  $SchoolID = $_POST['schoolid'];
  $StorageDir = $_POST['storageloc'];
  $school = $SchoolID;


  include ("../basmaticonstants.php");



 if ($_SESSION['LoginType'] != "A" . $districtid){
   echo("You must log-in to use this feature.");
   exit;
 }


//Read the student ID's from the submitted file...
$delim = chr(9);
$nl = chr(13) . chr(10);

$userfile = $HTTP_POST_FILES;
$file = $userfile['userfile']['tmp_name'];
if ($file == "none" || $file == ""){
	echo "You did not submit a file...";
    exit;
}
if (!($fp = fopen($file,"r"))){
	echo "Could not open file for reading....";
    exit;
}
while (!feof($fp)){
  $stuff = fgetcsv($fp,4096,$delim);
  $sid = $stuff[0];
  $sid = intval($sid);
  $nrows = 0;
  $row_n = 0;


  $output = "";
  $ot = "$nl $nl $nl";

 //Gather Student Information....
 $sql_query = "SELECT * from PERSONAL where sid = $sid and schoolid = '$school';";
 if ($datamethod == "mysql"){
    fnOpenDB();
    $sql_result = mysql_query($sql_query,$link);
    $firstname = mysql_result($sql_result,0,first);
    $lastname = mysql_result($sql_result,0,last);
    $gradelevel = mysql_result($sql_result,0,grade);
    fnCloseDB();
   }


  $output .= "<body bgcolor=white>";
  $output .= "<font face=arial  color=black>";
  $output .= "<center><b>Basmati Grade Report for $firstname $lastname <br>Grade:  $gradelevel<br>  Student ID: $sid</b><br>";
  $ot  .= "Grade Report for $firstname $lastname  $nl(Grade $gradelevel, Student ID = $sid) $nl";

 //determine school...
  $sql_query = "SELECT * from SCHOOLS where school_id = '" . $school ."'";

   //echo $sql_query;


   if ($datamethod == "mysql"){
    fnOpenDB();
    $sql_result = mysql_query($sql_query,$link);
    $schoolname = mysql_result($sql_result,0,school_name);
    fnCloseDB();
   }


 $output .= " <i>$schoolname<i><p> ";
 $ot .= "$schoolname $nl $nl";


  $mysql_query = "SELECT COURSEINFO.cc as cc, COURSEINFO.coursename as coursename, COURSEINFO.facultyname as facultyname,
                 COURSEINFO.modified as 'modified', GMSCORES.grade as grade, GMSCORES.percent as percent, COURSEINFO.email as email, COURSEINFO.misc as misc
                 FROM COURSEINFO left join GMSCORES on GMSCORES.cc = COURSEINFO.cc and GMSCORES.schoolid = COURSEINFO.schoolid where GMSCORES.sid = "  . $sid. " and GMSCORES.schoolid = '" . $school . "' order by GMSCORES.cc;";



   //echo $mysql_query;

 if ($datamethod == "mysql"){
  fnOpenDB();
  $sql_result = mysql_query($mysql_query,$link);
  //echo mysql_error($link);
  if (mysql_num_rows($sql_result)!=0){
    $nfields = mysql_num_fields($sql_result);
    for($r=0;$r<mysql_num_rows($sql_result);$r++){
     $nrows++;
     for ($i=0;$i<$nfields;$i++){
       $fieldname = mysql_field_name($sql_result,$i);
       $fieldvalu = mysql_result($sql_result,$r,mysql_field_name($sql_result,$i));
       $grade_array[$fieldname][$nrows]=$fieldvalu;
     } // for i
       $hyperlink = mysql_result($sql_result,$r,email);
       $grade_array["hmail"][$nrows] = $hyperlink;

       if (!eregi("http://",$hyperlink)){
        $hyperlink = "mailto:" . $hyperlink;
       }
       $grade_array[email][$nrows] = $hyperlink;

    } //for $r
    mysql_free_result($sql_result);
   }//If numrows != 0
   fnCloseDB();
   $row_n = $nrows;
  } // end of mysql


//echo("<hr>".$grade_array[email][1]."<hr>");





//Now that we have an array, cycle through and create table... (independent of database)

  $output .= "<center>";
  $output .= "<table border=1 bgcolor=black><tr bgcolor=#eeeeee>";
  $output .= "<td><b>Class Name</b></td><td><b>Instructor</b></td><td><b>Last Updated</b></td><td><b>Current Grade</b></td><td><b>Current Percent</b></td></td>";
  $output .= "</tr>";



  for ($i = 1; $i <= $row_n ;$i++){
//Apply any of the misc3 tags...
    $hlink = "";


     $hlink = $grade_array[facultyname][$i];


    if (eregi("-percent",$grade_array[misc][$i])){
      $grade_array[percent][$i] = " ";
    } else {
      $grade_array[percent][$i] = $grade_array[percent][$i] . "%";
    }

    $output .= "<tr bgcolor=white>";
    $output .= "<td>" . $grade_array[coursename][$i] . "<br><font size=-1>(" . $grade_array[cc][$i].  ")</font></td>";
    $output .= "<td>". $hlink . "</td>";
    $output .= "<td  align=center width=80>" . $grade_array[modified][$i] . "</td>";
    $output .= "<td align=center>" . $grade_array[grade][$i] . "</font></td>";
    $output .= "<td align=center>" . $grade_array[percent][$i]. "</font></td>";
    $output .= "</tr>";
    $ot .= $nl;
    $ot .= "---------------------------------------------------------- $nl";
    $ot .= "Course:  " . $grade_array[coursename][$i] . "  (" . $grade_array[cc][$i] . ")" . $nl;
    $ot .= "Grades Last Updated on " . $grade_array[modified][$i] . $nl;
    $ot .= "Instructor:  $hlink $nl";
    $ot .= "Current Grade: " . $grade_array[grade][$i] . " (" . $grade_array[percent][$i] . ")$nl";
    $ot .= "---------------------------------------------------------- $nl ";


  }
   $output .= "</table>";

   //echo $output;


 //Write the output to a file...
   $outfilename =   trim($StorageDir) . "rc."  . trim($gradelevel) . "~" . trim($lastname) . "~" . trim($sid) . ".txt";

   echo $outfilename . "<hr>";
   $fwpointer = fopen($outfilename,"w");
  // fputs($fwpointer,$output);
   fputs($fwpointer,$ot);
   fclose($fwpointer);

   $output = "";
   $ot = "";

}//Close the while for file looping...
fclose($fp);
echo "Finished...";

function fnOpenDB(){
 global $link;
 include ("../basmaticonstants.php");
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
