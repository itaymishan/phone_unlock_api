<?php

 
require ('header.php');
include ('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON");
define('DHRUFUSION_URL', "url");
define("USERNAME", "username");
define("API_ACCESS_KEY", "token"); 
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
