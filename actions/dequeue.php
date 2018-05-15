<?php

// Dequeue some objects

require_once(dirname(dirname(__FILE__)) . '/queue/queue.php');

// Array to hold links we discover
$links = array();

while (!queue_is_empty())
{	
	$result = dequeue(100);
	
	$links = array_merge($links, $result);
}

// links we have found
print_r($links);

// For each additional link add it to the queue if we haven't already found it

foreach ($links as $url)
{
	echo $url . "\n";
	enqueue($url, false);
}

// Empty queue, ignoring any further links
while (!queue_is_empty())
{	
	dequeue(100);
}

?>
