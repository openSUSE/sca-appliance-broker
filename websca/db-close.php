<?PHP
	if(isset($_SERVER['HTTP_USER_AGENT']))
	{
		include 'checklogin.php';
	}
?>

<?PHP
	mysql_close($DBConn) or die("<FONT SIZE=\"-1\"><B>ERROR</B>: Unable to close database: $Database</FONT><BR>");
	echo "<!-- Database: Status          = Closed -->\n\n";
?>
