CREATE TABLE users (
	id bigint(20) unsigned NOT NULL auto_increment,
	first_name varchar(50) NOT NULL,
	last_name varchar(50) NOT NULL,
	email varchar(254) NOT NULL,
	username varchar(32) NOT NULL,
	`password` varchar(32) NOT NULL,
	PRIMARY KEY  (id),
	KEY first_name (first_name,last_name)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO users VALUES (1, 'Sam', 'Eubank', 'sameubank@gmail.com', 'BigBear', '7c6a180b36896a0a8c02787eeafb0e4c');


CREATE TABLE user_groups (
	id bigint(20) unsigned NOT NULL auto_increment,
	`name` varchar(50) NOT NULL,
	PRIMARY KEY  (id),
	KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO user_groups VALUES (1, 'System Administrators');
INSERT INTO user_groups VALUES (2, 'Developers');
INSERT INTO user_groups VALUES (3, 'Administrators');
INSERT INTO user_groups VALUES (4, 'Moderators');