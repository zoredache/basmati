<html>
<head>
<title>WSIPC Re-Processor</title>
</head>
<body bgcolor=white>
<font size=+1>WSIPC Re-Processor</font>
<hr>
<form method=post action=wsipcproc.php ENCTYPE="multipart/form-data">
Locate your WSIPC file produced from "SELECT cc,sid,grade,comments FROM GMSCORES" query.
<br> <input type=file name="userfile" value="Locate File">
<p>
Proecess this file...
<br>
<input type=submit value="Process this File">
<p>
<hr>
<font color=blue size=+1>O P T I O N S</font>
<br>
These grades are...<br>
<input type=radio name=gtype value="q" checked>Quarterly</br>
<input type=radio name=gtype value="f">Final</br>
<p>
Grade Changes...<br>
<input type=checkbox name=aplus checked>Change A+ to A   <br>
<input type=checkbox name=dminus checked>Change D- to D



</form>
</body>
</html>

