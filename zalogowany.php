<!DOCTYPE html>
<head>
	<meta charset='utf-8'>
	<title>rejestracja</title>
	<link rel=stylesheet href=main.css>
</head>
<body style='background-color:#493b24;color:#dbc8a7; font-size:17px; text-align: center; line-height:150%;'>

<?php
if(isset($_POST['wyloguj']))
	{
		session_start();
		session_destroy();
		header("Location: index.php"); 
	}
if(isset($_POST['graj']))
	{
		header("Location: gra.php"); 
	}
?>

	<center>
	<br>
	<table>
	<tr>
		<form method='POST' action='zalogowany.php'>
			<input type="hidden" name="wyloguj" value="true">
			<input type='submit' value='Wyloguj' class='przycisk'>
		</form>
		<br>
		<form method='POST' action='zalogowany.php'>
			<input type="hidden" name="graj" value="true">
			<input type='submit' value='Graj' class='przycisk'>
		</form>
		<br>
		<br>
	</tr>
	</table>
	

</body>