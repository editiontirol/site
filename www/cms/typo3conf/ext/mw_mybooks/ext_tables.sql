#
# Table structure for table 'tx_mwmybooks_books'
#
CREATE TABLE tx_mwmybooks_books (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	title tinytext NOT NULL,
	author tinytext NOT NULL,
	isbn tinytext NOT NULL,
	price tinytext NOT NULL,
	shortdescription tinytext NOT NULL,
	description text NOT NULL,
	cover blob NOT NULL,
	link tinytext NOT NULL,
	category int(11) DEFAULT '0' NOT NULL,
	martinreiter tinyint(3) DEFAULT '0' NOT NULL,
	available tinyint(3) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_mwmybooks_category'
#
CREATE TABLE tx_mwmybooks_category (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	name tinytext NOT NULL,
	description tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	tx_mwmybooks_category int(11) DEFAULT '0' NOT NULL,
	tx_mwmybooks_martinreiter tinyint(3) DEFAULT '0' NOT NULL
);