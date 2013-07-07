<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<style>

ul {
	list-style-type:none;
	background-color:#999;
	color:$FFF;
	width:250px;
	float:left;
}

li {
	border-bottom:1px #000 solid;
}

</style>

</head>

<body>

<?php
	$username="mutual_admin";
	$password="v]zgp2#bX3kG";
	$database="mutual_nafhatest";

	mysql_connect(localhost,$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	$query="SELECT * FROM lineups WHERE Week='3'";
	$result=mysql_query($query);
	
	$num=mysql_numrows($result);
	
	mysql_close();
	
	echo "<b><center>Week One Lineups</center></b><br><br>";
	
	$i=0;
	while ($i < $num) {
	
	$Name=mysql_result($result,$i,"Name");
	$Team=mysql_result($result,$i,"Team");
	$Week=mysql_result($result,$i,"Week");
	$LeftWing=mysql_result($result,$i,"LeftWing");
	$Center=mysql_result($result,$i,"Center");
	$RightWing=mysql_result($result,$i,"RightWing");
	$DefenseOne=mysql_result($result,$i,"DefenseOne");
	$DefenseTwo=mysql_result($result,$i,"DefenseTwo");
	$Goalie=mysql_result($result,$i,"Goalie");
	$BackupCenter=mysql_result($result,$i,"BackupCenter");
	$BackupWing=mysql_result($result,$i,"BackupWing");
	$BackupDefense=mysql_result($result,$i,"BackupDefense");
	
	echo "<ul><li>Name: $Name</li><li>Team: $Team</li><li>Week: $Week</li><li>LW: $LeftWing</li><li>C: $Center</li><li>RW: $RightWing</li><li>D: $DefenseOne</li><li>D: $DefenseTwo</li><li>G: $Goalie</li><li>BC: $BackupCenter</li><li>BW: $BackupWing</li><li>BD: $BackupDefense</li></ul>";
	
	$i++;
}

?>

</body>
</html>