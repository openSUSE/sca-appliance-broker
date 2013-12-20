<?PHP
	if(isset($_SERVER['HTTP_USER_AGENT']))
	{
		include 'checklogin.php';
	}
?>

<?PHP
	echo "\n<!-- Database: Connecting = $Database on $DBHost as $User -->\n";
	$DBConn = mysql_connect($DBHost,$User,$Password) or die("<FONT SIZE=\"-1\"><B>ERROR</B>: Unable to connect to database: <b>$Database</b></FONT><BR>");
	@mysql_select_db($Database) or die("<FONT SIZE=\"-1\"><B>ERROR</B>: Unable to select database: <b>$Database</b></FONT><BR>");
	echo "<!-- Database: Status          = Open -->\n";
?>
