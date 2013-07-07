<?php 

	$username="mutual_admin";
	$password="v]zgp2#bX3kG";
	$database="mutual_nafhatest";
	
	$Name=$_POST['Name'];
	$Team=$_POST['Team'];
	$Week=$_POST['Week'];
	$LeftWing=$_POST['LeftWing'];
	$Center=$_POST['Center'];
	$RightWing=$_POST['RightWing'];
	$DefenseOne=$_POST['DefenseOne'];
	$DefenseTwo=$_POST['DefenseTwo'];
	$Goalie=$_POST['Goalie'];
	$BackupCenter=$_POST['BackupCenter'];
	$BackupWing=$_POST['BackupWing'];
	$BackupDefense=$_POST['BackupDefense'];
	
	mysql_connect(localhost,$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	
	$query = "INSERT INTO lineups VALUES
	('','$Name','$Team','$Week','$LeftWing','$Center','$RightWing','$DefenseOne','$DefenseTwo','$Goalie','$BackupCenter','$BackupWing','$BackupDefense')";
	
	mysql_query($query);
	
	mysql_close();
?>
