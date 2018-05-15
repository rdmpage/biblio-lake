<?php

// My local microcitation service

require_once(dirname(dirname(__FILE__)) . '/lib.php');

//----------------------------------------------------------------------------------------
function local_fetch($guid)
{
	$data = null;
		
	$url = 'http://localhost/~rpage/microcitation/www/citeproc-api.php?guid=' . urlencode($guid);
	
	$json = get($url);
	
	echo $json;
	
	if ($json != '')
	{
		$obj = json_decode($json);
		if ($obj)
		{
			$data = new stdclass;
			$data->{'message-format'} = 'application/vnd.citationstyles.csl+json';
			
			// Set URL we got data from
			$data->{'message-source'} = $url;
			
			$data->message = $obj;
		}
	}
	
	return $data;
}

if (0)
{
	$guid = '10.3969/j.issn.1000-1565.2001.01.019';
	
	$data = local_fetch($guid);
	
	print_r($data);
}	



?>
