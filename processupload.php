<?php

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
	
	$error=0;
	//(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
	if(move_uploaded_file($_FILES['FileInput']['tmp_name'],$UploadDirectory."baseaws.csv" ))
	   {
		echo ("Success! File Uploaded: $File_Name <br>");
		$error=1;
	}else{
		die("error uploading Files!");
	}
	if(move_uploaded_file($_FILES['FileInput_controle']['tmp_name'], $UploadDirectory."controle.csv" ))
	   {
		echo("Success! File Uploaded: $File_Name_controle");
		$error=1;
	}else{
		die("error uploading Files!");
	}
	
}
else
{
	die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
}
