<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
		echo "<p> $num campos na linha $row: <br /></p>\n";
		$row++;
		for ($c=0; $c < $num; $c++) {
			echo $data[$c] . "<br />\n";
		}
	}
	fclose ($handle);
}

switch($_GET['id']) {
	case 'cat1':
		//$content = 'This is content for page Politics.';
		$content = read_csv(baseaws.csv);
		break;
	case 'cat2':
		$content = 'This is content for page Sports.';
		break;
	case 'cat3':
		$content = 'This is content for page Lifestyle.';
		break;
	default:
		$content = 'There was an error.';

} 
print stringForJavascript($content);
usleep(600000);
?>

</body>

</html>
