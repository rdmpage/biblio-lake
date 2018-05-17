<?php

error_reporting(E_ALL);

$url = '';
if (isset($_GET['url']))
{
	$url = $_GET['url'];
}

if ($url != '')
{
	$data = file_get_contents($url);
  	header("Content-type: application/octet-stream");
  	//header("Content-disposition: attachment;filename=YOURFILE.pdf");

  	echo $data;
}

?>


