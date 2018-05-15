<?php

require_once(dirname(dirname(__FILE__)) . '/lib.php');
require_once (dirname(dirname(dirname(__FILE__))) . '/documentstore/couchsimple.php');


//----------------------------------------------------------------------------------------
// CrossRef API
function get_work($doi)
{
	global $couch;
	$force = true;

	$data = null;
	
	$url = 'https://api.crossref.org/v1/works/http://dx.doi.org/' . $doi;
	
	$json = get($url);
	
	if ($json != '')
	{
		$obj = json_decode($json);
		if ($obj)
		{
			$data = new stdclass;
			
			// https://github.com/CrossRef/rest-api-doc#result-types
			$data->{'message-format'} = 'application/vnd.crossref-api-message+json';
			
			// Set URL we got data from
			$data->{'message-source'} = $url;
						
			$data->message = $obj->message;
			
			// authors
			if (isset($data->message->author))
			{
				foreach ($data->message->author as $author)
				{
					if (isset($author->ORCID))
					{
						$data->links[] = str_replace('http:', 'https:', $author->ORCID);
					}
				}
			}
					
			// cited literature (ensure we use same logic when naming these as in CouchDB view)
			// see http://data.crossref.org/schemas/common4.3.7.xsd
			if (isset($data->message->reference))
			{
				// extract and add cited literature to database
				
				foreach ($data->message->reference as $cited) {
				
					// If reference has a DOI we simply add that to our list of links,
					// and these may be added to the queue 
					if (isset($cited->DOI))
					{
						$data->links[] = 'https://doi.org/' . strtolower(trim($cited->DOI));
					} 
					else 
					{
						// Handle citations that lack a DOI
						
						$doc = new stdclass;
						$doc->message = $reference;

						$doc->{'message-format'} = 'application/vnd.crossref-citation+json'; // made up by rdmp	
						$doc->{'message-timestamp'} = date("c", time());
						$doc->{'message-modified'} 	= $doc->{'message-timestamp'};
					
						// need consistent way of identifying these references,
						// note that the "key" used by CrossRef or in HTML version of article
						// is not always compatible with a URI :(
						
						$id = $cited->key;
						
						// clean 						
						$id = preg_replace('/[^a-zA-Z0-9_]/', '', $id);
												
						// make hash id 
						$doc->_id = 'https://doi.org/' . $doi . '#' . $id;
						$doc->cluster_id = $doc->_id;
						
						$doc->message = $cited;
						
						// Add directly to database
						$exists = $couch->exists($doc->_id);
						if (!$exists)
						{
							$couch->add_update_or_delete_document($doc, $doc->_id, 'add');	
						}
						else
						{
							if ($force)
							{
								$couch->add_update_or_delete_document($doc, $doc->_id, 'update');
							}
						}

					}
				}
			}


			
		}
	}
	
	return $data;
}


//----------------------------------------------------------------------------------------
function crossref_fetch($doi)
{
	$data = get_work($doi);
	
	//print_r($data);
	
	return $data;
}


// test cases

if (0)
{
	$doi = '10.1371/journal.pone.0139421'; // no links to XML
	
	$doi = '10.3897/zookeys.520.6185'; // has links to XML
	
	$doi = '10.7554/eLife.08347';
	
	$doi = '10.1038/sdata.2015.35';
	
	$doi = '10.15585/mmwr.mm6503e3';
	
	// Three new species of Begonia (Begoniaceae) from Bahia, Brazil
	$doi = '10.3897/phytokeys.44.7993'; 
	
	//$doi = '10.1016/j.ympev.2011.05.006';
	
	$doi = '10.3897/zookeys.446.8195';
	
	// CrossRef metadata has ORCIDs
	//$doi = '10.7554/eLife.08347'; 
	
	$doi = '10.1007/s12225-010-9229-9'; 
	
	//$doi = '10.1371/journal.pone.0194877'; // PloS with lots of references
	
	$data = crossref_fetch($doi);
	
	print_r($data);
}

?>
