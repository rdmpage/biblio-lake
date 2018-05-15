<?php

// Manage a queue of objects

// Queue is managed by views in CouchDB


require_once(dirname(dirname(__FILE__)) . '/documentstore/couchsimple.php');
require_once(dirname(dirname(__FILE__)) . '/resolvers/resolve.php');


//----------------------------------------------------------------------------------------
// Put an item in the queue , optionally force if already exists by deleting item
// and putting it back in the queue.
function enqueue($url, $force = false)
{
	global $config;
	global $couch;
	
	$go = true;
	
	// Normalise URLs to avoid needless duplication
	
	// DOIs should be https
	if (preg_match('/https?:\/\/(dx.)?doi.org\/(?<doi>.*)/', $url, $m))
	{
		$url = 'https://doi.org/' . $m['doi'];
	}
	
	// Check whether this URL already exists (have we done this object already?)
	// to do: what about having multiple URLs for same thing, check this
	$exists = $couch->exists($url);
	
	if ($exists)
	{
		echo "$url Exists\n";
		$go = false;
		
		if ($force)
		{
			echo "[forcing]\n";
			$couch->add_update_or_delete_document(null, $url, 'delete');
			$go = true;		
		}
	}

	if ($go)
	{
		$doc = new stdclass;
		
		// URL is document id and also source (i.e., we will resolve this URL to get details on object)
		$doc->_id = $url;	
		
		// By default message is empty and has timestamp set to "now"
		// This means it will be at the end of the queue of things to add
		$doc->{'message-timestamp'} = date("c", time());
		$doc->{'message-modified'} 	= $doc->{'message-timestamp'};
		$doc->{'message-format'} 	= 'unknown';
		
		$resp = $couch->send("PUT", "/" . $config['couchdb_options']['database'] . "/" . urlencode($doc->_id), json_encode($doc));
		var_dump($resp);
	}

}

//----------------------------------------------------------------------------------------
// True if queue is empty
function queue_is_empty()
{
	global $config;
	global $couch;
	
	$empty = false;
	
	$url = '_design/queue/_view/todo?limit=1';
		
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);
	$response_obj = json_decode($resp);

	if (!isset($response_obj->error))
	{
		$empty = ($response_obj->total_rows == 0);
	}
	
	return $empty;

}

//----------------------------------------------------------------------------------------
// Item is a single row from a CouchDB query
function fetch($item)
{
	global $config;
	global $couch;
	
	$links = array();
	
	// log
	echo "Resolving " . $item->value . "\n";
	//exit();
	
	$data = null;
	$data = resolve_url($item->value);
	
	//print_r($data);
	
	if (!$data)
	{
		echo " *** Failed to resolve " . $item->value . "\n";
	
		// No data means we failed to resolve this,
		// keep track of attempts to resolve so we can ignore them
		
		// update document store item with message content
		$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . urlencode($item->value));
		var_dump($resp);
		if ($resp)
		{
			$doc = json_decode($resp);
			if (!isset($doc->error))
			{
				if (isset($doc->{'message-attempts'}))
				{
					$doc->{'message-attempts'}++;
				}
				else
				{
					$doc->{'message-attempts'} = 1;
				}
				
				$resp = $couch->send("PUT", "/" . $config['couchdb_options']['database'] . "/" . urlencode($doc->_id), json_encode($doc));
				var_dump($resp);
			}
		}	
	}
	else
	{
		// if we have message content, update object with that message, which will remove it from the queue
		// Assuming we have set {'message-format'} to one of the MIME types recognised by the CouchDB
		// views, the object will also be indexed by the corresponding view
		if (isset($data->message))
		{
			
			if (isset($data->links))
			{	
				$links = $data->links;			
			}
			
			// update document store item with message content
			$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . urlencode($item->value));
			var_dump($resp);
			if ($resp)
			{
				$doc = json_decode($resp);
				if (!isset($doc->error))
				{
					$doc->{'message-modified'} = date("c", time());					
					$doc->{'message-format'} = $data->{'message-format'};
					$doc->{'message-source'} = $data->{'message-source'};
					
					$doc->message = $data->message;
					
					// To support clusering versions of the "same" work add a cluster_id and
					// initialise it with _id
					$doc->cluster_id = $doc->_id;
					
					$resp = $couch->send("PUT", "/" . $config['couchdb_options']['database'] . "/" . urlencode($doc->_id), json_encode($doc));
					var_dump($resp);
				}
			}	
		}		
	}
	
	return $links;
}

//----------------------------------------------------------------------------------------
// Dequeue one or more objects and fetch them
// 
// to do: if we get just one object, and that fails, we may end up with a queue that is 
// forever stuck, so maybe get a bunch of items, and resolve those.
//
// object may have links to other objects, we return a list of these, giving us the 
// option of adding these to the queue 
//
function dequeue($n = 5, $descending = false)
{
	global $config;
	global $couch;
	
	$links = array();
	
	$url = '_design/queue/_view/todo?limit=' . $n;
	
	if ($descending)
	{
		$url .= "&descending=true";
	}
	
	$resp = $couch->send("GET", "/" . $config['couchdb_options']['database'] . "/" . $url);
	$response_obj = json_decode($resp);

	print_r($response_obj);
		
	// fetch content
	$count = 0;
	foreach ($response_obj->rows as $row)
	{
		// Fetch object from source, add any links to it to our list
		$result = fetch($row);	
		
		if (count($result) > 0)
		{
			$links = array_merge($result);
		}
		
		// Give source a rest
		if (($count++ % 10) == 0)
		{
			$rand = rand(1000000, 3000000);
			echo '...sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
			usleep($rand);
		}
		
	}
	
	return $links;
		
}

//----------------------------------------------------------------------------------------
// Load one item directly into database without waiting for it to be dequeued
function load_url($url)
{
	// Ensure item is in the queue 
	enqueue($url);
	
	// simulate the result of a CouchDB query by creating an item that has
	// the URL to resolve as it's value
	$item = new stdclass;
	$item->value = $url;
	// fetch the item
	fetch($item);
}



?>
