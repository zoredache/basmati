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
// $Id: uploadfile.php,v 1.1 2001/09/22 22:18:07 basmati Exp $

$LoginType = "";
session_start();

$LoginType = $HTTP_SESSION_VARS['LoginType'];

include ("basmatifunctions.php");

 if ($LoginType != "T" && $LoginType != "A"){
   echo("You must log-in to use this feature.");
   exit;
 }

?>

<html>
<head>
<title>Results from export file...</title>
<body bgcolor=white>
<h3>Results from Export File Transfer</h3>

<?php
include ("basmaticonstants.php");


  $SchoolID =    $HTTP_SESSION_VARS['SchoolID'];
  $UserID = $HTTP_SESSION_VARS['UserID'];

  $userfile = $HTTP_POST_FILES;
  $file = $userfile['userfile']['tmp_name'];


//Initialize Random Number Generator
  srand((double)microtime() * 1000000);


//Open the file the user just sent
if ($file=="none" || $file == ""){
  echo("You did not submit a file.");
  exit;
}

if (!($fp = fopen($file,"r"))){
  echo("could not open file for reading");
  exit;
}


//Set global variables
  global $studentdata;


//Read the file line-by-line (up to 64k per line) and start parsing the data
while ($lineoftext=fgets($fp,65536)){
  $data = $data . $lineoftext;
}
fclose($fp);
//while ($data=fread($fp,filesize($file))){  //This is the preferred method, but does not work well in Windows environment


//replace any ampersands with the + sign (not sure why, but XML parser does not like ampersand)
  $data = ereg_replace("&","+",$data);
  $data = ereg_replace("'","`",$data);

//Must assume Magic-Quotes are off!
  $data = addslashes($data);
  $data = trim($data); //added on sept 24 2000
  //$data = ereg_replace(chr(34),"`",$data);

//Now, strip off the BasmatiL and /BasmatiL tags (for purpose of multi-class files)
  $data = ereg_replace("<BasmatiL>","",$data);
  $data = ereg_replace("</BasmatiL>","",$data);
  $rawdata = $data;
  $data = "";


//Locate any instances of the "CLASS" tag and separate each into its own array
  $eachclass = explode("<CLASS>",$rawdata);
  $nclasses = sizeof($eachclass) - 1;
  if ($nclasses > 1) echo "Detected $nclasses classes in this file.<br>";
  for ($ncl=1; $ncl < sizeof($eachclass); $ncl++){
  	//Append the stripped tags back on the data...
  	$tempvar = "<BasmatiL><CLASS>" . $eachclass[$ncl] . "</BasmatiL>";
  	$eachclass[$ncl] = $tempvar;
  	$tempvar = "";
  	$data = $eachclass[$ncl];
  	$studentdata = NULL;
  	$nstudent = NULL;
  	$nassign = NULL;
    $classinfo = NULL;
    $assignlist = NULL;
    $pointlist = NULL;
    $ealrlist = NULL;



	//Create an XML parser to analyze the file
  	$parser = xml_parser_create();
 	xml_set_element_handler($parser, "startElementHandler", "endElementHandler");
  	xml_set_character_data_handler($parser,"cdataHandler");



  	if(!xml_parse($parser,$data)){
   		die(sprintf("<font color=red>There is an error in your export file at line %d and column
   		%d.  Please verify that your file is a valid export file.  (Error code: %s)</font>",xml_get_current_line_number($parser),xml_get_current_column_number($parser),xml_error_string(xml_get_error_code($parser))));
  	}




	//Dispose of the parser
	xml_parser_free($parser);


	//Verify that the email address in the file is equal to the address of the current user
	//Only allow this user to post their own files...  (this may be removed if desired)
    if (trim(strtolower($classinfo["MISC1"])) != trim(strtolower($UserID))) {
       echo ("<font color=red>You may only submit your own files.  Please log in as the appropriate user and resubmit this file.</font>");
       fnCloseDB();
       exit;
     }


	//Verify all data (make sure there are course codes, names, etc...)
	if(verifydata()==0){
  		exit;
  		//exit from program here... (call closing routines first though...)
 	}


	//Create a WSIPC compliant course-code
  	createWSIPCcc();

	//Open the database...
  	fnOpenDB();    //odbc-mysql


	//Now verify that account exists, password is correct and account has not expired...
	//Verify account will return school ID if everything OK, otherwise a 0
 	$SCHID = VerifyAccount();  //odbc-mysql
 	if ($SCHID == "EXIT"){
  		fnCloseDB();
  		exit;
 	}


	//Add data into COURSEINFO table (remove old data first)
 	if (fnAddCourseInfo() == 0){      //odbc-mysql
  		//exit from program here
 	}

	//Add data into GMSCORES table (remove any old data first)
  	fnAddGMScores();  //odbc-mysql

	//Add data into PERSONAL table (use update routine!)
  	fnAddPersonal();


	echo("<hr>");
	echo "<b>Class $ncl</b><br>";
	echo($classinfo["TNAME"]."<br>");
	echo("Your export file for <i>".$classinfo["CNAME"]." </i>contained $nstudent student(s) and $nassign assignment(s).");
    writelog("UP-FAC",$UserID,$SCHID);
	echo("<br><i>Summary...</i><br>");
	echo("<table border=1><tr><td><b>ID</t></td><td><b>GRADE</b></td></tr>");
	for ($i=1;$i<=$nstudent;$i++){
 		echo ("<tr>");
 		echo ("<td>".$studentdata[$i]["ID"]."</td><td>".$studentdata[$i]["GRADE"]."</td></tr>");
	}
	echo ("</table>");
} //for eachclass

//Close database
  fnCloseDB();  //odbc-mysql


//---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------
//Functions------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------


function fnAddPersonal(){
  global $datamethod;
  global $link;
  global $SCHID;
  global $nstudent;
  global $studentdata;
  global $classinfo;

  $sql_discover = "select distinct sid from GMSCORES where cc = '".
          $classinfo["CC"] . "' and schoolid = '" . $SCHID .
          "' and sid not in (select sid from PERSONAL where schoolid = '" .
          $SCHID . "');";

  $sql_discover_mysql = "select distinct GMSCORES.sid from GMSCORES
                         LEFT JOIN PERSONAL ON GMSCORES.SID = PERSONAL.SID
                         AND GMSCORES.SCHOOLID = PERSONAL.SCHOOLID
                         WHERE PERSONAL.SID IS NULL AND GMSCORES.CC = '" .
                         $classinfo["CC"]. "' and GMSCORES.SCHOOLID = '" .
                         $SCHID . "';";


 if ($datamethod == "odbc") {
  $sql_statement = odbc_prepare($link,$sql_discover);
  $sql_result = odbc_execute($sql_statement);
  //start adding new students...
     while (odbc_fetch_row($sql_statement)){
      $stupwd = randomPassword(6);
      $sql_add = "insert into PERSONAL(
                  sid,
                  schoolid,
                  password)
                VALUES (".
                  odbc_result($sql_statement,'sid').","
                  ."'".$SCHID."',"
                  ."'" . $stupwd . "');";

      $sql_statement_add = odbc_prepare($link,$sql_add);
      $sql_result = odbc_execute($sql_statement_add);
      odbc_free_result($sql_statement_add);
     } //end while
  odbc_free_result($sql_statement);
 } //end of ODBC

 if ($datamethod == "mysql") {
  if (!$sql_result = mysql_query($sql_discover_mysql))
   {echo ("Error $sql_discover_mysql");
  }
  //start adding new students...
     for($i=0;$i<mysql_num_rows($sql_result);$i++){
      $spw = randomPassword(6);
      $sql_add = "insert into PERSONAL(
                  sid,
                  schoolid,
                  password)
                VALUES (".
                  mysql_result($sql_result,$i,'sid').","
                  ."'".$SCHID."',"
                  ."'" . $spw . "');";


      $sql_statement_add = mysql_query($sql_add,$link);
     } //end for
 } //end of mysql



}



function fnAddGMScores(){
  global $datamethod;
  global $link;
  global $SCHID;
  global $classinfo;
  global $nstudent;
  global $studentdata;
  $sql_rem = "delete from GMSCORES where cc = '" . $classinfo["CC"] . "'
     and schoolid = '" . $SCHID ."'";

//delete old records...
 if ($datamethod == "odbc"){
  $sql_statement = odbc_prepare($link,$sql_rem);
  $sql_result = odbc_execute($sql_statement);
  odbc_free_result($sql_statement);
 } // end of odbc

 if ($datamethod == "mysql"){
   if (!mysql_query($sql_rem,$link)){
     echo("Error deleting GMSCORES");
   }
 }


//start adding new records...
 for ($i=1;$i<=$nstudent;$i++){
  $sql_add_mysql = "
    INSERT INTO GMSCORES(
     sid,
     cc,
     schoolid,
     scores,
     percent,
     grade,
     comments)
  VALUES
   (
    ".$studentdata[$i][ID].",
    '".$classinfo["CC"]."',
    '".$SCHID."',
    '".$studentdata[$i]["SCORES"]."',
    ".$studentdata[$i]["PERCENT"].",
    '".$studentdata[$i]["GRADE"]."',
    '".$studentdata[$i]["COMMENTS"]."');";

$sql_add_odbc = "
    INSERT INTO GMSCORES(
     sid,
     cc,
     schoolid,
     scores,
     [percent],
     grade,
     comments)
  VALUES
   (
    ".$studentdata[$i][ID].",
    '".$classinfo["CC"]."',
    '".$SCHID."',
    '".$studentdata[$i]["SCORES"]."',
    ".$studentdata[$i]["PERCENT"].",
    '".$studentdata[$i]["GRADE"]."',
    '".$studentdata[$i]["COMMENTS"]."');";



   if ($datamethod == "odbc"){
    $sql_statement = odbc_prepare($link,$sql_add_odbc);
    $sql_result = odbc_execute($sql_statement);
    odbc_free_result($sql_statement);
   } // end of odbc

   if ($datamethod == "mysql"){
    if (!mysql_query($sql_add_mysql,$link)){
     echo("Error adding GMSCORES");
     echo(sprintf("error: %d %s",mysql_errno($link),mysql_error($link)));
    }
   }



 } // close for loop

    return 1;

}






function fnOpenDB(){
 global $link;
 include ("basmaticonstants.php");
 if ($datamethod == "odbc"){
  $link = odbc_connect($databasename,$datausername,$datapassword);
 } // end of odbc
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

 if ($datamethod == "odbc"){
   odbc_close($link);
 } // end of odbc
 if ($datamethod == "mysql"){
   mysql_close($link);
 }
}






function fnAddCourseInfo(){
  global $datamethod;
  global $link;
  global $SCHID;
  global $classinfo;
  global $pointlist, $ealrlist, $assignlist;
  $sql_rem = "delete from COURSEINFO where cc = '" . $classinfo["CC"] . "'
     and schoolid = '" . $SCHID ."'";
  $sql_add = "
    INSERT INTO COURSEINFO(
     cc,
     schoolid,
     facultyname,
     facultycode,
     period,
     email,
     phone,
     misc,
     coursename,
     assignlist,
     assignvals,
     ealr,
     modified,
     type)
  VALUES
   ('".$classinfo["CC"]."',
    '".$SCHID."',
    '".$classinfo["TNAME"]."',
    '".$classinfo["TID"]."',
    '".$classinfo["PER"]."',
    '".$classinfo["MISC1"]."',
    '".$classinfo["MISC2"]."',
    '".$classinfo["MISC3"]."',
    '".$classinfo["CNAME"]."',
    '".$assignlist."',
    '".$pointlist."',
    '".$ealrlist."',
    '".strftime(date("m/d/Y h:iA"))."',
    'GM');";


 if ($datamethod == "odbc"){
  $sql_statement = odbc_prepare($link,$sql_rem);
  $sql_result = odbc_execute($sql_statement);
  $sql_statement = odbc_prepare($link,$sql_add);
  $sql_result = odbc_execute($sql_statement);
  odbc_free_result($sql_statement);
 } // end of odbc
 if ($datamethod == "mysql"){
   if (!mysql_query($sql_rem,$link)){
     echo("Error deleting COURSEINFO");
   }
   if (!mysql_query($sql_add,$link)){
     echo("Error adding COURSEINFO");
   }
 } // end of mysql


return 1;
}







function VerifyAccount(){
 global $classinfo;
 global $datamethod,$link;
 global $nassign;

 $sql = "select * from CLIENTS where client_id = '" . trim($classinfo["MISC1"]).
        "' and client_pw = '" . trim($classinfo["TID"]) . "'";
 if ($datamethod == "odbc"){
  $sql_statement = odbc_prepare($link,$sql);
  $sql_result = odbc_execute($sql_statement);
  $school_id = odbc_result($sql_statement,client_school);
  odbc_free_result($sql_statement);
 } // end of odbc
 if ($datamethod == "mysql"){
  $school_id = "";
  $sql_result = mysql_query($sql,$link);
  $rowcount = (mysql_num_rows($sql_result));
  if ($rowcount != 0){
    $school_id = mysql_result($sql_result,0,'client_school');
    mysql_free_result($sql_result);
   }
  } // end of mysql

 if($school_id == ""){
  echo("<font color=red>BAD PASSWORD--</FONT>  Your export file does not contain a valid username/password.");
  return "EXIT";
 }
  return $school_id;
}



function verifydata(){
  $returnval=1;
  echo ("<font color=red>");
  global $classinfo;
  global $nassign;

  if ($classinfo["TNAME"]==""){
    echo("Warning: No Teacher Name can be found.  Your grades may still post.<br>");
  }
  if ($classinfo["CC"] == ""){
    echo("No course-code is present -- cannot post file.  Check <i>Class-Class Info</i> section of Grade Machine and make sure a course-code exists.<br>");
    $returnval = 0;
  }
  if (eregi(" ",$classinfo["CC"])){
    echo("Your coursecode and/or section number must not contain any spaces.  Please remove any spaces from Grade Machine's Class-Class Info course-code and/or section and resubmit your data.<br>");
    $returnval = 0;
  }
  if($classinfo["CNAME"] == "") {
    echo ("Warning:  No Course Name was found in this file.  Data will not be posted.  Check <i>Class-Class Info</i> section of Grade Machine.<br>");
    $returnval = 0;
  }
  if ($classinfo["MISC1"] == "") {
    echo ("Warning:  No Email Address was found in this file.  Data will not be posted.  Check <i>Class-Class Info</i> section of Grade Machine and make sure your username is in MISC1 field.<br>");
    $returnval = 0;
  }
    if ($classinfo["TID"] == "") {
    echo ("Warning:  No Teacher ID was found in this file.  Data will not be posted.  Check <i>Class-Class Info</i> section of Grade Machine to make sure your password is in the Teacher ID field.<br>");
    $returnval = 0;
  }
    if ($nassign < 1) {
    echo ("Warning:  Your export file must contain at least one assignment.  Data will not be posted.<br>");
    $returnval = 0;
  }


  echo("</font>");
  return $returnval;
}


function addtoMySQLdb(){
 global $classinfo;
 global $assignlist;
 global $pointlist;
 global $ealrlist;
 global $nstudent;
 global $studentdata;
 global $datausername,$datapassword,$databasename;

 //First add information to COURSEINFO table
 $cc = $classinfo["CC"].$classinfo["SN"];

$link = mysql_connect($databaseserver,$username,$password) or die ("Couldn't connect to server");
//echo ($link);
if (! mysql_select_db($databasename,$link)){
 echo ("<B><br>Error:  Cannot Select DB</b> -- please contact administrator.");
}

$sql = "
    INSERT INTO COURSEINFO(
     cc,
     schoolid,
     facultyname,
     facultycode,
     period,
     email,
     phone,
     misc,
     coursename,
     assignlist,
     assignvals,
     ealr,
     modified,
     type)
  VALUES
   ('".$classinfo["CC"]."',
    '".$schoolid."',
    '".$classinfo["TNAME"]."',
    '".$classinfo["TID"]."',
    '".$classinfo["PER"]."',
    '".$classinfo["MISC1"]."',
    '".$classinfo["MISC2"]."',
    '".$classinfo["MISC3"]."',
    '".$classinfo["CNAME"]."',
    '".$assignlist."',
    '".$pointlist."',
    '".$ealrlist."',
    '".strftime(date("m/d/Y h:iA"))."',
    'GM');";

mysql_query($sql,$link);
mysql_close($link);
}

function startElementHandler($parser,$name,$attribs){
 global $current_xmltree;
// echo ("<b>&lt;$name&gt;<br></b>");
  $current_xmltree=$current_xmltree."_".$name;


}

function endElementHandler($parser,$name){
//  echo ("<b>&lt/$name&gt;<br></b>");
global $current_xmltree;

  $new_xmltree=ereg_replace("_".$name,"",$current_xmltree);
  $current_xmltree = $new_xmltree;
}

function cdataHandler($parser, $data) {
 $sepval = chr(169);
 global $current_xmltree;
 global $classinfo;
 global $assignlist;
 global $pointlist;
 global $ealrlist;
 global $nstudent, $nassign;
 global $studentdata;

//   echo($current_xmltree."<br>");

//echo("<font color=green>$data</font><br>");
//  echo($root_tree);
//Add a CLASS root to array...
 $root_tree = "_BASMATIL_CLASS_";
 if(ereg($root_tree,$current_xmltree)){
   //echo ($data);
   $keyval=substr($current_xmltree,strlen($root_tree));
   //echo($keyval."  =".$data."<br>");
   $classinfo[$keyval]=trim($data);
 }


//Create the Assign vals and add to array when finished.
 $root_tree = "_BASMATIL_ASSIGN_";
 if(ereg($root_tree,$current_xmltree)){
   $keyval=substr($current_xmltree,strlen($root_tree));
   if ($keyval=="DESCR"){
    $nassign++;
    $assignlist = $assignlist.trim($data).$sepval;
    }
   if ($keyval=="POINTS"){
    $pointlist = $pointlist.trim($data).$sepval;
    }
   if ($keyval=="EALR"){
    $ealrlist = $ealrlist.trim($data).$sepval;
    }
  }

//Now parse the Student vals...
 $root_tree="_BASMATIL_STUDENT_";
 if(ereg($root_tree,$current_xmltree)){
   $keyval=substr($current_xmltree,strlen($root_tree));
   if ($keyval == "ID"){
    $nstudent++;
    $data = intval($data);   //Convert to an integer...
    }
    if ($keyval == "PERCENT") {
     $data = doubleval($data);
    }
    if ($keyval == "SCORES") {
     $data = trim(ereg_replace(",",chr(169),$data)) . chr(169);
    }
    $studentdata[$nstudent][$keyval] = trim($data);
   }
 }

function createWSIPCcc(){
 //Here... we will append an extra 0 to single digit section numbers
 //and create a single course code combining CC and SN
 global $classinfo;
 if (strlen($classinfo["SN"])==1){
   $classinfo["SN"] = "0".$classinfo["SN"];
 }
 $classinfo["CC"] = $classinfo["CC"] . $classinfo["SN"];

}



function randomPassword($length){

  $possible = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
  $str = "";
  while (strlen($str) < $length) {
    $str .= substr($possible,(rand() % strlen($possible)),1);
  }
  return ($str);
}

?>
