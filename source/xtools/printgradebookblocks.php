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
 $SchoolID =    $HTTP_POST_VARS['schoolid'];

 //These will come from files....
 $UserID = $_SESSION['UserID'];
 $cc = $HTTP_GET_VARS['cc'];
 $CurrentSID = $_SESSION['CurrentSID'];
 $sid = $_SESSION['sid'];
 $StorageDir = $HTTP_POST_VARS['storageloc'];
 $pagesperblock = 25;
 $currentpage = 0;
 $pagecounter = 0;

 include ("../basmaticonstants.php");


 if ($_SESSION['LoginType'] != "A" . $districtid){
   echo("You must log-in to use this feature.");
   exit;
 }
set_time_limit(6000);  //allow up to 10 minutes for this script to run!

//Read the student ID's from the submitted file...
$delim = chr(9);
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
  $cc = trim($stuff[1]);
  $nrows = 0;
  $row_n = 0;
  $school = $SchoolID;
  $output = "";
  $pagenumber++;

 //determine school...
  $sql_query = "SELECT * from SCHOOLS where school_id = '" . $school ."'";
   //echo $sql_query;
    if ($datamethod == "mysql"){
    fnOpenDB();
    $sql_result = mysql_query($sql_query,$link);
    $schoolname = mysql_result($sql_result,0,school_name);
    fnCloseDB();
   }


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


  $mysql_query = "SELECT COURSEINFO.cc as cc, COURSEINFO.coursename as coursename, COURSEINFO.facultyname as facultyname,
                 COURSEINFO.modified as 'modified', GMSCORES.grade as grade, GMSCORES.percent as percent, COURSEINFO.email as email,
                 COURSEINFO.assignlist as assignlist, COURSEINFO.ealr as ealr,GMSCORES.scores as scores, COURSEINFO.assignvals as assignvals, COURSEINFO.phone as phone,
                 COURSEINFO.misc as misc
                 FROM COURSEINFO left join GMSCORES on COURSEINFO.cc = GMSCORES.cc and COURSEINFO.schoolid = GMSCORES.schoolid where GMSCORES.cc = '$cc' and GMSCORES.sid = "  . $sid. " and GMSCORES.schoolid = '" . $school . "' order by GMSCORES.cc;";


  // echo $mysql_query;

if ($datamethod == "mysql"){
  fnOpenDB();
  $sql_result = mysql_query($mysql_query,$link);
  echo mysql_error($link);
  if (mysql_num_rows($sql_result)!=0){
    $nfields = mysql_num_fields($sql_result);
    for($r=0;$r<mysql_num_rows($sql_result);$r++){
     $nrows++;
     for ($i=0;$i<$nfields;$i++){
       $fieldname = mysql_field_name($sql_result,$i);
       $fieldvalu = mysql_result($sql_result,$r,mysql_field_name($sql_result,$i));
       $grade_array[$fieldname][$nrows]=$fieldvalu;
       $hyperlink = mysql_result($sql_result,0,email);
       if (!eregi("http://",$hyperlink)){
        $hyperlink = "mailto:" . $hyperlink;
       }
       $grade_array["email"][$i] = $hyperlink;

     } // for $i
    } //for $r
    mysql_free_result($sql_result);
   }//If numrows != 0
   fnCloseDB();
   $row_n = $nrows;
  } // end of mysql




//Explode COURSEINFO.assignlist, COURSEINFO.assignvals, COURSEINFO.ealr and GMSCORES.scores

  $assign_names = explode(chr(169),stripslashes($grade_array[assignlist][1]));
  $assign_vals = explode(chr(169),$grade_array[assignvals][1]);
  $ealr_names = explode(chr(169),$grade_array[ealr][1]);
  $student_scores = explode(chr(169),$grade_array[scores][1]);
  $nrows = count($assign_names);


//Start drawing page...


  $output .= "<body background = white><font face=arial>";
  $output .= '<STYLE TYPE="text/css"> P.breakhere {page-break-before:always} </STYLE>';
  $output .= '<P CLASS="breakhere">';
  $output .= "<font  color=black>";
  $output .= "<center><b>Basmati Gradebook Report for $firstname $lastname</b><br>";
  $output .= "<font size=-1><i>$schoolname</i><br>";
  $output .= "Grade: $gradelevel  -- ";
  $output .= "Student ID:  $sid<font></br>";
  $output .= "<p>";



// Show contact Information
// Process any Misc 3 tags...

     $hlink = $grade_array[facultyname][1];

    if (eregi("-percent",$grade_array[misc][1])){
      $grade_array[percent][1] = " ";
    } else {
      $grade_array[percent][1] = "" . $grade_array[percent][1] . "%";
    }

  $output .= ("<center><font size=-1>");
  $output .= "Course Title:  <b>". $grade_array[coursename][1]."</b><br>";
  $output .= "Instructor:  <b>$hlink</b><br>";
  $output .= "Phone:  <b>".$grade_array[phone][1]."</b><br>";
  $output .= "Last Updated: <b>".$grade_array[modified][1]."</b>";
  $output .= "<p>";

  $output .= "Current Grade: <b>" . $grade_array[grade][1]."</b><br>";
  $output .= "Current Percent: <b>" . $grade_array[percent][1] . "</b><p></font>";






//Now that we have an array, cycle through and create table... (independent of database)

  $output .= "<center>";
  //Draw a large table around the inner table...
  $output .= "<table border = 1><tr><td>";
  //Now draw the grades table...
  $output .= "<table border=0 cellpadding=1 cellspacing=1 bordercolor=black><tr>";
  $output .= "<td><b><font size=-1>Assignment</font></b></td><td><b><font size=-1>Your<br> Score</font></b></td><td><b><font size=-1>Points <br>Possible</b></font></td>";
  if (!eregi("-misc",$grade_array[misc][1])){
    $output .= "<td><b><font size=-1>Misc.</font></b></td>";
  }
  $output .= "</tr>";

//If we encounter a #!C in $ealr[0], we have a commentary type record -- implode the array

  if (trim($ealr_names[0]) == "#!C") {
  		$temp = implode(chr(169),$student_scores);
  		//Strip the final 169 from the end
  		$temp = substr($temp,0,strlen($temp)-1);
  		//Replace 169's with commas
  		$temp = eregi_replace(chr(169),",",$temp);
  		//Replace the first array element.
  		$student_scores[0]= $temp;
  }


  for ($i = 0; $i < $nrows-1 ;$i++){
    $output .= "<tr>";
    $output .= "<td border=1><font size=-2>" . $assign_names[$i] . "</font></td>";
    $output .= "<td border=1><font size=-2>" . AddStarofDeath($student_scores[$i]) . "</font></td>";
    $output .= "<td border=1><font size=-2>" . $assign_vals[$i] . "</font></td>";
    if (!eregi("-misc",$grade_array[misc][1])){
       $output .= "<td border=1><font size=-2>" . ParseStandards($ealr_names[$i],$grade_array[misc][1]) . "</font></td>"; //process standards
    }
    $output .= "</tr>";
  }
  $output .= "</table>"; //Closes the assignment table...
  $output .= "</td></tr></table>"; //Closes the larger table...
  $output .= "</center>";

 $output .= "<table border = 0><tr><td width=100></td><td width=*>";
 $output .=  "<font size=-2><p><hr><b>Disclaimer:</b>  The scores contained in the table above may be weighted.  ";
 $output .=  "It is not usually possible to simply add the number of points earned and divide ";
 $output .=  "by the total number of points when scores are weighted.  The scores presented in ";
 $output .=  "this table are for information only.<p>";
 $output .=  "An asterisk (<font color=black>*</font>) indicates that this score is missing from the teacher's grade book.  Most likely this indicates ";
 $output .=  "that the assignment was never turned into the teacher.  However, in rare occasions it may ";
 $output .= "also indicate that this assignment was not intended to be graded.</font>";
 $output .=  "<p>";
 $output .=  "<br>";
 $output .=  "</td></tr></table>";

// echo "$output <hr>";

  //Write the output to a file... (in groups of pages)

   if ($pagecounter == 0) {
	   $currentpage++;
 	   $outfilename =    trim($StorageDir) . "gb." . trim($currentpage) . ".html";
	   echo $outfilename . "<hr>";
	   $fwpointer = fopen($outfilename,"w");
   }

	   fputs($fwpointer,$output);
	   $pagecounter++;

   if ($pagecounter == $pagesperblock){
	   $pagecounter = 0;
	   fclose($fwpointer);
   }

   $output = "";
}// End the While from the file-read....  (way up there!)
  echo "Finished.";

  //Now print the last line and close...
	fclose($fwpointer);




function AddNA($string){
 if (trim($string) == ""){
   return "N/A";
 } else {
   return $string;
 }
}

function AddStarofDeath($string){
 if (trim($string) == ""){
   return "<font color=black ><b>*</b></font>";
 } else {
   return $string;
 }
}


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

function ParseStandards($standardtext,$processcode){
//process the EALRS
 if (eregi("standard",$processcode)){
  //explode by commas...
  $newtext = "";
  $stands = explode(",",$standardtext);
  $nealr = count($stands);
  //determine state...
  $stateloc = strpos(strtoupper($processcode),"STANDARD:");
  $statecode = substr(strtolower($processcode),$stateloc+9,2);


   for ($j=0;$j < $nealr; $j++){
     $fullref = $stands[$j];
     $page = "";
     $ref = "";
     $component = split(" ",trim($fullref),2);
     $page = $component[0];
     $ref = $component[1];
     if (trim($page)!=""){
      $link = "<a href=http://basmati.esd189.org/standards/". $statecode. "/". strtolower(trim($page)) . ".htm#" . trim($ref).">$page $ref</a> ";
     }
     $newtext = $newtext."  ".$link;
   }




  return(AddNA($newtext ));
 }
 //don't process the EALRS
 if (!eregi("standard",$processcode)){
  return(AddNA($standardtext));
 }


}

?>
