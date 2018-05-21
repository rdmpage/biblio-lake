<?php

// Delete records from CouchDB added since a given timestamp 
// (handy if we want to clean up some experimental mess);

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/couchsimple.php');

// Get list of documents
// http://127.0.0.1:5984/crowdsource/_design/housekeeping/_view/ids

$response_obj = null;
	
$url = '_design/housekeeping/_view/modified';

$timestamp = "2018-05-21";

$url .= '?startkey=' . urlencode('"' . $timestamp . '"');

echo $url . "\n";

$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);

if ($resp)
{
	$response_obj = json_decode($resp);
	
	foreach ($response_obj->rows as $row)
	{
		if (0)
		{
			echo $row->value . "\n";		
		}
		else
		{
			$couch->add_update_or_delete_document(null, $row->value, 'delete');
		}
	}
}	


?>