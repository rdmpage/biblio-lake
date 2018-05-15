<?php

// Send data from CouchDB to Elasticsearch

require_once(dirname(dirname(__FILE__)) . '/documentstore/couchsimple.php');
require_once(dirname(dirname(__FILE__)) . '/documentstore/views.php');
require_once(dirname(dirname(__FILE__)) . '/elastic/elastic.php');

//----------------------------------------------------------------------------------------
// Upload one search document (use a search data "schema")
function doc_to_elastic($view, $id)
{
	global $config;
	global $couch;
	global $elastic;

	$url = '_design/' . $view . '/_view/elastic' . '?key=' . urlencode('"' . $id . '"');
	
	//echo $url . "\n";
	
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);

	if ($resp)
	{
		$response_obj = json_decode($resp);
		
		//print_r($response_obj);
		
		if (!isset($response_obj->error))
		{
			$doc = $response_obj->rows[0]->value;
			
			$doc->id = $response_obj->rows[0]->id;

			$elastic_doc = new stdclass;
			$elastic_doc->doc = $doc;
			$elastic_doc->doc_as_upsert = true;

			//print_r($elastic_doc);

			$elastic->send('POST',  $elastic_doc->doc->type . '/' . urlencode($doc->id). '/_update', json_encode($elastic_doc));					
		
		}
	}
}



//----------------------------------------------------------------------------------------

$view = 'zoobank';
$view = 'csl';
$view = 'reference';

$obj = list_modified($view);

$obj = list_modified($view, '2018-05-12');

$obj = list_modified($view, '2018-05-12');

$start_time = date("c", time() - (60 * 5)); // last 5 minutes

$start_time = date("c", time() - (60 * 60)); // last hour
//$start_time = date("c", time() - (60 * 180)); // last 3 hours


//$views = array('csl', 'reference');
$views = array('csl', 'reference');

foreach ($views as $view)
{
	$obj = list_modified($view, $start_time);

	foreach ($obj->rows as $row)
	{
		doc_to_elastic($view, $row->value);
	}
}

?>

