SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ServerDiagnostics`
--

-- --------------------------------------------------------

--
-- Table structure for table `Agents`
--

CREATE TABLE IF NOT EXISTS `Agents` (
  `AgentID` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique agent identification number',
  `Hostname` varchar(255) NOT NULL COMMENT 'The hostname of the agent',
  `AgentState` enum('Configure','Active','Inactive','Dead','Stale','Error') NOT NULL DEFAULT 'Configure' COMMENT 'The current state of the agent',
  `AgentEvent` datetime DEFAULT NULL COMMENT 'The date and time of the last event',
  `AgentMessage` varchar(255) DEFAULT NULL COMMENT 'Last error message from the agent',
  `AgentPriority` tinyint(2) NOT NULL DEFAULT '5' COMMENT '*RESERVED, Unused* Determines which agent will be chosen first for work. 0=Highest Priority',
  `Patterns` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The number of patterns used for analysis',
  `ThreadsActive` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'The current number of active threads running on the agent server',
  `ThreadsMax` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT 'The maximum number of threads that can run concurrently',
  `CPUCurrent` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'The current agent CPU utilization',
  `CPUMax` tinyint(3) unsigned NOT NULL DEFAULT '80' COMMENT 'The maximum CPU utilization allowed for assigning threads',
  `ArchivesProcessed` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'The number of archives processed by the agent',
  PRIMARY KEY (`AgentID`),
  UNIQUE KEY `Hostname` (`Hostname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Responsible for analyzing supportconfig archives' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `AgentWorkers`
--

CREATE TABLE IF NOT EXISTS `AgentWorkers` (
  `WorkerID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identifies the worker thread',
  `WorkersAgentID` int(10) unsigned NOT NULL COMMENT 'The agent ID to which this worker is assigned',
  `ArchiveAssigned` int(10) unsigned DEFAULT NULL COMMENT 'Archive ID assigned to this worker',
  `HomePath` varchar(255) NOT NULL COMMENT 'Path where files are extracted, analyzed and reports built',
  PRIMARY KEY (`WorkerID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='The analysis worker threads' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Archives`
--

CREATE TABLE IF NOT EXISTS `Archives` (
  `ArchiveID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'A unique archive ID number',
  `FileLocation` varchar(512) NOT NULL COMMENT 'The supportconfig tar ball file location URI',
  `Filename` varchar(255) NOT NULL COMMENT 'The supportconfig tar ball filename',
  `ArchiveState` enum('New','Assigned','Retry','Downloading','Extracting','Identifying','Analyzing','Reporting','Done','Error') NOT NULL DEFAULT 'New' COMMENT 'Current archive state',
  `ArchiveEvent` datetime DEFAULT NULL COMMENT 'The date and time of the last state change',
  `ArchiveMessage` varchar(255) DEFAULT NULL COMMENT 'Error message from sdworker thread',
  `RetryCount` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'The retry count used for Retry state',
  `AssignedAgentID` int(10) unsigned DEFAULT NULL COMMENT 'Agent ID from Agents assigned to the archive',
  `AssignedWorkerID` int(10) unsigned DEFAULT NULL COMMENT 'Worker ID from AgentWorkers assigned to the archive',
  `ReportDate` date DEFAULT NULL COMMENT 'Date supportconfig was analyzed',
  `ReportTime` time DEFAULT NULL COMMENT 'Time supportconfig was analyzed',
  `ArchiveDate` date DEFAULT NULL COMMENT 'Date supportconfig was run on the server',
  `ArchiveTime` time DEFAULT NULL COMMENT 'Time supportconfig was run on the server',
  `SRNum` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Service request number',
  `ServerName` varchar(128) DEFAULT NULL COMMENT 'The hostname of the server analyzed',
  `AnalysisTime` mediumint(8) unsigned DEFAULT NULL COMMENT 'The time in seconds to analyze the archive',
  `VersionKernel` varchar(128) DEFAULT NULL COMMENT 'The server''s kernel version reported',
  `VersionSupportconfig` varchar(128) DEFAULT NULL COMMENT 'Supportconfig version used to gather information',
  `Architecture` enum('i386','i586','i686','x86_64','ppc','ppc64','s390','s390x','ia64') DEFAULT NULL COMMENT 'Server''s architecture',
  `Hardware` varchar(256) DEFAULT NULL COMMENT 'The hardware of the server',
  `Distro` varchar(128) DEFAULT NULL COMMENT 'The OS distribution',
  `DistroSP` tinyint(2) unsigned DEFAULT NULL COMMENT 'The OS distribution service pack number',
  `OESDistro` varchar(128) DEFAULT NULL COMMENT 'OES distribution',
  `OESDistroSP` tinyint(2) unsigned DEFAULT NULL COMMENT 'OES distribution service pack number',
  `Hypervisor` varchar(128) DEFAULT NULL COMMENT 'Virtulization hypervisor name',
  `HypervisorIdentity` varchar(128) DEFAULT NULL COMMENT 'Virtualization identification',
  `PatternsApplicable` int(10) unsigned DEFAULT NULL COMMENT 'Patterns that applied to the server',
  `PatternsTested` int(10) unsigned DEFAULT NULL COMMENT 'The total number of patterns tested against the archive',
  `PatternsCritical` int(10) unsigned DEFAULT NULL COMMENT 'Patterns resulting in a critical state',
  `PatternsWarning` int(10) unsigned DEFAULT NULL COMMENT 'Patterns resulting in a warning state',
  `PatternsRecommended` int(10) unsigned DEFAULT NULL COMMENT 'Patterns resulting in a recommended state',
  `PatternsSuccess` int(10) unsigned DEFAULT NULL COMMENT 'Patterns resulting in a successful state',
  PRIMARY KEY (`ArchiveID`),
  UNIQUE KEY `Filename` (`Filename`),
  UNIQUE KEY `SortState` (`ArchiveState`,`RetryCount`,`ArchiveID`),
  KEY `ArchiveEvent` (`ArchiveEvent`,`ArchiveID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Supportconfig archive being analyzed' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Results`
--

CREATE TABLE IF NOT EXISTS `Results` (
  `ResultID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The ID number for the pattern result',
  `ResultsArchiveID` int(10) unsigned NOT NULL COMMENT 'The archive ID of the archive analyzed',
  `Class` varchar(50) DEFAULT NULL COMMENT 'Pattern class',
  `Category` varchar(50) DEFAULT NULL COMMENT 'Pattern category',
  `Component` varchar(50) DEFAULT NULL COMMENT 'Pattern component',
  `PatternID` varchar(255) DEFAULT NULL COMMENT 'Pattern filename used to identify the pattern',
  `PatternLocation` varchar(512) DEFAULT NULL COMMENT 'Relative SVN repository location of the pattern',
  `Result` tinyint(3) unsigned NOT NULL COMMENT 'Numeric pattern result',
  `ResultStr` mediumtext COMMENT 'Pattern overall result string',
  `PrimaryLink` varchar(512) DEFAULT NULL COMMENT 'Primary primary solution link',
  `TID` varchar(512) DEFAULT NULL COMMENT 'Link to TID',
  `BUG` varchar(512) DEFAULT NULL COMMENT 'Link to BUG',
  `URL01` varchar(512) DEFAULT NULL COMMENT 'Link to URL',
  `URL02` varchar(512) DEFAULT NULL COMMENT 'Link to URL',
  `URL03` varchar(512) DEFAULT NULL COMMENT 'Link to URL',
  `URL04` varchar(512) DEFAULT NULL COMMENT 'Link to URL',
  `URL05` varchar(512) DEFAULT NULL COMMENT 'Link to URL',
  `URL06` varchar(512) DEFAULT NULL COMMENT 'Link to URL',
  `URL07` varchar(512) DEFAULT NULL COMMENT 'Link to URL',
  `URL08` varchar(512) DEFAULT NULL COMMENT 'Link to URL',
  `URL09` varchar(512) DEFAULT NULL COMMENT 'Link to URL',
  `URL10` varchar(512) DEFAULT NULL COMMENT 'Link to URL',
  PRIMARY KEY (`ResultID`),
  KEY `archive` (`ResultsArchiveID`),
  KEY `class` (`Class`),
  KEY `category` (`Category`),
  KEY `component` (`Component`),
  KEY `patternid` (`PatternID`),
  KEY `result` (`Result`),
  KEY `TID` (`TID`),
  KEY `BUG` (`BUG`),
  KEY `resultList` (`Class`, `Category`, `Component`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Results of the NSA patterns applied to the archive' AUTO_INCREMENT=1 ;


