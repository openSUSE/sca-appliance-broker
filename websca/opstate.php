<?PHP include 'checklogin.php';?>
<?PHP //echo "<!-- Modified: Date       = 2014 May 08 -->\n"; ?>
<HTML>
<HEAD>
<TITLE>SCA Operations</TITLE>
<?PHP
	include 'sca-config.php';
	//echo "<!-- Query: Refresh Rate  = $StatsRefresh seconds -->\n";
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"$StatsRefresh;URL=opstate.php\">\n";
?>
<META HTTP-EQUIV="Content-Style-Type" CONTENT="text/css">
<LINK REL="stylesheet" HREF="style.css">
</HEAD>

<?PHP
	$Connection = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
	if ($Connection->connect_errno()) {
		echo "<P CLASS=\"head_1\" ALIGN=\"center\">SCA Database Operations</P>\n";
		echo "<H2 ALIGN=\"center\">Connect to Database: <FONT COLOR=\"red\">FAILED</FONT></H2>\n";
		echo "<P ALIGN=\"center\">Make sure the MariaDB database is configured properly.</P>\n";
		echo "</BODY>\n</HTML>\n";
		die();
	}
	$query = "SELECT ResultID FROM Results";
	$result = $Connection->query($query);
	$CountResults = $result->num_rows;
	//echo "<!-- Query: Submitted          = $query -->\n";
	if ( $result ) {
		//echo "<!-- Query: Result             = Success -->\n";
		//echo "<!-- Query: Results            = $CountResults -->\n";
	} else {
		//echo "<!-- Query: Results            = FAILURE -->\n";
	}
	$result->close();

	$query = "SELECT ArchiveID FROM Archives";
	$result = $Connection->query($query);
	$CountArchives = $result->num_rows;
	//echo "<!-- Query: Submitted          = $query -->\n";
	if ( $result ) {
		//echo "<!-- Query: Result             = Success -->\n";
		//echo "<!-- Query: Archives           = $CountArchives -->\n";
	} else {
		//echo "<!-- Query: Results            = FAILURE -->\n";
	}
	$result->close();

	$query = "SELECT ArchiveID FROM Archives WHERE ArchiveState='New' OR ArchiveState='Retry' OR ArchiveState='Assigned'";
	$result = $Connection->query($query);
	$CountNew = $result->num_rows;
	//echo "<!-- Query: Submitted          = $query -->\n";
	if ( $result ) {
		//echo "<!-- Query: Result             = Success -->\n";
		//echo "<!-- Query: New                = $CountNew -->\n";
	} else {
		//echo "<!-- Query: Results            = FAILURE -->\n";
	}
	$result->close();

	$query = "SELECT ArchiveID FROM Archives WHERE ArchiveState='Identifying' OR ArchiveState='Analyzing' OR ArchiveState='Extracting' OR ArchiveState='Downloading' OR ArchiveState='Reporting'";
	$result = $Connection->query($query);
	$CountActive = $result->num_rows;
	//echo "<!-- Query: Submitted          = $query -->\n";
	if ( $result ) {
		//echo "<!-- Query: Result             = Success -->\n";
		//echo "<!-- Query: Active             = $CountActive -->\n";
	} else {
		//echo "<!-- Query: Results            = FAILURE -->\n";
	}
	$result->close();

	$query = "SELECT ArchiveID FROM Archives WHERE ArchiveState='Done'";
	$result = $Connection->query($query);
	$CountDone = $result->num_rows;
	//echo "<!-- Query: Submitted          = $query -->\n";
	if ( $result ) {
		//echo "<!-- Query: Result             = Success -->\n";
		//echo "<!-- Query: Done               = $CountDone -->\n";
	} else {
		//echo "<!-- Query: Results            = FAILURE -->\n";
	}
	$result->close();

	$query = "SELECT ArchiveID FROM Archives WHERE ArchiveState='Error'";
	$result = $Connection->query($query);
	$CountError = $result->num_rows;
	//echo "<!-- Query: Submitted          = $query -->\n";
	if ( $result ) {
		//echo "<!-- Query: Result             = Success -->\n";
		//echo "<!-- Query: Error              = $CountError -->\n";
	} else {
		//echo "<!-- Query: Results            = FAILURE -->\n";
	}
	$result->close();
	
	echo "<BODY BGPROPERTIES=FIXED BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\">\n";
	echo "\n<H1 ALIGN=\"center\">Supportconfig Analysis Appliance<br>Operations</H1>\n";
	echo "<P ALIGN=\"center\">[ ";
	echo "<A HREF=\"index.php\" TARGET=\"reports\">Reports</A> | ";
	echo "<A HREF=\"docs.html\" TARGET=\"docs\">Documentation</A> ]<BR>\n";
	echo "[ ";
	echo "<A HREF=\"detailarch.php?atp=t\" TARGET=\"total\" TITLE=\"All Archives Detailed Report\">All Archives:</A> ";
	if ( $CountNew > 0 ) {
		echo "<A HREF=\"detailarch.php?atp=p\" TARGET=\"pending\" TITLE=\"Detailed Pending Report\">Pending</A>, ";
	} else {
		echo "Pending, ";
	}
	if ( $CountActive > 0 ) {
		echo "<A HREF=\"detailarch.php?atp=a\" TARGET=\"active\" TITLE=\"Detailed Active Report\">Active</A>, ";
	} else {
		echo "Active, ";
	}
	if ( $CountDone > 0 ) {
		echo "<A HREF=\"detailarch.php?atp=d\" TARGET=\"done\" TITLE=\"Detailed Done Report\">Done</A>, ";
	} else {
		echo "Done, ";
	}
	if ( $CountError > 0 ) {
	echo "<A HREF=\"detailarch.php?atp=e\" TARGET=\"error\" TITLE=\"Detailed Error Report\">Error</A>";
	} else {
		echo "Error";
	}
	echo " ]</P>\n";

	// Create Agents table
	echo "\n<H2 ALIGN=\"left\">Agent Summary</H2>\n";
	echo "\n<TABLE ALIGN=\"center\" WIDTH=100% CELLPADDING=2>\n";
	echo "<TR ALIGN=\"left\" CLASS=\"head_2\">";
	echo "<TH>Agent Name</TH>";
	echo "<TH>Agent State</TH>";
	echo "<TH>Event Date</TH>";
	echo "<TH>Agent Message</TH>";
	echo "<TH>Processed</TH>";
	echo "<TH>Active Threads</TH>";
	echo "<TH>CPUCurrent</TH>";
	echo "<TH>Total Patterns</TH>";
	echo "</TR>\n";

	$query="SELECT AgentID,AgentState,AgentEvent,AgentMessage,Patterns,ThreadsActive,ThreadsMax,CPUCurrent,CPUMax,Hostname,ArchivesProcessed FROM Agents ORDER BY  AgentState,AgentPriority,Hostname ASC";
	$result = $Connection->query($query);
	$num = $result->num_rows;
	//echo "<!-- Query: Submitted          = $query -->\n";
	if ( $result ) {
		//echo "<!-- Query: Result             = Success -->\n";
		//echo "<!-- Query: Error              = $num -->\n";
	} else {
		//echo "<!-- Query: Results            = FAILURE -->\n";
	}

	$i = 0;
	while ( $row_cell = $result->fetch_row() ) {
		$AgentID = htmlspecialchars($row_cell[0]);
		$AgentState = htmlspecialchars($row_cell[1]);
		$AgentEvent = htmlspecialchars($row_cell[2]);
		$AgentMessage = htmlspecialchars($row_cell[3]);
		$Patterns = htmlspecialchars($row_cell[4]);
		$ThreadsActive = htmlspecialchars($row_cell[5]);
		$ThreadsMax = htmlspecialchars($row_cell[6]);
		$CPUCurrent = htmlspecialchars($row_cell[7]);
		$CPUMax = htmlspecialchars($row_cell[8]);
		$Hostname = htmlspecialchars($row_cell[9]);
		$Processed = htmlspecialchars($row_cell[10]);

		// Set row color
		if ( $i%2 == 0 ) {
			$row_color="tdGrey";
		} else {
			$row_color="tdGreyLight";
		}

		//Create table rows with data
		echo "<TR ALIGN=\"left\" CLASS=\"$row_color\">";
		echo "<TD>$Hostname</TD>";
		echo "<TD>$AgentState</TD>";
		echo "<TD>$AgentEvent</TD>";
		echo "<TD>$AgentMessage</TD>";
		echo "<TD>$Processed</TD>";
		echo "<TD>$ThreadsActive&nbsp;/&nbsp;$ThreadsMax</TD>";
		echo "<TD>$CPUCurrent%&nbsp;/&nbsp;$CPUMax%</TD>";
		echo "<TD>$Patterns</TD>";
		echo "</TR>\n";

		$i++;
	}
	$result->close();
	$Connection->close();
	echo "</TABLE>\n";

	// Create Archives table
	$row_color="tdGrey";

	echo "\n<H2 ALIGN=\"left\">Archive Summary</H2>\n";
	echo "\n<TABLE ALIGN=\"left\" WIDTH=\"50%\" CELLPADDING=2>\n";
	echo "<TR ALIGN=\"center\" CLASS=\"head_2\">";
	echo "<TH COLSPAN=\"2\" WIDTH=\"25%\"><A HREF=\"detailarch.php?atp=t\" TARGET=\"total\" TITLE=\"All Archives Detailed Report\">Total Archives</A></TH><TH COLSPAN=\"2\" WIDTH=\"25%\">Results</TH>";
	echo "</TR>\n";
	echo "<TR ALIGN=\"center\" CLASS=\"$row_color\">";
	echo "<TD COLSPAN=\"2\">$CountArchives</TD><TD COLSPAN=\"2\">$CountResults</TD>";
	echo "</TR>\n";
	echo "<TR ALIGN=\"center\" CLASS=\"head_2\">";
	if ( $CountNew > 0 ) {
		echo "<TH WIDTH=\"25%\"><A HREF=\"detailarch.php?atp=p\" TARGET=\"pending\" TITLE=\"Detailed Pending Report\">Pending</A></TH>";
	} else {
		echo "<TH WIDTH=\"25%\">Pending</TH>";
	}
	if ( $CountActive > 0 ) {
		echo "<TH WIDTH=\"25%\"><A HREF=\"detailarch.php?atp=a\" TARGET=\"active\" TITLE=\"Detailed Active Report\">Active</A></TH>";
	} else {
		echo "<TH WIDTH=\"25%\">Active</TH>";
	}
	if ( $CountDone > 0 ) {
		echo "<TH WIDTH=\"25%\"><A HREF=\"detailarch.php?atp=d\" TARGET=\"done\" TITLE=\"Detailed Done Report\">Done</A></TH>";
	} else {
		echo "<TH WIDTH=\"25%\">Done</TH>";
	}
	if ( $CountError > 0 ) {
		echo "<TH WIDTH=\"25%\"><A HREF=\"detailarch.php?atp=e\" TARGET=\"error\" TITLE=\"Detailed Error Report\">Error</A></TH>";
	} else {
		echo "<TH WIDTH=\"25%\">Error</TH>";
	}
	echo "</TR>\n";
	echo "<TR ALIGN=\"center\" CLASS=\"$row_color\">";
	echo "<TD>$CountNew</TD><TD>$CountActive</TD><TD>$CountDone</TD><TD>$CountError</TD>";
	echo "</TR>\n";
	echo "</TABLE>\n";
	echo "</BODY>\n";
	echo "</HTML>\n";
?>

