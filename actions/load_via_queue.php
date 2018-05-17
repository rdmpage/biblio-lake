<?php

// Load some data

require_once(dirname(dirname(__FILE__)) . '/queue/queue.php');

$urls = array('https://doi.org/10.13128/Acta_Herpetol-13269');

// Trivalvaria (nice iSpecies example)
$urls = array(
'https://doi.org/10.3897/phytokeys.94.21553',
'https://doi.org/10.1111/j.1756-1051.1997.tb00306.x', // citations
'https://doi.org/10.1021/np50070a013',
'https://doi.org/10.2305/iucn.uk.1998.rlts.t31725a9655727.en'
);

$urls=array('https://orcid.org/0000-0001-6238-2743');

// Cyril Nelson, lots of works with limited metadata :(
$urls=array('https://orcid.org/0000-0003-3941-5628');

$urls=array('https://orcid.org/0000-0002-0008-1061');

// Medra
$urls=array('https://doi.org/10.12905/0380.sydowia66(1)2014-0099');


// Lots of authors
// 10.1590/2175-7860201566411 
// 10.3389/fpls.2011.00034

$urls = array(
'https://doi.org/10.3897/phytokeys.94.21553',
'https://doi.org/10.1111/j.1756-1051.1997.tb00306.x', // citations
'https://doi.org/10.1021/np50070a013',
'https://doi.org/10.2305/iucn.uk.1998.rlts.t31725a9655727.en',
'https://doi.org/10.12905/0380.sydowia66(1)2014-0099',
'https://orcid.org/0000-0002-0008-1061',
'https://doi.org/10.13128/Acta_Herpetol-13269'
);

$urls = array(
'https://doi.org/10.11646/phytotaxa.305.1.8',
'https://doi.org/10.12705/662.12',
'https://doi.org/10.11646/phytotaxa.306.2.4',
'https://doi.org/10.1093/botlinnean/box007',
'https://doi.org/10.2985/015.089.0201',
'https://doi.org/10.14258/turczaninowia.20.1.15',
'https://doi.org/10.11646/phytotaxa.306.2.8',
'https://doi.org/10.1600/036364417X694548',
'https://doi.org/10.5735/085.054.0302',
'https://doi.org/10.11646/phytotaxa.299.1.7',
'https://doi.org/10.11646/phytotaxa.303.1.2',
'https://doi.org/10.11646/phytotaxa.298.3.8',
'https://doi.org/10.11646/phytotaxa.308.2.5',
'https://doi.org/10.1600/036364417X694917'
);

$urls = array('https://doi.org/10.1007/s13225-016-0366-9');

$urls = array(
'https://doi.org/10.1080/01916122.2016.1146174',
'https://doi.org/10.5943/mycosphere/8/7/3',
'https://doi.org/10.1371/journal.pone.0178050',
'https://doi.org/10.7872/crym/v38.iss1.2017.101',
'https://doi.org/10.1007/s11557-016-1264-y'
);

$urls = array('https://orcid.org/0000-0003-3628-2567');

$urls = array('https://doi.org/10.6165/tai.1986.31.89');

// Example of duplicate documents from different sources
$urls = array(
'https://orcid.org/0000-0003-3628-2567/work/21039657', // in ORCID profile
'https://doi.org/10.6165/tai.2009.54(2).159' // from DOI
);

// Oreomyrrhis borneensis
$urls=array(
// https://orcid.org/0000-0003-3628-2567 Kuo-Fang Chung lists this work with no DOI
// and Phytotaxa isn't aware of the ORCID for the author
'http://dx.doi.org/10.11646/phytotaxa.142.1.7',
 
// also has a JSTOR version, cited without DOI in ref above
// https://www.jstor.org/stable/2435163
'https://doi.org/10.1002/j.1537-2197.1918.tb05518.x'

);

// Zoobank
$urls = array('urn:lsid:zoobank.org:pub:F1DE2C0F-1C90-468E-856B-4A0BCEC56A07');

// deep water barnacle
$urls=array(
'https://doi.org/10.11646/zootaxa.3745.5.4',
'https://doi.org/10.1080/00288330.2000.9516944',
'https://doi.org/10.1007/BF03043086',
'https://doi.org/10.11646/zootaxa.4407.1.8',
'https://doi.org/10.1017/s0025315409000459'
);

$urls=array('https://orcid.org/0000-0003-3477-3047');

$urls=array('http://dx.doi.org/10.11646/phytotaxa.142.1.7');

// ZooBank and same paper from CrossRef
$urls = array(
'urn:lsid:zoobank.org:pub:F1DE2C0F-1C90-468E-856B-4A0BCEC56A07',
'https://doi.org/10.3897/zookeys.480.9046'
);

// ORCID work and CrossRef
$urls = array(
'http://doi.org/10.3897/phytokeys.94.21337',
'https://orcid.org/0000-0001-6238-2743/work/41066436'
);

// Oreomyrrhis borneensis
$urls=array(
'https://orcid.org/0000-0003-3628-2567', // Kuo-Fang Chung lists this work with no DOI
// and Phytotaxa isn't aware of the ORCID for the author
'http://dx.doi.org/10.11646/phytotaxa.142.1.7',
 
// also has a JSTOR version, cited without DOI in ref above
// https://www.jstor.org/stable/2435163
'https://doi.org/10.1002/j.1537-2197.1918.tb05518.x'

);

$urls=array(
'https://doi.org/10.1600/036364414x681437',
'https://doi.org/10.1186/s40529-015-0115-5',
'https://doi.org/10.1186/s40529-017-0207-5'
);

$urls=array(
'https://doi.org/10.1186/1999-3110-55-1'
);

$urls=array(
'https://doi.org/10.5169/seals-88720'
);

$urls=array(
'http://dx.doi.org/10.1080/00222933608655229',
'http://dx.doi.org/10.1080/00222933608655218',
'http://dx.doi.org/10.1080/00222933608655211',
'http://dx.doi.org/10.1080/00222933608655202'
);

$urls=array(
'https://doi.org/10.3406/linly.1926.14688',
'https://doi.org/10.3406/linly.1925.14667',
'https://doi.org/10.3406/linly.1923.14611',
'https://doi.org/10.3406/linly.1922.14592'
);

// Cites linly article(s) above but without DOI
$urls = array(
'https://doi.org/10.1080/00305316.1988.11835496'
);

// Lots of references, hardly any have DOIs, hence CrossRef metadata is weak
// TF site needs cookies to screen scrape HTML for references
$urls = array(
'https://doi.org/10.1080/00305316.2011.648904'
);

// Example of duplicate documents from different sources
$urls = array(
'https://orcid.org/0000-0003-3628-2567/work/21039657', // in ORCID profile
'https://doi.org/10.6165/tai.2009.54(2).159' // from DOI
);

//Ann. Mag. Nat. Hist., (10) 19
$urls = array(
'https://doi.org/10.1080/00222933708655256' 
);

$urls = array(
'https://doi.org/10.3897/phytokeys.94.21553',
'https://doi.org/10.1111/j.1756-1051.1997.tb00306.x', // citations
'https://doi.org/10.1021/np50070a013',
'https://doi.org/10.2305/iucn.uk.1998.rlts.t31725a9655727.en'
);


$urls = array(
'https://doi.org/10.1590/1809-43921981111049',
'https://doi.org/10.2307/25065859',
'https://doi.org/10.3767/000651911x588844',
'https://doi.org/10.7717/peerj.2402'
);

// Towards holomorphology in entomology: rapid and cost-effective adult-larva matching using NGS barcodes
// lots of references
$urls = array(
'https://doi.org/10.1111/syen.12296'
);

// Revalidation and redescription of Potamon elbursi Pretzmann, 1976 (Brachyura, Potamidae) from Iran, based on morphology and genetics
// https://www.ncbi.nlm.nih.gov/nuccore/KF227382
$urls = array('https://doi.org/10.2478/s11535-013-0203-z');

// Exploring Genetic Divergence in a Species-Rich Insect Genus Using 2790 DNA Barcodes
// PMID, PMC, data in BOLD http://dx.doi.org/10.5883/DS-TABAC
$urls = array('https://dx.doi.org/10.1371/journal.pone.0138993');

$urls = array('https://doi.org/10.3897/zookeys.574.6129');

$urls = array('https://doi.org/10.1080/00379271.2018.1435306');

$urls = array(
'https://doi.org/10.3406/linly.1922.14602',
'https://doi.org/10.3406/linly.1923.14612'
);

//----------------------------------------------------------------------------------------
// Add items to the queue

$force = false;
$force = true;


foreach ($urls as $url)
{
	echo $url . "\n";
	enqueue($url, $force);
}
	
//----------------------------------------------------------------------------------------
// Resolve items

// Do this twice, the first time dequeue items in our queue, as we do so we
// build a list of things linked to those items. We store those and can then enqueue them,
// but this time ignoring the links. This enables us to crawl the web of objects one level deep,
// adding records but not ending up crawling the whole web!

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