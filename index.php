<!DOCTYPE html>
<head>
	<meta charset='utf-8'>
	<title>LP</title>
	<link rel=stylesheet href=main.css>
	<link rel="Shortcut icon" href="ikona.ico" />
</head>
<body style='background-color:#6bab40;color:#dbc8a7; font-size:20px;'>
<?php session_start();?>
	<div id='strona'>
		<div id='baner' class='baner-image' style="background-image: url('baner.png');">
		</div>
		<div id='menu'>
			<?php include("menu.php");?>
		</div>
		<div id='tresc'>
			<center>
			<?php include("tresc.php");?>
		</div>
		<div id='logowanie'>
			<?php
			if(isset($_SESSION['login']))
				include("zalogowany.php");
			else if(isset($_POST['rejestracja']))
				include("rejestracja.php");
			else include("logowanie.php");
			?>
		</div>
		<div style="clear:both;"></div>
		<div id='stopka'>
			<table width='100%'>
				<tr><td>
				<?php
					define("podpis","Radosław Nagiel");
					echo podpis.date(" Y.m.d G:i");
				?>
				</tr></td>
			</table>
		</div>
	</div>
</body>