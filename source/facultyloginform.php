<html><head>
<!-- ------------------------------------------------------------------ -->
<!--  Basmati -- Version 2.0P                                           -->
<!-- ------------------------------------------------------------------ -->
<!-- Copyright (C) 2000-2001 James B. Bassett (basmatisoftware@msn.com) -->
<!-- ------------------------------------------------------------------ -->
<!-- This program is free software.  You can redistribute in and/or     -->
<!-- modify it under the terms of the GNU General Public License        -->
<!-- Version 2 as published by the Free Software Foundation.            -->
<!-- ------------------------------------------------------------------ -->
<!--  Authors: James B. Bassett - basmatisoftware@msn.com               -->
<!-- ------------------------------------------------------------------ -->
<!-- $Id: facultyloginform.php,v 1.2 2001/11/01 20:51:27 basmati Exp $ -->
<title>Login as Faculty Member</title>
<LINK rel="stylesheet" type = "text/css" href="style.css" title="style1">
</head>
<h3>Faculty Login Page</h3>

<?php

  include("basmaticonstants.php");

  if (trim($emaildomain) == "") {
    echo "<form method=post action=facultylogin.php name=loginform>";
  	echo "Username:  <input type=text name=uname size=30><br>";
    echo "Password:  <input type=password name = pword size=30><br>";
    echo "<input type=submit value='Log-In'>";
    echo "</form>";
  } else {
    echo "<form method=post action=facultylogin.php name=loginform onsubmit = 'return checkemail()'>";
  	echo "Username:  <input type=text name=uname size=30><b>@$emaildomain</b><br>";
    echo "Password:  <input type=password name = pword size=30><br>";
    echo "<input type=submit value='Log-In'>";
    echo "</form>";
  }

    echo "<p><hr>";
	echo $announcement;
?>

</body>
</html>
<script language=JavaScript>
  function checkemail(){
  	var txtUser=loginform.uname.value;
    var character=txtUser.indexOf("@");
    if (character > 0){
    	var lop = txtUser.substr(0,character);
        alert ("You only need to type the user-name portion of your email address (" + lop + ") in the Username box.");
        document.loginform.uname.value = lop;
        return true;
    }
  }
</script>

