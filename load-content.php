<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<title>Ajax Workshop 2: Building Tabbed Content</title>
<body>
<?php
function stringForJavascript($in_string) {
   $str = ereg_replace("[\r\n]", " \\n\\\n", $in_string);
   $str = ereg_replace('"', '\\"', $str);
   return $str;
}
function read_csv ($file) {
	$row = 1;
	$handle = fopen ("./uploads/".$file,"r");
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count ($data);
		$row++;
		for ($c=0; $c < $num; $c++) {
			echo $data[$c].",\n";
		}
		echo "<br><hr>";
	}	
	fclose ($handle);
}

function merge_files ($file1,$file2) {
	$merge_tmp = fopen("./uploads/merge_tmp.csv", "w") or die("Unable to open file!");

        $handle_controle = fopen ("./uploads/".$file1,"r");
        $handle_base = fopen ("./uploads/".$file2,"r");
        while (($data = fgetcsv($handle_controle, 1000, ",")) !== FALSE) {
                $num = count ($data);
		//$data_base = fgetcsv($handle_base, 1000, ",");
		//echo "$data_base[0]\n $num";
		//print array_search('908611282160', $data);
		$linked_ids=(explode("|", $data[1]));
		foreach ($linked_ids as $linked_values ){
			print "$linked_values";
		}

		//print $data[1];
		
                for ($c=0; $c < $num; $c++) {
				
			fwrite ($merge_tmp, $data[$c].",");
//                        echo $data[$c].",\n";
                }
		fwrite ($merge_tmp,"\n");
		
        }
        fclose ($handle_controle);
	fclose($merge_tmp);
	exec("sed -i 's/,$//g' ./uploads/merge_tmp.csv");

}
switch($_GET['id']) {
	case 'cat1':
		//$content = 'This is content for page Politics.';
		$content = read_csv("baseaws.csv");
		break;
	case 'cat2':
		$content = read_csv("controle.csv");
		break;
	case 'cat3':
		$content = 'This is content for page Lifestyle.';
		break;
	default:
		$content = 'There was an error.';

} 
//print stringForJavascript($content);
//usleep(600000);

merge_files("controle.csv","baseaws.csv");
?>

</body>

</html>
