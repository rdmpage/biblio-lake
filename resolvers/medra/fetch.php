<?php

// medra bibliographic record (typically not very complete compared to CrossRef)

require_once(dirname(dirname(__FILE__)) . '/lib.php');


//----------------------------------------------------------------------------------------
function medra_fetch($doi)
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
	$doi = '10.12905/0380.sydowia66(1)2014-0099'; 
	
	$data = medra_fetch($doi);
	
	print_r($data);
	
	echo json_encode($data);
}

?>
