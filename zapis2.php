<?php
	//$serwer = @mysqli_connect('mysql.cba.pl','ciosy','1qaz@WSX','ciosy') or die("Błąd połączenia z serwerem");
	$serwer = mysqli_connect('localhost','root','','ciosy');
	session_start();
	$id=$_SESSION['id_profil'];
	$m=$_POST['m'];
	$pol="UPDATE postac SET map='$m' WHERE id_postaci=$id;";
	mysqli_query($serwer,$pol);
	mysqli_close($serwer);
	echo $pol
?>