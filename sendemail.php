<?php
session_start();

if(!isset($_SESSION['id'])){ //if login in session is not set
    header("Location: index.php");
}
if (!empty($_SESSION['id'])){
        $prefix=$_SESSION['id'] ;
}

function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    if (mail($mailto, $subject, "", $header)) {
        echo "$mailto: mail send ... OK <br>"; // or use booleans here
    } else {
        echo "$mailto: mail send ... ERROR! <br>";
    }
}
$my_file = "result-".$prefix.".csv";
$my_path = "./uploads/";
$my_name = "Financeiro";
$my_mail = "financeiro@dedalusprime.com.br";
$my_replyto = "financeiro@dedalusprime.com.br";
$my_subject = "This is a mail with attachment.";
$my_message = "Test de Email.\r\n\r\n";
//mail_attachment($my_file, $my_path, "gabrieladt@gmail.com", $my_mail, $my_name, $my_replyto, $my_subject, $my_message);


if(!empty($_POST['check_list'])) {
	$check_list=$_POST['check_list'];
	$base=$_POST['base'];
	$controle=$_POST['controle'];
	
	$db = new SQLite3('./db/mydb');
/*
	$query  = $db->query("SELECT sql FROM sqlite_master WHERE tbl_name = '$base' AND type = 'table'");
	while ($row2 = $query->fetchArray()) {
		print $row2['sql'];
	}

*/
	
	for ($i=0; $i<sizeof($check_list); $i++ ){
		$id= $check_list[$i];
		$query  = $db->query("select linkedaccountid,email from $controle where id = $id");
		while ($row2 = $query->fetchArray()) {
			$ids = explode(";",$row2['linkedaccountid']);
			$email=$row2['email'];
                }
                
		$tmp="";
		for ($j=0; $j<sizeof($ids); $j++ ){
			$tmp.="\"".$ids[$j]."\",";
                }

		$final_controle=rtrim($tmp,",");
		
		$results_base2 = $db->query("SELECT invoiceid , payeraccountid , linkedaccountid , recordtype , recordid , billingperiodstartdate , billingperiodenddate , invoicedate , payeraccountname , linkedaccountname , taxationaddress , payerponumber , productcode , productname , sellerofrecord , usagetype , operation , rateid , itemdescription , usagestartdate , usageenddate , usagequantity , blendedrate , currencycode , costbeforetax , credits , taxamount , taxtype , totalcost FROM $base where linkedaccountid in ($final_controle) and removed=0");
                $row3="";

		$merge_tmp = fopen("./uploads/result-".$prefix.".csv", "w") or die("Unable to open file!");
		
		$handle_controle = fopen ("./uploads/".$base,"r");
		$header = fgets($handle_controle); // read until first newline
  		fclose($handle_controle);
		
		//print "invoiceid , payeraccountid , linkedaccountid , recordtype , recordid , billingperiodstartdate , billingperiodenddate , invoicedate , payeraccountname , linkedaccountname , taxationaddress , payerponumber , productcode , productname , sellerofrecord , usagetype , operation , rateid , itemdescription , usagestartdate , usageenddate , usagequantity , blendedrate , currencycode , costbeforetax , credits , taxamount , taxtype , totalcost<br>";
		fwrite ($merge_tmp,"$header");

		//print $header."<br>";
		while ($row = $results_base2->fetchArray()) {
			$collun= "\"$row[invoiceid]\",\"$row[payeraccountid]\",\"$row[linkedaccountid]\",\"$row[recordtype]\",\"$row[recordid]\",\"$row[billingperiodstartdate]\",\"$row[billingperiodenddate]\",\"$row[invoicedate]\",\"$row[payeraccountname]\",\"$row[linkedaccountname]\",\"$row[taxationaddress]\",\"$row[payerponumber]\",\"$row[productcode]\",\"$row[productname]\",\"$row[sellerofrecord]\",\"$row[usagetype]\",\"$row[operation]\",\"$row[rateid]\",\"$row[itemdescription]\",\"$row[usagestartdate]\",\"$row[usageenddate]\",\"$row[usagequantity]\",\"$row[blendedrate]\",\"$row[currencycode]\",\"$row[costbeforetax]\",\"$row[credits]\",\"$row[taxamount]\",\"$row[taxtype]\",\"$row[totalcost]\"";
			fwrite ($merge_tmp,"$collun\n");

		//	print "\"$row[invoiceid]\",\"$row[payeraccountid]\",\"$row[linkedaccountid]\",\"$row[recordtype]\",\"$row[recordid]\",\"$row[billingperiodstartdate]\",\"$row[billingperiodenddate]\",\"$row[invoicedate]\",\"$row[payeraccountname]\",\"$row[linkedaccountname]\",\"$row[taxationaddress]\",\"$row[payerponumber]\",\"$row[productcode]\",\"$row[productname]\",\"$row[sellerofrecord]\",\"$row[usagetype]\",\"$row[operation]\",\"$row[rateid]\",\"$row[itemdescription]\",\"$row[usagestartdate]\",\"$row[usageenddate]\",\"$row[usagequantity]\",\"$row[blendedrate]\",\"$row[currencycode]\",\"$row[costbeforetax]\",\"$row[credits]\",\"$row[taxamount]\",\"$row[taxtype]\",\"$row[totalcost]\"<br>";
		/*
			foreach ( $row3  as $value ) {
				$linha.="\"".$value."\",";

                	}
                	$linha_final=rtrim($linha,",");

			print $linha_final."<br>";*/

                }
  		fclose($merge_tmp);
		mail_attachment($my_file, $my_path, "$email", $my_mail, $my_name, $my_replyto, $my_subject, $my_message);

	}
	

	print "<center><br><br><a href=\"./parse.php\"><h2>VOLTAR</h2></a></center>";



}
?>
