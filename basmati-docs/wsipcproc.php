<?
//Process uploaded file...
$file = $userfile;
if ($file=="none" || $file == ""){
  echo("You did not submit a file.");
  exit;
}

if (!($fp = fopen($file,"r"))){
  echo("could not open file for reading");
  exit;
}
echo("<pre>");
$tab = chr(9);
while (!feof($fp)){
 $stuff =  fgetcsv($fp,4096,$tab);
 $cc = strtoupper($stuff[0]);
 $id = strtoupper($stuff[1]);
 $gr = strtoupper($stuff[2]);
 $cm = strtoupper($stuff[3]);
 
 //The work begins here... processing the file 
 $wcc = substr($cc,0,6);   //WSIPC Course-Code
 $wsn = substr($cc,strlen($cc)-2,2);  //Section number
 //Now if leading character is a 0, we need to replace with underscore
 if (substr($wsn,0,1) == 0){
   $wsn = "_" . substr($wsn,1,1);
 }
 
//Change any underscores to minuses
 $gr = eregi_replace("_","-",$gr);
 
//Change F+ or F- to F
   $gr = eregi_replace("F\+","F",$gr);
   $gr = eregi_replace("F-","F",$gr);



if ($aplus=="on"){
  $gr = eregi_replace("A\+","A",$gr);
}
if ($dminus == "on"){
  $gr = eregi_replace("D-","D",$gr);
}

if ($gtype == "q") { 
 $qgr = $gr;
 $fgr = "";
} else {
 $qgr = "";
 $fgr = $gr;
}  

   
 if ($id !=0){
  echo $wcc.$wsn. "-" .$id .$tab.$id.$tab.$qgr.$tab.$fgr.$tab.$wcc.$tab.$wsn.$tab.$cm.$tab."0".$tab."0" ;
  echo (chr(13));
 }
}
fclose($fp)
?>
