<?php

// Backup CouchDB views to file system 

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/couchsimple.php');


$views = array(
	'augment',
	'container',
	'csl',
	'export',
	'housekeeping',
	'matching',
	'queue',
	'reference',
	'zoobank'
);


foreach ($views as $view)
{
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . '/_design/' . $view);
	
	file_put_contents(dirname(__FILE__) . '/couchdb/' . $view . '.js', $resp);
}

		


?>