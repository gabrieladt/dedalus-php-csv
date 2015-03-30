<?php
session_start();

if(!empty($_POST['chave'])) {
	$prefix=preg_replace("/[^A-Za-z0-9 ]/", '',$_POST['chave']);
	if ($prefix == "KEY") {
		print "<br><br>Entre com uma chave";
		die;
	}else{
		if (empty($_SESSION['id'])){
			$_SESSION['id'] = $prefix;
		}
	}
}

$db = new SQLite3('./db/mydb');
$db1 = new SQLite3('./db/mydb');

//$create_table_sql = "DROP table controle_tabelas";
//$create_table_sql = "CREATE TABLE IF NOT EXISTS controle_tabelas (id TEXT primary key,tabela TEXT, date TEXT)";
//$create_table_sql = "CREATE TABLE IF NOT EXISTS controle_tabelas (tabela TEXT primary key, created date)";
//$db->exec($create_table_sql);



//$results = $db->query("SELECT name FROM sqlite_master WHERE type='table';");
//while ($row = $results->fetchArray()) {
//	print $row['name']."<br>";
//	print_r ($row)."<b><br>";
//}
//print "--------------<br>";

/* NAO PRECISA REMOVER MAIS
$old= date("Y-m-d", time() - (86400*30));

$results = $db->query("SELECT * FROM controle_tabelas where created <= '$old'");
//$results = $db->query("SELECT * FROM controle_tabelas");
$row="";
$tabela="";
$i=0;
while ($row = $results->fetchArray()) {
//	print $row['tabela']."  - ".$row['created']." <br>";
	$tabela[$i]= $row['tabela'];
	$i++;
}
foreach ($tabela as $value) {
	$db->exec("DROP TABLE IF EXISTS $value");
	$db->exec("delete from controle_tabelas where tabela='$value'");
	unlink("./uploads/$value");
}
//$db->exec("DROP TABLE IF EXISTS $tabela");
*/
//$table="baseaws.csv";
//$date = new DateTime('2015-02-01');
//$dia = $date->format('Y-m-d');
//print "-- $prefix $dia <br>";

//$db->exec("INSERT OR IGNORE INTO controle_tabelas (id,tabela,created) values (\"".$prefix."\",\"".$table.$prefix."\",\"".$dia."\")");



//session_destroy();


function import_csv ($csv_path) {

//Open the database mydb
$db = new SQLite3('./db/mydb');



if (($csv_handle = fopen($csv_path, "r")) === FALSE)
	throw new Exception('Cannot open CSV file');
	if(!$delimiter)
		$delimiter = ',';
		
	if(!$table)
		$table = basename($csv_path);
	
	if(!$fields){
		$fields = array_map(function ($field){
			return strtolower(preg_replace("/[^A-Z0-9]/i", '', $field));
		}, fgetcsv($csv_handle, 0, $delimiter));
	}
	
	$create_fields_str = join(', ', array_map(function ($field){
		return "$field TEXT NULL";
	}, $fields));

//	echo "XXXXXXXXXxx $table -- $create_fields_str ";
	
	$db->exec("DROP TABLE IF EXISTS $table");

	$create_table_sql = "CREATE TABLE IF NOT EXISTS $table (id integer primary key autoincrement, $create_fields_str, removed INTEGER DEFAULT 0)";
	$db->exec($create_table_sql);
	
	$date = new DateTime();
	$dia = $date->format('Y-m-d');

	$db->exec("INSERT OR IGNORE INTO controle_tabelas (tabela,created) values (\"".$table."\",\"".$dia."\")");

	$insert_fields_str = join(', ', $fields);
	$insert_values_str = join(', ', array_fill(0, count($fields),  '?'));
	//$insert_sql = "INSERT INTO $table ($insert_fields_str) VALUES ($insert_values_str)";
	//$insert_sth = $db->exec($insert_sql);
	
	$final="";
	while (($data = fgetcsv($csv_handle, 0, $delimiter)) !== FALSE) {
		$num = count ($data);
		$tmp="";
		for ($c=0; $c < $num; $c++) {
			$tmp.="\"".$data[$c]."\",";
		}
		$final=rtrim($tmp,",");
		$insert_sql = "INSERT INTO $table ($insert_fields_str) VALUES ($final)";
		//echo $insert_sql."<br>";
		$insert_sth = $db->exec($insert_sql);
	}
		
	
	fclose($csv_handle);
	//b->exec("update $table set totalcost = replace (totalcost,\",\",\".\")");
	//$db->exec("update $table set total=totalcost");

/*
$results = $db->query("SELECT * FROM $table");
while ($row = $results->fetchArray()) {
        var_dump($row);
	echo "$row <br> *";

}
*/
}
//import_csv ("./uploads/baseaws.csv");
if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
{
	############ Edit settings ##############
	$UploadDirectory	= './uploads/'; //specify upload directory ends with / (slash)
	##########################################
	
	/*
	Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini". 
	Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit 
	and set them adequately, also check "post_max_size".
	*/
	
	//check if this is an ajax request
	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
		die();
	}
	
	
	//Is file size is less than allowed size.
	if ($_FILES["FileInput"]["size"] > 20000000) {
		die("File size is too big!");
	}
	
	//allowed file type Server side check
	switch(strtolower($_FILES['FileInput']['type']))
		{
			//allowed file types
		case 'text/csv': 
			break;
		default:
		die('Unsupported File!'); //output error
	}
        $File_Name          = strtolower($_FILES['FileInput']['name']);
        $File_Name_controle          = strtolower($_FILES['FileInput_controle']['name']);
        $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
        $File_Ext_controle           = substr($File_Name_controle, strrpos($File_Name_controle, '.')); //get file extention
        $Random_Number      = rand(0, 9999999999); //Random number to be added to name.
        $NewFileName            = $Random_Number.$File_Ext; //new file name
        $NewFileName_controle           = $Random_Number.$File_Ext; //new file name
	
	$error_base=1;
	$error_cont=1;
	//(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
	if(move_uploaded_file($_FILES['FileInput']['tmp_name'],$UploadDirectory."baseaws".$prefix."csv" ))
	   {
		echo ("Success! File Uploaded: $File_Name <br>");
		import_csv ($UploadDirectory."baseaws".$prefix."csv");
		$error_base=1;
	}else{
		die("error uploading Files!");
	}
	if(move_uploaded_file($_FILES['FileInput_controle']['tmp_name'], $UploadDirectory."controle".$prefix."csv" ))
	   {
		echo("Success! File Uploaded: $File_Name_controle <br>");
		echo("MES: $prefix");
		import_csv ($UploadDirectory."controle".$prefix."csv");
		$error_cont=1;
	}else{
		die("error uploading Files!");
	}
	
	if (($error_base=1) and  ($error_cont=1)){
		echo "<br><br><a href=\"./parse.php\">SUCESSO AO IMPORTAR ARQUIVOS - SEGUIR</a>";
	}
		
}
else
{
	die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
}
