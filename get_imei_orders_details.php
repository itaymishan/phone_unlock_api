<?php

 
require ('header.php');
include ('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON"); // we recommend json format (More information http://php.net/manual/en/book.json.php)
define('DHRUFUSION_URL', "url");
define("USERNAME", "rapidunlockers");
define("API_ACCESS_KEY", "YFX-CST-A1R-6P6-LUU-4KM-NSI-VQT");
$api = new DhruFusion();

// Debug on
$api->debug = true;


//$para['ID'] = '52366'; // got REFERENCEID from placeimeiorder
$para['IMEI'] = $_POST["IMEI"];
$request = $api->action('getimeiorder', $para);


//echo '<PRE>';
//print_r($request); 
//echo '</PRE>';
if($request['SUCCESS']['0']['CODE'] == "")
{
	echo "The Results Are Not In Yet, Please Try Again Later!!!! <br>";
}
else
{
	echo $request['SUCCESS']['0']['CODE'], "<br>";
}

?>
