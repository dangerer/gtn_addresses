#
# Table structure for table 'tx_gtnaddresses_data_cat_mm'
# 
#
CREATE TABLE tx_gtnaddresses_data_cat_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);



#
# Table structure for table 'tx_gtnaddresses_data'
#
CREATE TABLE tx_gtnaddresses_data (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	moduser_id int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	nachname varchar(255) DEFAULT '' NOT NULL,
	vorname tinytext,
	titel varchar(255) DEFAULT '' NOT NULL,
	geschlecht int(11) DEFAULT '0' NOT NULL,
	jahrgang varchar(4) DEFAULT '' NOT NULL,
	praxis_plz int(11) DEFAULT '0' NOT NULL,
	praxis_ort varchar(255) DEFAULT '' NOT NULL,
	praxis_adresse text,
	aus_weiterbildung text,
	html_format int(11) DEFAULT '0' NOT NULL,
	praxis2_plz int(11) DEFAULT '0' NOT NULL,
	praxis2_ort varchar(255) DEFAULT '' NOT NULL,
	praxis2_adresse text,
	iaus tinyint(3) DEFAULT '0' NOT NULL,
	freiekassenplaetze_abkw int(11) DEFAULT '0' NOT NULL,
	freiekassenplaetze2 tinyint(3) DEFAULT '0' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	telefon varchar(255) DEFAULT '' NOT NULL,
	images text,
	web_adresse tinytext,
	vid int(11) DEFAULT '0' NOT NULL,
	nid int(11) DEFAULT '0' NOT NULL,
	fe_uid int(11) DEFAULT '0' NOT NULL,
	edited_from int(11) DEFAULT '0' NOT NULL,
	bezeichnung text,
	promote tinyint(3) DEFAULT '0' NOT NULL,
	sticky tinyint(3) DEFAULT '0' NOT NULL,
	revision_titel varchar(255) DEFAULT '' NOT NULL,
	beschreibung text,
	teaser text,
	dateien text,
	signature text,
	usersdata text,
	cat int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_gtnaddresses_cat'
#
CREATE TABLE tx_gtnaddresses_cat (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	moduser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	description varchar(255) DEFAULT '' NOT NULL,
	weight int(11) DEFAULT '0' NOT NULL,
	cattype int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_gtnaddresses_cattypes'
#
CREATE TABLE tx_gtnaddresses_cattypes (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	moduser_id int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	description varchar(255) DEFAULT '' NOT NULL,
	multiple tinyint(3) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);