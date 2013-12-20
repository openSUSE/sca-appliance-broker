<?PHP include 'checklogin.php';?>

<HTML>
<HEAD>
<!-- Modified: Date       = 2013 Dec 12 -->
<?PHP
	include 'db-config.php';

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

	echo "<!-- Variable: AgentID       = $AgentID -->\n";
	echo "<!-- Variable: ArchiveType   = $ArchiveType -->\n";

	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"$StatusRefresh;URL=detailarch.php?atp=$ArchiveType\">\n";
	echo "<META HTTP-EQUIV=\"Content-Style-Type\" CONTENT=\"text/css\">\n";
	echo "<LINK REL=\"stylesheet\" HREF=\"style.css\">\n";
	echo "<TITLE>Delete SCA Report</TITLE>\n";
	echo "</HEAD>\n";
	echo "<BODY BGPROPERTIES=FIXED BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\">\n";
	echo "<H1 ALIGN=\"center\">Supportconfig Analysis Appliance<br>Delete Report</H1>\n";

	include 'db-open.php';

	$query="DELETE FROM Results WHERE ResultsArchiveID=$ArchiveID";
	echo "<!-- Query: Submitted     = $query -->\n";
	$result=mysql_query($query);
	if ( $result ) {
		echo "<!-- Query: Result        = Success -->\n";
		$query="DELETE FROM Archives WHERE ArchiveID=$ArchiveID";
		echo "<!-- Query: Submitted     = $query -->\n";
		$result=mysql_query($query);
		if ( $result ) {
			echo "<!-- Query: Result        = Success -->\n";
			echo "<H2 ALIGN=\"center\">Deleted ArchiveID $ArchiveID Report</H2>\n";
		} else {
			echo "<!-- Query: Result        = FAILURE -->\n";
			echo "<H2 ALIGN=\"center\">ERROR: Deleting ArchiveID $ArchiveID Report<br>Delete Manually</H2>\n";
		}
	} else {
		echo "<!-- Query: Result        = FAILURE -->\n";
		echo "<H2 ALIGN=\"center\">ERROR: Deleting ArchiveID $ArchiveID Report<br>Delete Manually</H2>\n";
	}
	include 'db-close.php';
	echo "</BODY>\n";
	echo "</HTML>\n";
?>

