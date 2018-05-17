<?php

error_reporting(E_ALL);

$url = '';
if (isset($_GET['url']))
{
	$url = $_GET['url'];
}

$callback = '';
if (isset($_GET['callback']))
{
	$callback = $_GET['callback'];
} 

$response = new stdclass;

if ($url != '')
{
	/*
	$data = file_get_contents($url);
  	header("Content-type: application/xml");

  	echo $data;
  	*/
  	
  	$data = file_get_contents($url);
  	if ($data != '')
  	{
	  	$response->xml = $data;
	}
 	
}

if ($callback != '')
{
	echo $callback . '(';
}

echo json_encode($response);

if ($callback != '')
{
	echo ')';
}


?>