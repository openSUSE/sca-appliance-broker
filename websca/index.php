<?PHP include 'checklogin.php';?>

<HTML>
<?PHP //echo "<!-- Modified: Date       = 2014 Jan 28 -->\n"; ?>
<HEAD>
<TITLE>SCA Reports</TITLE>
<?PHP
	ini_set('include_path', '/srv/www/htdocs/scdiag/');
	include 'db-config.php';
	$DefaultTop = 30;
	$DefaultRowStart = 0;
	$DefaultSortType = 'r';
	$Top = $_GET['top'];
	$rowStart = $_GET['row'];
	$sortType = $_GET['st'];

	if ( isset($Top) ) {
		if ( ! is_numeric($Top) ) { $Top = $DefaultTop; } 
	} else { 
		$Top = $DefaultTop;
	}
	if ( isset($rowStart) ) {
		if ( ! is_numeric($rowStart) ) { $rowStart = $DefaultRowStart; } 
	} else { 
		$rowStart = $DefaultRowStart;
	}
	switch ($sortType) {
	case 's':
	case 'r':
	case 't':
	case 'd':
	case 'a':
	case 'c':
	case 'w':
	case 'm':
	case 'g':
		break;
	default:
		$sortType = $DefaultSortType;
		break;
	}

	$rowNext = $rowStart + $Top;
	$rowPrev = $rowStart - $Top;
	//echo "<!-- Variable: rowStart        = $rowStart -->\n";
	//echo "<!-- Variable: rowNext         = $rowNext -->\n";
	//echo "<!-- Variable: rowPrev         = $rowPrev -->\n";
	//echo "<!-- Variable: sortType        = $sortType -->\n";
	//echo "<!-- Variable: Top             = $Top -->\n";
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"$StatsRefresh;URL=index.php?top=$Top&row=$rowStart&st=$sortType\">\n";

	$ColorRed = "#FF0000";
	$ColorYellow = "#FFFF00";
	$ColorRoyalBlue = "#1975FF";
	$ColorGreen = "#00FF00";
	$ColorWhite = "#FFFFFF";
	$ColorGray = "#EEEEEE";
	$ColorDarkGray = "#898989";
	$ColorLightGray = "#CDCDCD";
	$ColorSteelBlue = "#B0C4DE";
	$ColorPeach = "#FFCC99";
	$ColorBlack = "#000000";
	$ColorBlue = "#0000FF";
?>
<META HTTP-EQUIV="Content-Style-Type" CONTENT="text/css">
<LINK REL="stylesheet" HREF="style.css">

<?PHP
	echo "</HEAD>\n";
	echo "<BODY BGPROPERTIES=FIXED BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\">\n";
	echo "\n<H1 ALIGN=\"center\">Supportconfig Analysis Appliance<BR>$Top Most Recent Reports</H1>\n";
	echo "<P ALIGN=\"center\">[ ";
	echo "<A HREF=\"opstate.php\" TARGET=\"opstate\">Operations</A> | ";
	echo "<A HREF=\"../sdp\" TARGET=\"sdp\">Create Patterns</A> | ";
	echo "<A HREF=\"docs.html\" TARGET=\"docs\">Documentation</A> ]<BR>\n";
	echo "[ ";
	echo "<A HREF=\"detailarch.php?atp=t&top=$Top&row=$rowStart\" TARGET=\"total\" TITLE=\"All Archives Detailed Report\">All Archives:</A> ";
	echo "<A HREF=\"detailarch.php?atp=p&top=$Top&row=$rowStart\" TARGET=\"pending\" TITLE=\"Detailed Pending Report\">Pending</A>, ";
	echo "<A HREF=\"detailarch.php?atp=a&top=$Top&row=$rowStart\" TARGET=\"active\" TITLE=\"Detailed Active Report\">Active</A>, ";
	echo "<A HREF=\"detailarch.php?atp=d&top=$Top&row=$rowStart\" TARGET=\"done\" TITLE=\"Detailed Done Report\">Done</A>, ";
	echo "<A HREF=\"detailarch.php?atp=e&top=$Top&row=$rowStart\" TARGET=\"error\" TITLE=\"Detailed Error Report\">Error</A>";
	echo " ]<BR>\n";

	include 'db-open.php';
	$DB_FIELDS='ArchiveID,ServerName,ReportDate,ReportTime,PatternsCritical,PatternsWarning,PatternsRecommended,PatternsSuccess,Distro,DistroSP,Architecture,ArchiveDate,ArchiveTime';
	switch ($sortType) {
	case 's':
		//echo "<!-- Sorting by:          = Server -->\n";
		$query="SELECT $DB_FIELDS FROM Archives WHERE ArchiveState='Done' ORDER BY ServerName ASC, ReportDate DESC, ReportTime DESC LIMIT " . $rowStart . "," . $Top;
		break;
	case 'r':
		//echo "<!-- Sorting by:          = Report Date -->\n";
		$query="SELECT $DB_FIELDS FROM Archives WHERE ArchiveState='Done' ORDER BY ReportDate DESC, ReportTime DESC, ServerName ASC LIMIT " . $rowStart . "," . $Top;
		break;
	case 't':
		//echo "<!-- Sorting by:          = Report Date -->\n";
		$query="SELECT $DB_FIELDS FROM Archives WHERE ArchiveState='Done' ORDER BY ArchiveDate DESC, ArchiveTime DESC, ServerName ASC LIMIT " . $rowStart . "," . $Top;
		break;
	case 'd':
		//echo "<!-- Sorting by:          = Distribution -->\n";
		$query="SELECT $DB_FIELDS FROM Archives WHERE ArchiveState='Done' ORDER BY Distro ASC, ServerName ASC, PatternsCritical DESC LIMIT " . $rowStart . "," . $Top;
		break;
	case 'a':
		//echo "<!-- Sorting by:          = Architecture -->\n";
		$query="SELECT $DB_FIELDS FROM Archives WHERE ArchiveState='Done' ORDER BY Architecture ASC, ServerName ASC, PatternsCritical DESC LIMIT " . $rowStart . "," . $Top;
		break;
	case 'c':
		//echo "<!-- Sorting by:          = Critical -->\n";
		$query="SELECT $DB_FIELDS FROM Archives WHERE ArchiveState='Done' ORDER BY PatternsCritical DESC, ServerName ASC, ReportDate DESC, ReportTime DESC LIMIT " . $rowStart . "," . $Top;
		break;
	case 'w':
		//echo "<!-- Sorting by:          = Warning -->\n";
		$query="SELECT $DB_FIELDS FROM Archives WHERE ArchiveState='Done' ORDER BY PatternsWarning DESC, ServerName ASC, ReportDate DESC, ReportTime DESC LIMIT " . $rowStart . "," . $Top;
		break;
	case 'm':
		//echo "<!-- Sorting by:          = Recommended -->\n";
		$query="SELECT $DB_FIELDS Archives WHERE ArchiveState='Done' ORDER BY PatternsRecommended DESC, ServerName ASC, ReportDate DESC, ReportTime DESC LIMIT " . $rowStart . "," . $Top;
		break;
	case 'g':
		//echo "<!-- Sorting by:          = Success -->\n";
		$query="SELECT $DB_FIELDS Archives WHERE ArchiveState='Done' ORDER BY PatternsSuccess DESC, ServerName ASC, ReportDate DESC, ReportTime DESC LIMIT " . $rowStart . "," . $Top;
		break;
	}
	$result=mysql_query($query);
	$num=mysql_numrows($result);
	//echo "<!-- Query: Submitted          = $query -->\n";
	if ( $result ) {
		//echo "<!-- Query: Result             = Success -->\n";
		//echo "<!-- Query: Rows               = $num -->\n";
	} else {
		//echo "<!-- Query: Results            = FAILURE -->\n";
	}
	include 'db-close.php';

	if ( $num > 0 ) {
		echo "[ Paging:&nbsp;&nbsp;";
		if ( $rowStart > 0 ) {
			echo "<A HREF=\"index.php?top=$Top&row=$rowPrev&st=$sortType\" ALT=\"Previous\">Prev</A>&nbsp;&nbsp;";
		} else {
			echo "Prev&nbsp;&nbsp;";
		}
		if ( $num >= $Top ) {
			echo "<A HREF=\"index.php?top=$Top&row=$rowNext&st=$sortType\" ALT=\"Next\">Next</A>&nbsp;&nbsp;";
		} else {
			echo "Next&nbsp;&nbsp;";
		}
		if ( $rowStart != 0 ) {
			echo "<A HREF=\"index.php?top=$Top&row=0&st=$sortType\" ALT=\"Top\">Top</A>&nbsp;&nbsp;| ";
		} else {
			echo "Top&nbsp;&nbsp;| ";
		}
		echo "List Top ";
		if ( $Top == 10 ) { echo "10, "; } else { echo "<A HREF=\"index.php?top=10&row=$rowStart&st=$sortType\">10</A>, "; }
		if ( $Top == 20 ) { echo "20, "; } else { echo "<A HREF=\"index.php?top=20&row=$rowStart&st=$sortType\">20</A>, "; }
		if ( $Top == 30 ) { echo "30, "; } else { echo "<A HREF=\"index.php?top=30&row=$rowStart&st=$sortType\">30</A>, "; }
		if ( $Top == 50 ) { echo "50, "; } else { echo "<A HREF=\"index.php?top=50&row=$rowStart&st=$sortType\">50</A>, "; }
		if ( $Top == 100 ) { echo "100, "; } else { echo "<A HREF=\"index.php?top=100&row=$rowStart&st=$sortType\">100</A>"; }
		echo " ]</P>\n";
	}

	// Create table header
	echo "\n<TABLE ALIGN=\"center\" WIDTH=100% CELLPADDING=2>\n";
	echo "<TR ALIGN=\"left\" CLASS=\"head_2\">";
	if ( $sortType == 's' ) {	
		echo "<TH WIDTH=\"15%\">Server Name</TH>";
	} else {
		echo "<TH WIDTH=\"15%\"><A HREF=\"index.php?top=$Top&row=$rowStart&st=s\">Server Name</A></TH>";
	}
	if ( $sortType == 'r' ) {	
		echo "<TH>Report Date</TH>";
	} else {
		echo "<TH><A HREF=\"index.php?top=$Top&row=$rowStart&st=r\">Report Date</A></TH>";
	}
	if ( $sortType == 't' ) {	
		echo "<TH>Supportconfig Date</TH>";
	} else {
		echo "<TH><A HREF=\"index.php?top=$Top&row=$rowStart&st=t\">Supportconfig Date</A></TH>";
	}
	if ( $sortType == 'd' ) {	
		echo "<TH ALIGN=\"left\">Distribution</TH>";
	} else {
		echo "<TH ALIGN=\"left\"><A HREF=\"index.php?top=$Top&row=$rowStart&st=d\">Distribution</A></TH>";
	}
	if ( $sortType == 'a' ) {
		echo "<TH ALIGN=\"left\">Arch</TH>";
	} else {
		echo "<TH ALIGN=\"left\"><A HREF=\"index.php?top=$Top&row=$rowStart&st=a\">Arch</A></TH>";
	}
	if ( $sortType == 'c' ) {
		echo "<TH WIDTH=\"5%\">Critical</TH>";
	} else {
		echo "<TH WIDTH=\"5%\"><A HREF=\"index.php?top=$Top&row=$rowStart&st=c\">Critical</A></TH>";
	}
	if ( $sortType == 'w' ) {
		echo "<TH WIDTH=\"5%\">Warning</TH>";
	} else {
		echo "<TH WIDTH=\"5%\"><A HREF=\"index.php?top=$Top&row=$rowStart&st=w\">Warning</A></TH>";
	}
	if ( $sortType == 'm' ) {
		echo "<TH WIDTH=\"5%\">Recommended</TH>";
	} else {
		echo "<TH WIDTH=\"5%\"><A HREF=\"index.php?top=$Top&row=$rowStart&st=m\">Recommended</A></TH>";
	}
	if ( $sortType == 'g' ) {
		echo "<TH WIDTH=\"5%\">Success</TH>";
	} else {
		echo "<TH WIDTH=\"5%\"><A HREF=\"index.php?top=$Top&row=$rowStart&st=g\">Success</A></TH>";
	}
	echo "</TR>\n";

	for ( $i=0, $active_num=0; $i < $num; $i++ ) {
		$row_cell = mysql_fetch_row($result);
		$ArchiveID = htmlspecialchars($row_cell[0]);
		$ServerName = htmlspecialchars($row_cell[1]);
		$ReportDate = htmlspecialchars($row_cell[2]);
		$ReportTime = htmlspecialchars($row_cell[3]);
		$PatternsCritical = htmlspecialchars($row_cell[4]);
		$PatternsWarning = htmlspecialchars($row_cell[5]);
		$PatternsRecommended = htmlspecialchars($row_cell[6]);
		$PatternsSuccess = htmlspecialchars($row_cell[7]);
		$Distro = htmlspecialchars($row_cell[8]);
		$DistroSP = htmlspecialchars($row_cell[9]);
		$Architecture = htmlspecialchars($row_cell[10]);
		$ArchiveDate = htmlspecialchars($row_cell[11]);
		$ArchiveTime = htmlspecialchars($row_cell[12]);

		// Set row color
		if ( $i%2 == 0 ) {
			$row_color="tdGrey";
		} else {
			$row_color="tdGreyLight";
		}

		//Create table rows with data
		echo "<TR ALIGN=\"left\" CLASS=\"$row_color\">";
		echo "<TD>$ServerName</TD>";
		echo "<TD><A HREF=\"reportfull.php?aid=$ArchiveID\" TARGET=\"$ServerName\" TITLE=\"SCA Report for $ServerName\">$ReportDate $ReportTime</A></TD>";
		echo "<TD>$ArchiveDate $ArchiveTime</TD>";
		echo "<TD>$Distro SP$DistroSP</TD>";
		echo "<TD>$Architecture</TD>";
		echo "<TD>$PatternsCritical</TD>";
		echo "<TD>$PatternsWarning</TD>";
		echo "<TD>$PatternsRecommended</TD>";
		echo "<TD>$PatternsSuccess</TD>";
		echo "</TR>\n";
	}
	echo "</TABLE>\n";

	if ( $num > 0 ) {
		echo "<P ALIGN=\"center\">[ ";
		echo "Paging:&nbsp;&nbsp;";
		if ( $rowStart > 0 ) {
			echo "<A HREF=\"index.php?top=$Top&row=$rowPrev\" ALT=\"Previous\">Prev</A>&nbsp;&nbsp;";
		} else {
			echo "Prev&nbsp;&nbsp;";
		}
		if ( $num >= $Top ) {
			echo "<A HREF=\"index.php?top=$Top&row=$rowNext\" ALT=\"Next\">Next</A>&nbsp;&nbsp;";
		} else {
			echo "Next&nbsp;&nbsp;";
		}
		if ( $rowStart != 0 ) {
			echo "<A HREF=\"index.php?top=$Top&row=0\" ALT=\"Top\">Top</A>&nbsp;&nbsp;| ";
		} else {
			echo "Top&nbsp;&nbsp;| ";
		}
		echo "List Top ";
		if ( $Top == 10 ) { echo "10, "; } else { echo "<A HREF=\"index.php?top=10&row=$rowStart\">10</A>, "; }
		if ( $Top == 20 ) { echo "20, "; } else { echo "<A HREF=\"index.php?top=20&row=$rowStart\">20</A>, "; }
		if ( $Top == 30 ) { echo "30, "; } else { echo "<A HREF=\"index.php?top=30&row=$rowStart\">30</A>, "; }
		if ( $Top == 50 ) { echo "50, "; } else { echo "<A HREF=\"index.php?top=50&row=$rowStart\">50</A>, "; }
		if ( $Top == 100 ) { echo "100, "; } else { echo "<A HREF=\"index.php?top=100&row=$rowStart\">100</A>"; }
		echo " ]</P>\n";
	}
	echo "</BODY>\n";
	echo "</HTML>\n";
?>

