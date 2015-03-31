<?php
session_start();

if(!isset($_SESSION['id'])){ //if login in session is not set
    header("Location: index.php");
}
if (!empty($_SESSION['id'])){
        $prefix=$_SESSION['id'] ;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<body>
<?php

function stringForJavascript($in_string) {
   $str = ereg_replace("[\r\n]", " \\n\\\n", $in_string);
   $str = ereg_replace('"', '\\"', $str);
   return $str;
}

function read_table ($table) {

	$db = new SQLite3('./db/mydb');
	

//	$tablesquery = $db->query("SELECT name FROM sqlite_master WHERE type='table';");

//	while ($table = $tablesquery->fetchArray(SQLITE3_ASSOC)) {
//		echo $table['name'] . '<br />';
//	}
	print "<font size=1.5>";
	print "<table border=1>";
	print "<tr><td>id</td><td>Invoiceid</td><td>Linkedaccountid</td><td>Start Date</td><td>End Date</td><td>ProdCode</td><td>ItemDescription</td><td>Total</td></tr>";

	$results = $db->query("SELECT * FROM $table");
	while ($row = $results->fetchArray()) {
		print "<tr><td>".$row['id']."</td>";
		print "<td>".$row['invoiceid']."</td>";
		print "<td>".$row['linkedaccountid']."</td>";
		print "<td>".$row['billingperiodstartdate']."</td>";
		print "<td>".$row['billingperiodenddate']."</td>";
		print "<td>".$row['productcode']."</td>";
		print "<td>".$row['itemdescription']."</td>";
		print "<td>".$row['totalcost']."</td></tr>";

	}
	print "</table>";
	print "</font>";


}

function read_table_controle ($table) {

	$db = new SQLite3('./db/mydb');
//	$tablesquery = $db->query("SELECT name FROM sqlite_master WHERE type='table';");

//	while ($table = $tablesquery->fetchArray(SQLITE3_ASSOC)) {
//		echo $table['name'] . '<br />';
//	}
	print "<font size=2>";
	print "<table border=1.5>";
	print "<tr><td>id</td><td>contrato</td><td>Grupo</td><td>Linkedaccountid</td><td>Remover</td><td>RegExp<td>fator</td><td>Email</td></tr>";

	$results = $db->query("SELECT * FROM $table");
	while ($row = $results->fetchArray()) {
		print "<tr><td>".$row['id']."</td>";
		print "<td>".$row['contrato']."</td>";
		print "<td>".$row['grupo']."</td>";
		print "<td>".$row['linkedaccountid']."</td>";
		print "<td>".$row['remover']."</td>";
		print "<td>".$row['removeregexp']."</td>";
		print "<td>".$row['fator']."</td>";
		print "<td>".$row['email']."</td></tr>";

	}
	print "</table>";
	print "</font>";


}


function merge_table ($base,$controle) {
        $db = new SQLite3('./db/mydb');
	$db->loadExtension("pcre.so");
	print "Index:<br><br>";
	print "<a name=\"Index\">";
	
        $results = $db->query("SELECT * FROM $controle");
	$row="";
        while ($row = $results->fetchArray()) {
		print "<a href=\"#".$row['grupo']."\">".$row['grupo']."</a><br>";
//                print "<a name=\"".$row['grupo']."\">";
	}
	print "</a>";

	print "<form action=\"update.php\" method=\"post\">";

        $results = $db->query("SELECT * FROM $controle");
	$row="";
        while ($row = $results->fetchArray()) {
		print "<a name=\"".$row['grupo']."\">";

                $ids = explode(";",$row['linkedaccountid']);
                $remover = explode(";",$row['remover']);
                $remover_regexp = $row['removeregexp'];
                $tmp="";
                $tmp2="";
                for ($i=0; $i<sizeof($ids); $i++ ){
                        $tmp.="\"".$ids[$i]."\",";

                }
                $final_controle=rtrim($tmp,",");

                for ($j=0; $j<sizeof($remover); $j++ ){
                        $tmp2.="\"".$remover[$j]."\",";

                }
                $final_remover=rtrim($tmp2,",");

		$update_base1 = $db->query("update $base set removed=1 where linkedaccountid in ($final_controle) and productcode in ($final_remover)");		
		$update_base2 = $db->query("update $base set removed=1 where linkedaccountid in ($final_controle) and itemdescription REGEXP '$remover_regexp'");		
		$results_base2 = $db->query("SELECT * FROM $base where linkedaccountid in ($final_controle) and removed=0");
		$j=0;
		$total=0;
		$row2="";
		$valor="";
		while ($row2 = $results_base2->fetchArray()) {
			$valor=str_replace(',', '.', str_replace('.', '', $row2['totalcost']));
			$total = $total + $valor;
			//print $row2['totalcost']."<br>";
		
		}
		$fator=str_replace(',', '.', $row["fator"]);
		$fator = $total * $fator;
		print "<font size=2>";
                print "<br>Contrato: ".$row['contrato']."<br>";
		print "Cliente: ".$row['grupo']." - ".$row['email']."</br>";
		print "Fator: ".$row['fator']."</br>";
		//print "Total Fatura: R$ ".$total." <br>";
		print "Total Fatura: $ ".number_format($total, 2, ',', '')." <br>";
		print "Total Fatura * Fator: $ ".number_format($fator, 2, ',', '')." <br>";
		print "</font>";
		
		print "<font size=1.5>";
        	print "<table border=1>";
		print "<tr><td>id</td><td>Invoiceid</td><td>Linkedaccountid</td><td>Start Date</td><td>End Date</td><td>ProdCode</td><td>ItemDescription</td><td>Total</td><td>REMOVE</td></tr>";
		//print $final_remover."<br>";
		
		//$results_base = $db->query("SELECT * FROM $base where linkedaccountid in ($final_controle) order by linkedaccountid, totalcost");		
		$results_base = $db->query("SELECT * FROM $base where linkedaccountid in ($final_controle) order by removed DESC");		
		//$results_base = $db->query("SELECT * FROM $base where linkedaccountid in ($final_controle) and removed=0");		
		$row="";
		while ($row = $results_base->fetchArray()) {
			$id = $row['id'];	
			$remover = $row['removed'];	
			if ( $remover == '1' ) {  
				$bg="#FF6347";
				$status="R.Controle";
			}elseif ( $remover == '2' ) {
				$bg="#F0E68C";
                                $status="Rm.Manual(<a href=\"./restore.php?id=$id\">Restore</a>)";

			}else {
				$bg="#FFFFFF";
				$status="OK";
				$status="<input type=\"checkbox\" name=\"check_list[]\" value=\"$id\">";
			}
			print "<tr bgcolor=\"$bg\"><td>".$row['id']."</td>";
	                print "<td>".$row['invoiceid']."</td>";
        	        print "<td>".$row['linkedaccountid']."</td>";
			print "<td>".$row['billingperiodstartdate']."</td>";
			print "<td>".$row['billingperiodenddate']."</td>";
			print "<td>".$row['productcode']."</td>";
			print "<td>".$row['itemdescription']."</td>";
			print "<td>".$row['totalcost']."</td>";
			print "<td><center>".$status."<center></td></tr>";

                }
		//echo "SELECT * FROM $base where linkedaccountid in ($final_controle)";
		print "</table>";
		print "<br>";
		print "</font>";
		print "<center><input type=\"submit\" name=\"REMOVER\"/></center>";

		print "</a>";
		print "<br><center><a href=\"#Index\">Index</a><br></center>";
        }
	print "</form>";

}

function send_email ($base,$controle) {
        $db = new SQLite3('./db/mydb');
	
        $results = $db->query("SELECT * FROM $controle");
	$row="";
	print "<form action=\"sendemail.php\" method=\"post\">";
	print "<font size=2>";
	print "<table border=1>";
	print "<tr><td>id</td><td>Grupo</td><td>Contrato</td><td>Email</td><td>Enviar Email</td></tr>";

        while ($row = $results->fetchArray()) {
		$id=$row['id'];
		$status="<input type=\"checkbox\" name=\"check_list[]\" value=\"$id\">";

		print "<tr><td>".$row['id']."</td>";
		print "<td>".$row['grupo']."</td>";
		print "<td>".$row['contrato']."</td>";
		print "<td>".$row['email']."</td>";
		print "<td><center>".$status."<center></td></tr>";


	}
	print "</table>";
	print "<br>";
	print "</font>";
	print "<input type=\"hidden\" name=\"base\" value=\"".$base."\">";
	print "<input type=\"hidden\" name=\"controle\" value=\"".$controle."\">";
	print "<center><input type=\"submit\" name=\"ENVIAR\"/></center>";
	print "</form>";

}

switch($_GET['id']) {
	case 'cat1':
		//$content = 'This is content for page Politics.';
		$content = read_table("baseaws".$prefix."csv");
		break;
	case 'cat2':
		$content = read_table_controle("controle".$prefix."csv");
		break;
	case 'cat3':
		$content = merge_table("baseaws".$prefix."csv","controle".$prefix."csv");
		break;
	case 'cat4':
		$content = send_email("baseaws".$prefix."csv","controle".$prefix."csv");
		break;
	default:
		$content = 'There was an error.';

} 
print stringForJavascript($content);
usleep(600000);

//merge_files("controle.csv","baseaws.csv");


?>

</body>

</html>
