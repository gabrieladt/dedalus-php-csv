<?php
session_start();

if(!isset($_SESSION['id'])){ //if login in session is not set
    header("Location: index.php");
}
if (!empty($_SESSION['id'])){
        $prefix=$_SESSION['id'] ;
}

$base="baseaws".$prefix."csv";

if(!empty($_GET['id'])) {
	$db = new SQLite3('./db/mydb');
	$id=$_GET['id'];
	$update_base = $db->query("update $base set removed=0 where id = $id"); 
 	
	}
	print "<center><br><br><a href=\"./parse.php\"><h2>RESTORE EFETUADO - VOLTAR</h2></a></center>";
?>
