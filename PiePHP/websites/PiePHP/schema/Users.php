<?

$SCHEMA['Users'] = array(
	'id' => 'int(10) unsigned NOT NULL auto_increment',
	'Username' => 'varchar(64) NOT NULL default \'\'',
	'Password' => 'varchar(64) NOT NULL default \'\'',
	0 => array('id'),
	1 => array('Username')
);

?>