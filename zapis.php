<?php
	echo "WLO";
	$e=$_GET['e'];
	$z=$_GET['z'];
	$g=$_GET['g'];
	$p=$_GET['p'];
	$x=$_GET['x'];
	$y=$_GET['y'];
	//$serwer = @mysqli_connect('mysql.cba.pl','ciosy','1qaz@WSX','ciosy') or die("Błąd połączenia z serwerem");
	$serwer = mysqli_connect('localhost','root','','ciosy');
	session_start();
	$id=$_SESSION['id_profil'];
	$pol="UPDATE postac SET ekwipunek='$e', zycie='$z', glod='$g', pragnienie='$p',  posX='$x',  posY='$y' WHERE id_postaci=$id;";
	mysqli_query($serwer,$pol);
	mysqli_close($serwer);
?>