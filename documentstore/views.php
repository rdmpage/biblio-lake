<?php

// Shared routines for documentstore

require_once(dirname(__FILE__) . '/couchsimple.php');

//----------------------------------------------------------------------------------------
// Get all objects modified after a certain date for a given design
// assumes that design has view called "modified" emits a timestamp for each document
function list_modified($view, $from = null)
{
	global $config;
	global $couch;
	
	$response_obj = null;
		
	$url = '_design/' . $view . '/_view/modified';
	
	//$start_key =
	if ($from)
	{
		$start_key = $from;
	}
	else
	{
		// Make October 1 2016 the "start date"
		$start_key = '2016-10-01'; //date("c", time());
	}
	$end_key = date("c", time());
	
	$url .= '?start_key=' . urlencode('"' . $start_key . '"') . '&end_key='. urlencode('"' . $end_key . '"');
	
	
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);

	if ($resp)
	{
		$response_obj = json_decode($resp);
	}	
	
	return $response_obj;	
}

?>