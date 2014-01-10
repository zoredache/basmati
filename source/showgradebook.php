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
 $SchoolID = $_SESSION['SchoolID'];
 $UserID = $_SESSION['UserID'];
 $cc = $_GET['cc'];
 $sid = $_SESSION['sid'];


 include ("basmaticonstants.php");


 if ($_SESSION['LoginType'] != "T" && $_SESSION['LoginType'] != "A" && $_SESSION['LoginType'] != "S"){
   echo("You must log-in to use this feature.");
   exit;
 }
 if ($_SESSION['LoginType'] == "S") {
 	$sid = $_SESSION['sid'];
 }

  $school = $SchoolID;
  $sid = intval($_SESSION['CurrentSID']);

  //check for invalid student ID
  if (intval($sid)<=0){
   echo("Invalid Student ID");
   exit;
  }


 //determine school...
  $sql_query = "SELECT * from SCHOOLS where school_id = '" . $school ."'";
   //echo $sql_query;
   if ($datamethod == "odbc"){
   $link = odbc_connect($databasename,$datausername,$datapassword);
   $sql_statement = odbc_prepare($link,$sql_query);
   $sql_result = odbc_execute($sql_statement);
   if (odbc_num_rows($sql_statement)!=0){
     odbc_fetch_row($sql_statement);
     $schoolname = odbc_result($sql_statement,school_name);
     odbc_free_result($sql_statement);
    }
   }
   


  $sql_query = "SELECT * from COURSEINFO inner join GMSCORES on COURSEINFO.cc = GMSCORES.cc where GMSCORES.sid = "  . $sid. " and GMSCORES.schoolid = '" . $school . "' and GMSCORES.cc = '" . $cc . "';";
  

  $mysql_query = "SELECT COURSEINFO.cc as cc, COURSEINFO.coursename as coursename, COURSEINFO.facultyname as facultyname,
                 COURSEINFO.modified as 'modified', GMSCORES.grade as grade, GMSCORES.percent as percent, COURSEINFO.email as email, GMSCORES.comments as comments,
                 COURSEINFO.assignlist as assignlist, COURSEINFO.ealr as ealr,GMSCORES.scores as scores, COURSEINFO.assignvals as assignvals, COURSEINFO.phone as phone,
                 COURSEINFO.misc as misc
                 FROM COURSEINFO inner join GMSCORES on COURSEINFO.cc = GMSCORES.cc and COURSEINFO.schoolid = GMSCORES.schoolid where GMSCORES.cc = '$cc' and GMSCORES.sid = "  . $sid. " and GMSCORES.schoolid = '" . $school . "' order by GMSCORES.cc;";


  // echo $mysql_query;
  // echo $sql_query;
  if ($datamethod == "odbc"){
  $link = odbc_connect($databasename,$datausername,$datapassword);
  $sql_statement = odbc_prepare($link,$sql_query);
  $sql_result = odbc_execute($sql_statement);
  
  
  if (odbc_num_rows($sql_statement)!=0){;
   while (odbc_fetch_row($sql_statement)){
    $row_n++;


    for ($i = 1; $i <= odbc_num_fields($sql_statement);$i++){
     //echo (odbc_field_name($sql_statement,$i));
     $grade_array[odbc_field_name($sql_statement,$i)][$row_n]=odbc_result($sql_statement,odbc_field_name($sql_statement,$i));
    }
    $hyperlink = odbc_result($sql_statement,email);
    if (!eregi("http://",$hyperlink)){
      $hyperlink = "mailto:" . $hyperlink;
    }
    $grade_array["email"][$i] = $hyperlink;

  } //If numrows != 0
  



  odbc_free_result($sql_statement);
  odbc_close($link);
 }
}//end of ODBC

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
  if (eregi("-background",$grade_array[misc][1])){
   echo("<body bgcolor=white>");
   echo '<LINK rel="stylesheet" type="text/css" href="style.css" title="style1">';
  } else {
   echo("<body background=notebook.gif>");
   echo '<LINK rel="stylesheet" type="text/css" href="style.css" title="style1">';
  }
  echo("<font size=+1 color=black>");
  echo("<center><b>Basmati Detailed Gradebook Report for $sid</b><p>");


//Display any Private Notes...
if ($datamethod == "mysql"){
  $mysql_query = "SELECT * from PRIVNOTES where sid = $sid and schoolid = '$SchoolID' and cc = '$cc';";
  fnOpenDB();
  $sql_result = mysql_query($mysql_query,$link);
  if (mysql_num_rows($sql_result) > 0){
	$notes = mysql_result($sql_result,0,notes);
	echo "<blockquote><center>";
	echo "<table bgcolor=white border=1><tr><td><b>Instructor's Notes:</b><br>$notes</td></tr></table>";
	echo "</center></blockquote>";
  }
} ///ADDED PAREN!


//Also display any pre-defined comments stored in the COMMENTLIST table...
if ($datamethod == "mysql"){
	$countcomment = 0;
	$commentlistarray = array_unique(explode(",",$grade_array[comments][1]));
	for ($i = 0; $i < sizeof($commentlistarray); $i++){
		if (trim($commentlistarray[$i] != "")) {
			$commentlist .= $commentlistarray[$i] . ",";
		}
	}
	//Strip off final comment
	$commentlist = substr($commentlist,0,-1);
	if ($commentlist != "") {
		$sqlgetcom = "SELECT * FROM COMMENTLIST where commentnum in ($commentlist) and schoolid = '$SchoolID';";
		fnOpenDB();
		$sql_result = mysql_query($sqlgetcom,$link);
	    if (mysql_num_rows($sql_result) > 0){
			$comlist =  "<table bgcolor=white border=1><tr><td><b>Instructor's Comments:</b><br><blockquote><center>";
			 for ($i = 0; $i < mysql_num_rows($sql_result); $i++){
				 $notes = mysql_result($sql_result,$i,commenttxt);
				 $comlist .= "$notes<br>";
			 }
			$comlist .= "</center></blockquote></td></tr></table>";
         }
	 }

}
 
    
 fnCloseDB();


// Show contact Information
// Process any Misc 3 tags...
   if (eregi("-mail",$grade_array[misc][1])){
     $hlink = $grade_array[facultyname][1];
    } else{
     $hlink = "<a href=$hyperlink>".$grade_array[facultyname][1]."</a>";
    }
    if (eregi("-percent",$grade_array[misc][1])){
      $grade_array[percent][1] = " ";
    } else {
      $grade_array[percent][1] = "Percent: " . $grade_array[percent][1] . "% -- ";
    }

  echo("<blockquote><center>");
  echo("<font size=+1>". $grade_array[coursename][1]."</font></font><br>");
  echo("$hlink<br>");
  echo("Phone:  ".$grade_array[phone][1]."<br>");
  echo("Last Updated: ".$grade_array[modified][1]."<p>");
  echo("<font size = +1>" . $grade_array[percent][1] . "</font>");
  echo("<font size = +1>  Grade: " . $grade_array[grade][1]."</font><p>");





//Now that we have an array, cycle through and create table... (independent of database)

  echo("<center>");
  echo("<table border=1 ><tr>");
  echo("<td><b>Assignment</b></td><td><b>Your<br> Score</b></td></td>");
  if (!eregi("-pp",$grade_array[misc][1])){
    echo("<td><b>Points<br>Possible</b></td>");
  }
  if (!eregi("-misc",$grade_array[misc][1])){
    echo("<td><b>Misc.</b></td>");
  }
  echo("</tr>");

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
    echo("<tr>");
    echo("<td border=1><font size=-1>" . $assign_names[$i] . "&nbsp</font></td>");
    echo("<td border=1><font size=-1>" . AddStarofDeath($student_scores[$i]) . "&nbsp</font></td>");
    if (!eregi("-pp",$grade_array[misc][1])){
       echo("<td border=1><font size=-1>" . ParseStandards($assign_vals[$i],$grade_array[misc][1]) . "&nbsp</font></td>"); //process standards
    }
    if (!eregi("-misc",$grade_array[misc][1])){
       echo("<td border=1><font size=-1>" . ParseStandards($ealr_names[$i],$grade_array[misc][1]) . "&nbsp</font></td>"); //process standards
    }
    echo("</tr>");
  }
  echo ("</table>");
  echo ("</center>");


echo "</blockquote>";




//Show the "canned" comments unless '-comlist' appears in MISC3
if (!eregi("-comlist", $grade_array[misc][1])){
	echo $comlist;
}


//Show the disclaimer unless '-disc' appears in MISC3
if (!eregi("-disc",$grade_array[misc][1])){
 echo "<table border = 0><tr><td width=100></td><td width=*>";
 echo "<p><hr><b><font size=-1>Disclaimer:</b>  The scores contained in the table above may be weighted.  ";
 echo "It is not usually possible to simply add the number of points earned and divide ";
 echo "by the total number of points when scores are weighted.  The scores presented in ";
 echo "this table are for information only.<p>";
 echo "An asterisk (<font color=red>*</font>) indicates that this score is missing from the teacher's grade book.  Most likely this indicates ";
 echo "that the assignment was never turned into the teacher.  However, in rare occasions it may ";
 echo "also indicate that this assignment was not intended to be graded.</font>";
 echo "<p>";
 echo "<br>";
 echo "</td></tr></table>";
}






function AddNA($string){
 if (trim($string) == ""){
   return "N/A";
 } else {
   return $string;
 }
}

function AddStarofDeath($string){
 if (trim($string) == ""){
   return "<font color=red ><b>*</b></font>";
 } else {
   return $string;
 }
}


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
