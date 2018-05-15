<?php

// Try to augment a record by adding a DOI

require_once(dirname(dirname(__FILE__)) . '/resolvers/lib.php');
require_once(dirname(dirname(__FILE__)) . '/documentstore/couchsimple.php');
require_once(dirname(dirname(__FILE__)) . '/queue/queue.php');


//----------------------------------------------------------------------------------------
// Get query string

function get_query_string($id)
{
	global $config;
	global $couch;
	
	$query_string = '';
	
	$url = '_design/matching/_view/query?key=' . urlencode('"' . $id . '"');
	
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);
	
	if ($resp)
	{
		$response_obj = json_decode($resp);
		
		if (!isset($response_obj->error))
		{
			if (count($response_obj->rows) == 1) {
				$query = $response_obj->rows[0]->value;
				
				$query_string = $query->string;
			}		
		}
	}
	
	return $query_string;
}

//----------------------------------------------------------------------------------------


$ids = array(
'https://orcid.org/0000-0003-3628-2567/work/21039667'
);

foreach ($ids as $id)
{
	echo "Work: " . $id . "\n";

	$query_string = get_query_string($id);
	
	if ($query_string != '')
	{
		$url = 'https://mesquite-tongue.glitch.me/search?q=' . urlencode($query_string);
		
		$json = get($url);
		
		if ($json != '')
		{
			$obj = json_decode($json);
			
			if (count($obj) == 1)
			{
				if ($obj[0]->match)
				{
					$doi = $obj[0]->id;
					
					echo "Found DOI: " . $doi . "\n";
					
					
					
					// update document store item with message content
					$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . urlencode($id));
					var_dump($resp);
					if ($resp)
					{
						$doc = json_decode($resp);
						if (!isset($doc->error))
						{
							// 1. Update record with DOI
							$doc->message->DOI = $doi;
							
							// Need to think this through, but for now set
							// cluster_id to DOI
							
							// 2. Set cluster_id to DOI to link this record to record with DOI
							// , update timestamp so record will be uploaded to elasticsearch
							
							$doc->cluster_id = 'https://doi.org/' . $doi;							
							$doc->{'message-modified'} = date("c", time());	
							
							$resp = $couch->send("PUT", "/" . $config['couchdb_options']['database'] . "/" . urlencode($doc->_id), json_encode($doc));
							var_dump($resp);
							
							// 3. Make sure we have the DOI record by adding it to the queue							
							enqueue('https://doi.org/' . $doi, false);
						}
					}	
				
				}
			}
		}

	}

}
?>
