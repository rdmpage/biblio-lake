<?php

// Datacite bibliographic record (typically not very complete compared to CrossRef)

require_once(dirname(dirname(__FILE__)) . '/lib.php');


//----------------------------------------------------------------------------------------
// Resolve a DOI using content negotiation
function datacite_fetch($doi)
{
	$data = null;
		
	$url = 'https://doi.org/' . $doi;	
	$json = get($url, '', 'application/vnd.citationstyles.csl+json');
			
	if ($json != '')
	{
		$data = new stdclass;
		$data->{'message-format'} = 'application/vnd.citationstyles.csl+json';
		$data->{'message-source'} = $url;
		$data->message = json_decode($json);
	}
	
	return $data;
}


// test cases

if (0)
{
	$doi = '10.13128/Acta_Herpetol-13269'; // no links to XML
	
	$data = datacite_fetch($doi);
	
	print_r($data);
	
	echo json_encode($data);
}

?>
