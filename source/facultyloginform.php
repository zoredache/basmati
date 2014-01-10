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
<title>Login as Faculty Member</title>
<LINK rel="stylesheet" type = "text/css" href="style.css" title="style1">
</head>
<h3>Faculty Login Page</h3>

<?php

  echo "<TABLE BORDER=0>";
  if (trim($emaildomain) == "") {
    echo "<form method=post action=facultylogin.php name=loginform>";
  	echo "<TR><TD>Username:  </TD><TD><input type=text name=uname size=30></TD></TR>";
    echo "<TR><TD>Password:  </TD><TD><input type=password name = pword size=30></TD></TR>";
    echo "<TR><TD COLSPAN=2><input type=submit value='Log-In'></TD></TR>";
    echo "</form>";
  } else {
    echo "<form method=post action=facultylogin.php name=loginform onsubmit = 'return checkemail()'>";
  	echo "<TR><TD>Username:  </TD><TD><input type=text name=uname size=30><b>@$emaildomain</b></TD></TR>";
    echo "<TR><TD>Password:  </TD><TD><input type=password name = pword size=30></TD></TR>";
    echo "<TR><TD COLSPAN=2><input type=submit value='Log-In'></TD></TR>";
    echo "</form>";
  }
	echo "</TABLE>";
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

