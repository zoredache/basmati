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
// $Id: createdb.php,v 1.1 2001/09/22 22:17:57 basmati Exp $

 $LoginType = "";
  session_start();
 require("basmaticonstants.php");
 if ($LoginType != "A"){
   echo("You must log-in to use this feature.");
   exit;
 }
?>

<html><head><title>Creating Basmati Database for MySQL</title>
</head>
<body bgolor=white>
<h3>Loading this page will create a MySQL or ODBC database called "<?=$databasename?>"</h3>



<?php
include ("basmaticonstants.php");


$sql_CLIENTS = "
  CREATE TABLE CLIENTS (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    client_id		varchar(80) NOT NULL,
    client_pw		varchar(16),
    client_school	varchar(16),
    client_expdate	date,
    client_fullname	varchar(50),
    webinfo			mediumtext,
    webinfodate		date,
    LastUpdate		TIMESTAMP
    );";

$sql_CLIENTS_ODBC = "
  CREATE TABLE CLIENTS (
    client_id		varchar(80),
    client_pw		varchar(16),
    client_school	varchar(16),
    client_expdate	date,
    client_fullname	varchar(50)
    webinfo			memo,
    webinfodate		date
    );";



$sql_COURSEINFO = "
  CREATE TABLE COURSEINFO (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cc			varchar(30) NOT NULL,
    schoolid		varchar(16) NOT NULL,
    facultyname 	varchar(50),
    facultycode		varchar(50),
    period		varchar(8),
    email		varchar(50),
    phone		varchar(20),
    misc		varchar(80),
    coursename		varchar(50),
    assignlist		mediumtext,
    assignvals		mediumtext,
    ealr		mediumtext,
    modified	varchar(50),
    type		varchar(2),
    LastUpdate	TIMESTAMP
    );";

$sql_COURSEINFO_ODBC = "
  CREATE TABLE COURSEINFO (
    cc			varchar(30),
    schoolid		varchar(16),
    facultyname 	varchar(50),
    facultycode		varchar(50),
    period		varchar(8),
    email		varchar(50),
    phone		varchar(20),
    misc		varchar(80),
    coursename		varchar(50),
    assignlist		memo,
    assignvals		memo,
    ealr		memo,
    modified		date,
    type		varchar(2)
    );";




$sql_GMSCORES = "
  CREATE TABLE GMSCORES (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    sid 		integer NOT NULL,
    cc			varchar(30) NOT NULL,
    schoolid		varchar(16) NOT NULL,
    scores		mediumtext,
    percent		real,
    grade		varchar(50),
    comments		varchar(50),
    LastUpdate	TIMESTAMP
    );";

$sql_GMSCORES_ODBC = "
  CREATE TABLE GMSCORES (
    sid 		integer,
    cc			varchar(30),
    schoolid	varchar(16),
    scores		memo,
    percent		real,
    grade		varchar(50),
    comments		varchar(50)
    );";


$sql_GROUPS = "
  CREATE TABLE GROUPS (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    groupname 		varchar(20),
    groupsids	 	mediumtext,
    grouppw		varchar(16),
    schoolid		varchar(16),
    LastUpdate		TIMESTAMP
    );";


$sql_GROUPS_ODBC = "
  CREATE TABLE GROUPS (
    groupname 		varchar(20),
    groupsids	 	memo,
    grouppw		varchar(16),
    schoolid		varchar(16)
    );";


$sql_PERSONAL = "
  CREATE TABLE PERSONAL (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    sid 		integer NOT NULL,
    schoolid	 	varchar(16) NOT NULL,
    last		varchar(24),
    first		varchar(20),
    grade		varchar(2),
    password		varchar(16),
    did			mediumint,
    emailaddress	varchar(50),
    LastUpdate	TIMESTAMP
    );";

$sql_PERSONAL_ODBC = "
  CREATE TABLE PERSONAL (
    sid 		integer,
    schoolid	 	varchar(16),
    last		varchar(24),
    first		varchar(20),
    grade		varchar(2),
    password		varchar(16),
    did			integer,
    emailaddress	varchar(50)
    );";


$sql_SCHOOLS = "
  CREATE TABLE SCHOOLS (
    id 		INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    school_id 		varchar(16) NOT NULL,
    school_name	 	varchar(50),
    school_state	varchar(2),
    school_city		varchar(50),
    school_url		varchar(80),
    LastUpdate		TIMESTAMP
    );";


if ($datamethod == "mysql"){

 echo("Attempting to connect to MySQL");
 $link = mysql_connect($databaseserver,$datausername,$datapassword) or
die("Couldn't
 connect to server");
 echo("-- connected.");

 echo("<br>Attempting to create Database $databasename");

 if (!mysql_create_db($databasename,$link)){
   printError(sprintf("Error in creating %s database", $databasename));
   printError(sprintf("error: %d %s",mysql_errno($link),mysql_error($link)));
   exit();
 };
 echo ("-- database created.");


 echo("<br>Selecting database");
 if (!mysql_select_db($databasename,$link)){
   printError(sprintf("Error in selecting %s database", $databasename));
   printError(sprintf("error: %d %s",mysql_errno($link),mysql_error($link)));
   exit();
 };
 echo("-- OK");

//start creating tables
 echo("<br>Creating tables...<br>");
 fnMySqlQuery($sql_CLIENTS,$link);
 fnMySqlQuery($sql_COURSEINFO,$link);
 fnMySqlQuery($sql_GMSCORES,$link);
 fnMySqlQuery($sql_GROUPS,$link);
 fnMySqlQuery($sql_PERSONAL,$link);
 fnMySqlQuery($sql_SCHOOLS,$link);
 echo("<br>Created tables...");

 //Create INDEXes
 ECHO ("<BR>Creating INDEXes...</br>");
 fnMySqlQuery("CREATE INDEX client_id_index ON CLIENTS(client_id);",$link);
 fnMySqlQuery("CREATE INDEX courseinfo_cc_index ON COURSEINFO(cc);",$link);
 fnMySqlQuery("CREATE INDEX courseinfo_schoolid_index ON COURSEINFO(schoolid);",$link);
 fnMySqlQuery("CREATE INDEX gmscores_sid_index ON GMSCORES(sid);",$link);
 fnMySqlQuery("CREATE INDEX gmscores_cc_index ON GMSCORES(cc);",$link);
 fnMySqlQuery("CREATE INDEX gmscores_schoolid_index ON GMSCORES(schoolid);",$link);
 fnMySqlQuery("CREATE INDEX personal_sid_index ON PERSONAL(sid);",$link);
 fnMySqlQuery("CREATE INDEX personal_schoolid_index ON PERSONAL(schoolid);",$link);
 fnMySqlQuery("CREATE INDEX schools_school_id_index ON SCHOOLS(school_id);",$link);


 echo("<br>Closing database...");
 if (!mysql_close($link)){
  printError(sprintf("Error in closing database.", $link));
  printError(sprintf("error: %d %s",mysql_errno($link),mysql_error($link)));
  exit();
 };
 echo(" -- closed.");


} // end of mysql database routine...


//PROCESS IF ODBC

if ($datamethod == "odbc"){

 echo("Attempting to connect to ODBC");
 $link = odbc_connect($databasename,$username,$password) or die("Couldn't
 connect to server");
 echo("-- connected.");

 echo("<br>Attempting to create Database '$databasename'");



//start creating tables
 echo("<br>Creating tables...<br>");
 fnODBCQuery($sql_CLIENTS_ODBC,$link);
 fnODBCQuery($sql_COURSEINFO_ODBC,$link);
 fnODBCQuery($sql_GMSCORES_ODBC,$link);
 fnODBCQuery($sql_GROUPS_ODBC,$link);
 fnODBCQuery($sql_PERSONAL_ODBC,$link);
 fnODBCQuery($sql_SCHOOLS,$link);
 echo("<br>Created tables...");

 echo("<br>Closing database...");
 odbc_close($link);
 echo(" -- closed.");


} // end of ODBC database routine...



//----------------------------------FUNCTIONS
function fnMySqlQuery($sql,$link){
if (!mysql_query($sql,$link)){
  printError(sprintf("Error in %s query...", $sql));
  printError(sprintf("error: %d %s",mysql_errno($link),mysql_error($link)));
  exit();
  }
};

function fnODBCQuery($sql,$link){
 $sql_statement = odbc_prepare($link,$sql);
 $sql_result = odbc_execute($sql_statement);
 odbc_free_result($sql_statement);
}


function printError($errorMesg)
{
 printf("<br> %s <br>",$errorMesg);
}




?>
<p>
<font color=red size=+2>Finished!</font>


