<?php
session_start();

if(!isset($_SESSION['id'])){ //if login in session is not set
    header("Location: index.php");
}
if (!empty($_SESSION['id'])){
        $prefix=$_SESSION['id'] ;
}


if(!empty($_POST['check_list'])) {
	$check_list=$_POST['check_list'];
	print_r($check_list);

	$merge_tmp = fopen("./uploads/result-".$prefix.".csv", "w") or die("Unable to open file!");

        $handle_controle = fopen ("./uploads/".$file1,"r");
-        while (($data = fgetcsv($handle_controle, 1000, ",")) !== FALSE) {
-               $num = count ($data);
-		$linked_ids=(explode("|", $data[1]));
-		//foreach ($linked_ids as $linked_values ){
-		for ($i=0; $i<sizeof($linked_ids)-1; $i++ ){
-			print "$linked_ids[$i]  CAT<br> ";
-			print "rodou <br>";
-	
-			rewind($handle_base);
-			fseek($handle_base,0);
-			//while(! feof($handle_base)){
-        		$handle_base = fopen ("./uploads/".$file2,"r");
-        		while (($data_base = fgetcsv($handle_base, 1000, ",")) !== FALSE) {
-				//$var=explode (",",fgets($handle_base));
-				if(!empty($data_base[2])){
-					echo " $linked_ids[$i] ==  $data_base[2]<br>";
-					$reg="/$data[2]/";
-					if (($linked_ids[$i] == $data_base[2]) AND (!preg_match("$reg",$data_base[12]))) {
-						print $data_base[12]."--".$data_base[2]." igual <br>";
-                                                $num = count ($data_base);
-                                                echo "aaaa $num <br>";
-                                                for ($c=0; $c < $num; $c++) {
-                                                        echo $data_base[$c].",";
-							fwrite ($merge_tmp, $data_base[$c].",");
-                                                }
-						fwrite ($merge_tmp,"\n");
-
-					}
-				}
-			}
-			fclose($handle_base);
-	




	print "<center><br><br><a href=\"./parse.php\"><h2>EMAIL ENVIADO(S) - VOLTAR</h2></a></center>";



}
?>
