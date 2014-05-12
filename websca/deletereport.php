<?PHP include 'checklogin.php';?>
<?PHP //echo "<!-- Modified: Date       = 2014 May 12 -->\n"; ?>
<HTML>
<HEAD>
<?PHP
	include 'sca-config.php';

	$DefaultArchiveType = 'a';
	$ArchiveType = $_GET['atp'];
	$ArchiveID = $_GET['aid'];

	if ( isset($ArchiveID) ) {
		if ( ! is_numeric($ArchiveID) ) {
			die("<FONT SIZE=\"-1\"><B>ERROR</B>: Invalid ArchiveID, Only numeric values allowed.</FONT><BR>");			
		}
	} else { 
		die("<FONT SIZE=\"-1\"><B>ERROR</B>: Invalid ArchiveID, Only numeric values allowed.</FONT><BR>");			
	}
	switch ($archiveType) {
	case 'p':
	case 'a':
	case 'd':
	case 'e':
	case 't':
		break;
	default:
		$archiveType = $DefaultArchiveType;
		break;
	}

	//echo "<!-- Variable: AgentID       = $AgentID -->\n";
	//echo "<!-- Variable: ArchiveType   = $ArchiveType -->\n";

	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"$StatusRefresh;URL=detailarch.php?atp=$ArchiveType\">\n";
	echo "<META HTTP-EQUIV=\"Content-Style-Type\" CONTENT=\"text/css\">\n";
	echo "<LINK REL=\"stylesheet\" HREF=\"style.css\">\n";
	echo "<TITLE>Delete SCA Report</TITLE>\n";
	echo "</HEAD>\n";
	echo "<BODY BGPROPERTIES=FIXED BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\">\n";
	echo "<H1 ALIGN=\"center\">Supportconfig Analysis Appliance<br>Delete Report</H1>\n";

	$Connection = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
	if ($Connection->connect_errno) {
		echo "<P CLASS=\"head_1\" ALIGN=\"center\">SCA Database Delete Report</P>\n";
		echo "<H2 ALIGN=\"center\">Connect to Database: <FONT COLOR=\"red\">FAILED</FONT></H2>\n";
		echo "<P ALIGN=\"center\">Make sure the MariaDB database is configured properly.</P>\n";
		echo "</BODY>\n</HTML>\n";
		die();
	}

	$query = "DELETE FROM Results WHERE ResultsArchiveID=$ArchiveID";
	//echo "<!-- Query: Submitted     = $query -->\n";
	$result = $Connection->query($query);
	if ( $result ) {
//		$result->close();
		//echo "<!-- Query: Result        = Success -->\n";
		$query = "DELETE FROM Archives WHERE ArchiveID=$ArchiveID";
		//echo "<!-- Query: Submitted     = $query -->\n";
		$result = $Connection->query($query);
		if ( $result ) {
			//echo "<!-- Query: Result        = Success -->\n";
			echo "<H2 ALIGN=\"center\">Deleted ArchiveID $ArchiveID Report</H2>\n";
		} else {
			//echo "<!-- Query: Result        = FAILURE -->\n";
			echo "<H2 ALIGN=\"center\">ERROR: Deleting ArchiveID $ArchiveID Report<br>Delete Manually</H2>\n";
		}
	} else {
		//echo "<!-- Query: Result        = FAILURE -->\n";
		echo "<H2 ALIGN=\"center\">ERROR: Deleting ArchiveID $ArchiveID Report<br>Delete Manually</H2>\n";
	}
	$result->close();
	$Connection->close();
	echo "</BODY>\n";
	echo "</HTML>\n";
?>

