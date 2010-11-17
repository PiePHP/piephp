ALTER TABLE users
	ADD user_groups TEXT NOT NULL,
	ADD FULLTEXT (
		user_groups
	);

DROP TABLE IF EXISTS users_user_groups;
CREATE TABLE users_user_groups(
	user_id INT UNSIGNED NOT NULL,
	user_group_id INT UNSIGNED NOT NULL,
	PRIMARY KEY(user_id, user_group_id)
) ENGINE=MyISAM;

