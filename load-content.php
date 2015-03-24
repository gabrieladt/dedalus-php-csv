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
switch($_GET['id']) {
	case 'newsCat1':
		$content = 'This is content for page Politics.';
		break;
	case 'newsCat2':
		$content = 'This is content for page Sports.';
		break;
	case 'newsCat3':
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
