<?php
session_start();

if(!empty($_POST['mes'])) {
        $prefix=$_POST['mes'] ;
        if ($prefix == "SELECIONE") {
                print "<center><br><br>SELECIONE O MES";
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

