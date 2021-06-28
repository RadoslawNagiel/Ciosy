<table>
	<tr>
	<br>
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
	<input type='submit' value='Zaloguj' class='przycisk'>
	</form>
	<br>
	<br>
	<form id='rejestracja' method='POST' action='index.php'>
		<input type="hidden" name="rejestracja" value="true">
		<input type='submit' value='Rejestracja' class='przycisk'>
	</form>
	</tr>
</table>
<?php
if(isset($_POST['login']))
{
	$poprawnosc=true;
	if($_POST['login']==false)
	{
		echo "Podaj login<br>";
		$poprawnosc=false;
	}
	if($_POST['haslo']==false)
	{
		echo "Podaj hasło<br>";
		$poprawnosc=false;
	}
	if($poprawnosc==true)
	{
		$login=$_POST['login'];
		$haslo=$_POST['haslo'];
		
		$serwer = @mysqli_connect('localhost','root','','ciosy') or die("Błąd połączenia z serwerem");
		//$serwer = @mysqli_connect('mysql.cba.pl','ciosy','1qaz@WSX','ciosy') or die("Błąd połączenia z serwerem");
		$SprLogin=mysqli_num_rows(mysqli_query($serwer,"SELECT login FROM profil WHERE login='$login'"));	
		$SprHaslo=mysqli_num_rows(mysqli_query($serwer,"SELECT haslo FROM profil WHERE login='$login' and haslo='$haslo'"));
		if($SprLogin==false || $SprHaslo==false)
		    echo 'Zły login lub hasło<br>';
		else
		{
			$x = mysqli_fetch_object(mysqli_query($serwer,"SELECT * FROM profil WHERE login='$login' and haslo='$haslo'"))or die("Błąd pobierania danych");	
			$_SESSION['id_profil']=$x->id_profil;
			$_SESSION['login']=$x->login;
			$id=$x->id_profil;
			$Sprpostac=mysqli_num_rows(mysqli_query($serwer,"SELECT id_postaci FROM postac WHERE id_postaci='$id'"));
			if(!$Sprpostac)
			{
				$eq='0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0_0|0|0';
				@mysqli_query($serwer,"INSERT INTO postac (id_postaci,ekwipunek,zycie,glod,pragnienie) VALUES ('$id','$eq',100,100,100)") or die("Błąd dodawania eq");
			}
				@mysqli_close($serwer);
				header("Location: index.php"); 
		}
		@mysqli_close($serwer);
	}
}
?>