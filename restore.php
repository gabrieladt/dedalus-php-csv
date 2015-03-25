<?php
if(!empty($_GET['id'])) {
	$db = new SQLite3('./db/mydb');
	$id=$_GET['id'];
	$update_base = $db->query("update baseawscsv set removed=0 where id = $id"); 
 	
	}
	print "<center><br><br><a href=\"./parse.php\"><h2>RESTORE EFETUADO - VOLTAR</h2></a></center>";
?>
