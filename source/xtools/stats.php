<?php
	include("../basmaticonstants.php");

//Open the database...
    if ($datamethod == "mysql"){
	  $link = mysql_connect($databaseserver,$datausername,$datapassword) or die
	  ("Couldn't connect to server");
 	 	if (! mysql_select_db($databasename,$link)){
  		   echo ("<B><br>Error:  Cannot Select DB</b> -- please contact administrator.");
  		}
 	}

//Start constructing questions....
   $sql = "select min(LastUpdate) as m from EVENTLOG";
    $result = mysql_query($sql,$link);
	$m = mysql_result($result,m,0);
    $year = substr($m,0,4);
    $month = substr($m,4,2);
    $day = substr($m,6,2);
    echo "<b>Basmati Statistics since $month-$day-$year</b><hr>";


//Number of activities
    $sql = "select max(id) as n from EVENTLOG";
    $result = mysql_query($sql,$link);
	$n = mysql_result($result,0,n);
    echo "Total Number of Events Logged: $n<p>";

//Number of Student-Parent Logins....
    $sql = "select count(*) as n from EVENTLOG where eventid = 'IN-STU'";
    $result = mysql_query($sql,$link);
	$n = mysql_result($result,0,n);
    echo "Total Number of Student/Parent Events: $n<br>";

//Number of Student-Parent Logins from campus....
    $sql = "select count(*) as n from EVENTLOG where eventid = 'IN-STU' and ipaddr like '$localsubnet'";
    $result = mysql_query($sql,$link);
	$n = mysql_result($result,0,n);
    echo "Total Number of Student/Parent Events (on-campus): $n<br>";

//Number of Student-Parent Logins from OFF campus....
    $sql = "select count(*) as n from EVENTLOG where eventid = 'IN-STU' and ipaddr not like '$localsubnet'";
    $result = mysql_query($sql,$link);
	$n = mysql_result($result,0,n);
    echo "Total Number of Student/Parent Events (off-campus): $n<p>";

//Activity by Schools (parent/student logins)....
    $sql = "select count(*) as n, schoolid from EVENTLOG where eventid = 'IN-STU' group by schoolid  order by n desc";
    $result = mysql_query($sql,$link);
    for ($i = 0; $i < mysql_num_rows($result); $i++){
	 $n = mysql_result($result,$i,n);
     $s = mysql_result($result,$i,schoolid);
     echo "Total Number of Student/Parent Events for $s: $n<br>";
    }
    echo "<p>";

//Grade Uploads by Schools
	$sql = "select count(*) as n, schoolid from EVENTLOG where eventid = 'UP-FAC' group by schoolid  order by n desc";
    $result = mysql_query($sql,$link);
    for ($i = 0; $i < mysql_num_rows($result); $i++){
	 $n = mysql_result($result,$i,n);
     $s = mysql_result($result,$i,schoolid);
     echo "Total Number of Grade Upload Events for $s: $n<br>";
    }
    echo "<p>";

//Number of Grades in system by school....
//Grade Uploads by Schools
	$sql = "select count(*) as n, schoolid from GMSCORES  group by schoolid  order by n desc";
    $result = mysql_query($sql,$link);
    for ($i = 0; $i < mysql_num_rows($result); $i++){
	 $n = mysql_result($result,$i,n);
     $s = mysql_result($result,$i,schoolid);
     echo "Total Number of Current Student Grades for $s: $n<br>";
    }


//Close the database
    mysql_close($link);




//Close the database...
?>
