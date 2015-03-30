<?php
session_start();

if(!empty($_POST['chave'])) {
        $prefix=preg_replace("/[^A-Za-z0-9 ]/", '',$_POST['chave']);
        if ($prefix == "KEY") {
                print "<center><br><br>ENTRE COM A CHAVE";
		print "<br><a href=\"index.php\">VOLTAR</a></center>";
                die;
        }else{
                if (empty($_SESSION['id'])){
                        $_SESSION['id'] = $prefix;
			header("Location: parse.php");

                }
        }
}
?>

