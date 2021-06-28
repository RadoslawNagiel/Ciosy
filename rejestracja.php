<!DOCTYPE html>
<head>
	<meta charset='utf-8'>
	<title>rejestracja</title>
	<link rel=stylesheet href=main.css>
</head>
<body style='background-color:#493b24;color:#dbc8a7; font-size:17px; text-align: center; line-height:150%;'>

	<center>
	<br>
	<table>
	<tr>
		<form id='logowanie' method='POST' action='index.php'>
		<?php
		$login='';
			if (isset($_POST['login']))
			{
				$login=$_POST['login'];
			}		
		echo	"<input type='text' name='login' value='$login' placeholder='Login' required/><br>";
		?>
			<input type='password' name='haslo' placeholder='Hasło' required/><br>
			<input type='password' name='phaslo' placeholder='Powtórz hasło' required/><br>
			<input type="hidden" name="rejestracja" value="true">
			<input type='submit' value='Zarejestruj' class='przycisk'>
		</form>
		<br>
		<br>
		<form id='log' method='POST' action='index.php'>
			<input type='submit' value='Logowanie' class='przycisk'>
		</form>
	</tr>
	</table>
</body>

<?php
if(isset($_POST['login']))
{
	$login=$_POST['login'];
	$haslo=$_POST['haslo'];
	$poprawnosc=true;
	if(!$login)
	{
		echo "Podaj nick<br>";
		$poprawnosc=false;
	}
	else if(strlen($login)<5 || strlen($login)>20) 
	{
		echo "login musi mieć od 5 do 20 znaków<br>";
		$poprawnosc=false;
	}
	else
	{
		$serwer = @mysqli_connect('localhost','root','','ciosy') or die("Błąd połączenia z serwerem");
		//$serwer = @mysqli_connect('mysql.cba.pl','ciosy','1qaz@WSX','ciosy') or die("Błąd połączenia z serwerem");
		$SprLogin = @mysqli_num_rows(mysqli_query($serwer,"SELECT * FROM profil WHERE login='$login'"));	
		@mysqli_close($serwer);
		if($SprLogin)
		{
			echo "Login zajęty<br>";
			$poprawnosc=false;
		}
	}
	if($haslo)
	{
		 if(strlen($haslo)<5 || strlen($haslo)>20) 
		{
			echo "hasło musi mieć od 5 do 20 znaków<br>";
			$poprawnosc=false;
		}
		else if($_POST['phaslo'])
		{
			$phaslo=$_POST['phaslo'];
			if($haslo!=$phaslo)
			{
				echo "Hasła niezgodne";	
				$poprawnosc=false;
			}
		}
		else
		{
			echo "Potwierdź hasło<br>";	
			$poprawnosc=false;
		}
	}
	else
		echo "Podaj hasło<br>";
	if($poprawnosc)
	{
		$serwer = @mysqli_connect('localhost','root','','ciosy') or die("Błąd połączenia z serwerem");
		//$serwer = @mysqli_connect('mysql.cba.pl','ciosy','1qaz@WSX','ciosy') or die("Błąd połączenia z serwerem");
		$login=$_POST['login'];
		$haslo=$_POST['haslo'];
		@mysqli_query($serwer,"INSERT INTO profil (login,haslo) VALUES ('$login','$haslo')") or die("Błąd dodawania");
		@mysqli_close($serwer);
		echo "Rejestracja przebiegła pomyślnie";
	}
}
?>