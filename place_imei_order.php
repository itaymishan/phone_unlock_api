<?php

/**

 *	@author Dhru.com

 *	@APi kit version 2.0 March 01, 2012

 *	@Copyleft GPL 2001-2011, Dhru.com

 **/
require ('header.php');
include ('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON"); // we recommend json format (More information http://php.net/manual/en/book.json.php)
define('DHRUFUSION_URL', "http:///");
define("USERNAME", "rapidunlockers");
define("API_ACCESS_KEY", "YFX-CST-A1R-6P6-LUU-4KM-NSI-VQT");
$api = new DhruFusion();


// Debug on
$api->debug = true;


//$para['IMEI'] = "111111111111116";
//$para['ID'] = "13sss82"; // got from 'imeiservicelist' [SERVICEID]
$para['IMEI'] 	= $_POST["IMEI"];
$para['ID'] 	= $_POST["ID"];


// PARAMETRES IS REQUIRED
// $para['MODELID'] = "";
// $para['PROVIDERID'] = "";
// $para['MEP'] = "";
// $para['PIN'] = "";
// $para['KBH'] = "";
// $para['PRD'] = "";
// $para['TYPE'] = "";
// $para['REFERENCE'] = "";
// $para['LOCKS'] = "";


$request = $api->action('placeimeiorder', $para);


echo '<PRE>';
print_r($request);
echo '</PRE>';

?>
