<?php

// ORCID API version 2.1

require_once (dirname(dirname(__FILE__)) . '/lib.php');
require_once (dirname(dirname(__FILE__)) . '/nameparse.php');
require_once (dirname(dirname(__FILE__)) . '/fingerprint.php');
//require_once (dirname(dirname(__FILE__)) . '/shared/crossref.php');

require_once (dirname(dirname(dirname(__FILE__))) . '/documentstore/couchsimple.php');


/*
  Problems with ORCID:
  
  1. Only the person whose profile this is has is identified by an ORCID. Coauthors
     with ORCIDs don't have them (sigh).
     
  2. Many works lack DOIs in the ORCID profile, even if they actually have them. Need to
     think about whether we go hunting for these.
     
  3. Works may be duplicated within a profile, and between profiles of co-authors.
  

*/


//----------------------------------------------------------------------------------------
function orcid_fetch($orcid, $lookup_works = false)
{
	$data = null;
		
	$url = 'https://pub.orcid.org/v2.1/' . $orcid;	
	$json = get($url, '', 'application/orcid+json');
	
	//$json = file_get_contents(dirname(__FILE__) . '/https-0000-0001-6238-2743.json');
	//file_put_contents(dirname(__FILE__) . '/0000-0003-0566-372X.json', $json);
	//echo $json;
		
	if ($json != '')
	{
		$data = new stdclass;		
		$data->{'message-format'} = 'application/orcid+json';		
		$data->{'message-source'} = $url;				
		
		$data->message = json_decode($json);
		
		// API 2.1 has API to access individual works via "putcode"
		$data->links = array();
		if (isset($data->message->{'activities-summary'}))
		{
			if (isset($data->message->{'activities-summary'}->{'works'}))
			{
				foreach ($data->message->{'activities-summary'}->{'works'}->{'group'} as $work)
				{
					//print_r($work);
					
					foreach ($work->{'work-summary'} as $summary)
					{
						$doi = '';
						
						if (isset($work->{'external-ids'}))
						{
							if (isset($work->{'external-ids'}->{'external-id'}))
							{
								foreach ($work->{'external-ids'}->{'external-id'} as $external_id)
								{
									if ($external_id->{'external-id-type'} == 'doi')
									{
										$doi = $external_id->{'external-id-value'};
									}
								}
							}
						}
						
						// if we have a DOI then add that to the list of ids to resolve, otherwise get work ids
						if ($doi != '')
						{
							$data->links[] = 'https://doi.org/' . $doi;
						}
						else
						{
							$data->links[] = 'https://orcid.org/' . $orcid . '/work/' . $summary->{'put-code'};
						}
		
					}
				}
			}
		
		}
		
		$data->links = array_values(array_unique($data->links));
	}
	
	return $data;
}

//----------------------------------------------------------------------------------------
// Fetch an individual work from an ORCID profile
function orcid_work_fetch($orcid_work, $lookup_works = false)
{
	$data = null;
		
	$url = 'https://pub.orcid.org/v2.1/' . $orcid_work;	
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


if (0)
{
	//$data = orcid_fetch('0000-0002-7573-096X');

	//$data = orcid_fetch('0000-0002-7941-346X');

	//$data = orcid_fetch('0000-0001-8916-5570');

	//$data = orcid_fetch('0000-0003-0566-372X');
	
	//$data = orcid_fetch('0000-0001-6238-2743');
	
	$data = orcid_fetch('0000-0002-9116-606X');
	
	$data = orcid_work_fetch('0000-0002-9116-606X/work/20499044');
	
	print_r($data);
	
	
}


?>
