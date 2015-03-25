<?php
function import_csv ($csv_path) {
/*
 * PHP SQLite - Create a table and insert rows in SQLite
 */

//Open the database mydb
$db = new SQLite3('./db/mydb');

//drop the table if already exists
//b->exec('DROP TABLE IF EXISTS people');

//Create a basic table
//$db->exec('CREATE TABLE people (full_name varchar(255), job_title varchar (255))');

//insert rows
//$db->exec('INSERT INTO people (full_name, job_title) VALUES ("John Doe","manager")');
//$db->exec('INSERT INTO people (full_name, job_title) VALUES ("Jane Cyrus","assistant")');
if (($csv_handle = fopen($csv_path, "r")) === FALSE)
	throw new Exception('Cannot open CSV file');
		
	if(!$delimiter)
		$delimiter = ',';
		
	if(!$table)
		$table = preg_replace("/[^A-Z0-9]/i", '', basename($csv_path));
	
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

	$create_table_sql = "CREATE TABLE IF NOT EXISTS $table ($create_fields_str)";
	$db->exec($create_table_sql);

	$insert_fields_str = join(', ', $fields);
	$insert_values_str = join(', ', array_fill(0, count($fields),  '?'));
	//$insert_sql = "INSERT INTO $table ($insert_fields_str) VALUES ($insert_values_str)";
	//$insert_sth = $db->exec($insert_sql);
	
	$final="";
	while (($data = fgetcsv($csv_handle, 0, $delimiter)) !== FALSE) {
		$num = count ($data);
		for ($c=0; $c < $num; $c++) {
			$tmp.="\"".$data[$c]."\",";
		}
		$final=rtrim($tmp,",");
		$insert_sql = "INSERT INTO $table ($insert_fields_str) VALUES ($final)";
		//echo $insert_sql."<br>";
		$insert_sth = $db->exec($insert_sql);
	}
		
	
	fclose($csv_handle);

//$results = $db->query("SELECT * FROM $table");
//while ($row = $results->fetchArray()) {
//        var_dump($row);
//	echo "$row <br> *";

//}
}

import_csv ("./uploads/controle.csv");
import_csv ("./uploads/baseaws.csv");
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
	$NewFileName 		= $Random_Number.$File_Ext; //new file name
	$NewFileName_controle 		= $Random_Number.$File_Ext; //new file name
	
	$error_base=1;
	$error_cont=1;
	//(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
	if(move_uploaded_file($_FILES['FileInput']['tmp_name'],$UploadDirectory."baseaws.csv" ))
	   {
		echo ("Success! File Uploaded: $File_Name <br>");
		import_csv ($UploadDirectory."baseaws.csv");
		$error_base=1;
	}else{
		die("error uploading Files!");
	}
	if(move_uploaded_file($_FILES['FileInput_controle']['tmp_name'], $UploadDirectory."controle.csv" ))
	   {
		echo("Success! File Uploaded: $File_Name_controle");
		import_csv ($UploadDirectory."controle.csv");
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
