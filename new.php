<form method='POST' action='zapis2.php'>
	<?php
	$m="";
		for($y=0;$y<1000000;$y++)
			{
				$m.="z";
			}
	echo "<input type='hidden' name='abc' value='$m'>";
	?>
	<input type='submit' value='wyslij'>
</form>