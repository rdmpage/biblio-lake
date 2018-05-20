<?php

// What needs to be matched?

require_once(dirname(dirname(__FILE__)) . '/documentstore/couchsimple.php');
require_once(dirname(dirname(__FILE__)) . '/documentstore/merge_records.php');
require_once(dirname(dirname(__FILE__)) . '/documentstore/views.php');

//----------------------------------------------------------------------------------------
// Get hash for document $id
function doc_to_hash($id)
{
	global $config;
	global $couch;
	
	$hash = array();

	$url = '_design/matching/_view/doc_to_hash' . '?key=' . urlencode('"' . $id . '"');
		
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);

	if ($resp)
	{
		$response_obj = json_decode($resp);
		
		if (!isset($response_obj->error))
		{
			if (count($response_obj->rows) == 1) {
				$hash = $response_obj->rows[0]->value;
			}		
		}
	}
	
	return $hash;
}

//----------------------------------------------------------------------------------------
// Get DOI for document $id
function doc_to_doi($id)
{
	global $config;
	global $couch;

	$doi = '';

	$url = '_design/matching/_view/doc_to_doi' . '?key=' . urlencode('"' . $id . '"');
	
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);

	if ($resp)
	{
		$response_obj = json_decode($resp);
		
		if (!isset($response_obj->error))
		{
			if (count($response_obj->rows) == 1) {
				$doi = $response_obj->rows[0]->value;
			}		
		}
	}
	
	return $doi;
}

//----------------------------------------------------------------------------------------
function cluster_by_doi($doi) {
	global $config;
	global $couch;
	
	// list of works that have been clustered
	$works_clustered = [];
	
	$url = '_design/matching/_view/doi?key=' . urlencode('"' . $doi . '"');
	
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);
	
	$response_obj = json_decode($resp);
	
	$records = array();

	foreach ($response_obj->rows as $row)
	{
		$records[] = $row->id;
	}
	
	// Do we have more than one work with this hash?
	if (count($records) > 1)
	{
		// Records that could be clustered		
		echo "Works with this doi:\n";
		print_r($records);
		
		// Find clusters for these records
		$clusters = merge_records($records, false);
		
		echo "Clusters:\n";
		print_r($clusters);
		
	}	

}

//----------------------------------------------------------------------------------------
// Cluster records
function cluster_records($match_key_name, $match_key)
{
	global $config;
	global $couch;
	
	// list of works that have been clustered
	$works_clustered = [];
	
	$url = '';
	
	switch ($match_key_name)
	{
		case 'hash':
			// $match_key is an array
			$url = '_design/matching/_view/hash?key=' . urlencode(json_encode($match_key));
			break;
			
		case 'doi':
			// $match_key is a DOI
			$url = '_design/matching/_view/doi?key=' . urlencode('"' . $match_key . '"');
			break;
			
		// no method so no clustering
		default:
			return $works_clustered;
			break;
	
	}
	
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);	
	$response_obj = json_decode($resp);

	$records = array();

	foreach ($response_obj->rows as $row)
	{
		$records[] = $row;
	}
	
	// Do we have more than one work with this hash?
	if (count($records) > 1)
	{
		// We have records that could be clustered	
		
		echo "Records:\n";
		print_r($records);			
		
		// Find clusters for these records
		$clusters = merge_records($records, true);
		
		echo "Clusters:\n";
		print_r($clusters);
		
		
		if (count($clusters) > 0)
		{
			foreach ($clusters as $cluster_id => $members)
			{
				foreach ($members as $member)
				{
					echo $cluster_id . '->' . $member . "\n";
					
					// "merge" by updating cluster_id					
					$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . urlencode($member));
					if ($resp)
					{
						$doc = json_decode($resp);
						if (!isset($doc->error))
						{
							// Set cluster_id to id for this cluster
							$doc->cluster_id = $cluster_id;
							
							// Update modified timestamp so document will be among those
							// loaded into Elasticsearch
							$doc->{'message-modified'} = date("c", time());	
					
							// update
							$couch->add_update_or_delete_document($doc, $doc->_id, 'update');
						}
					}	
					
					$works_clustered[] = $member;					
				}
			}
		}
	}
	
	return $works_clustered;
}

//----------------------------------------------------------------------------------------

// Update clusters by getting list of modified records for a view and applying a given matching rule
function update($view = 'csl', $from = null, $match_type = 'doi')
{
	$obj = list_modified($view, $from);

	// Get list of ids to process
	$queue = array();
	foreach ($obj->rows as $row)
	{
		$queue[] = $row->value;
	}
		
	// Go through list
	while (count($queue) >  0)
	{
		$work = array_pop($queue);

		echo "Work: " . $work . "\n";
		
		$works_clustered = array();
	
		switch ($match_type)
		{
			case 'hash':
				$hash = doc_to_hash($work);	
				echo "Hash: " . json_encode($hash) . "\n";
				if (count($hash) == 3)
				{
					$works_clustered = cluster_records('hash', $hash);
				}				
				break;
			
			case 'doi':
				$doi = doc_to_doi($work);	
				echo "DOI: $doi\n";
				if ($doi != '')
				{
					$works_clustered = cluster_records('doi', $doi);
				}				
				break;
			
			default:
				break;
		}
		
		// If any works in this cluster are also in our queue then delete them as we have
		// already clustered them.
		$queue = array_diff($queue, $works_clustered);		

	}
}

//----------------------------------------------------------------------------------------

//update('csl',null, 'doi');

$start_time = date("c", time() - (60 * 5)); // last 5 minutes

$start_time = date("c", time() - (60 * 180)); // last 3 hours

//update('csl', $start_time, 'hash');
//update('reference', $start_time, 'hash');

cluster_records('hash', array(1960, 83, 79));


?>

