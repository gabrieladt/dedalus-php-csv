<?php
if(!empty($_POST['check_list'])) {
	foreach($_POST['check_list'] as $check) {
		$db = new SQLite3('./db/mydb');
		$update_base = $db->query("update baseawscsv set removed=2 where id = $check"); 
	}
	print "<center><br><br><a href=\"./parse.php\"><h2>UPDATE EFETUADO - VOLTAR</h2></a></center>";
}
?>
