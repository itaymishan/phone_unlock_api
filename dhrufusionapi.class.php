<?php

if (!extension_loaded('curl'))
{
    trigger_error('cURL extension not installed', E_USER_ERROR);
}

class DhruFusion
{
    var $xmlData;
    var $xmlResult;
    var $debug;
    var $action;
    function __construct()
    {
        $this->xmlData = new DOMDocument();
    }
    function getResult()
    {
        return $this->xmlResult;
    }
    function action($action, $arr = array())
    {	
		if($action == "getimeiorder")
		{
			$orderIMEI = $arr['IMEI'];
			$arr['ID'] = GetOrderNumberByIMEI($arr['IMEI']);
		}
		
        if (is_string($action))
        {
            if (is_array($arr))
            {
                if (count($arr))
                {
                    $request = $this->xmlData->createElement("PARAMETERS");
                    $this->xmlData->appendChild($request);
                    foreach ($arr as $key => $val)
                    {
                        $key = strtoupper($key);
                        $request->appendChild($this->xmlData->createElement($key, $val));
                    }
                }
                $posted = array(
                    'username' => USERNAME,
                    'apiaccesskey' => API_ACCESS_KEY,
                    'action' => $action,
                    'requestformat' => REQUESTFORMAT,
                    'parameters' => $this->xmlData->saveHTML());
                $crul = curl_init();
                curl_setopt($crul, CURLOPT_HEADER, false);
                curl_setopt($crul, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                //curl_setopt($crul, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($crul, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($crul, CURLOPT_URL, DHRUFUSION_URL.'/api/index.php');
                curl_setopt($crul, CURLOPT_POST, true);
                curl_setopt($crul, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($crul, CURLOPT_POSTFIELDS, $posted);
                $response = curl_exec($crul);
                if (curl_errno($crul) != CURLE_OK)
                {
                    echo curl_error($crul);
                    curl_close($crul);
                }
                else
                {
                    curl_close($crul);
					// if new IMEI Order insert to DB
					$jsonResponse = json_decode($response, true);
					if($action == "placeimeiorder")
                    {
						echo "<br>Action: ", $action;
						InsertToDB($jsonResponse); 
                    }
					
                    return (json_decode($response, true));
                }
            }
        }
        return false;
    }
}

function InsertToDB($res)
{
    $con=mysqli_connect("Localhost","rapidunl_admin","itaym777","rapidunl_orderSystem");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	else
	{
		echo "<br>Connected to MySQL!!!!!!!!<br>";
        $IMEI    	=  $res['IMEI'];
        $refID      =  $res['SUCCESS'][0]['REFERENCEID'];
		mysqli_query($con,"INSERT INTO CheckCarrierTbl(OrderID, IMEI)
		VALUES ('$refID', '$IMEI')");
		echo "IMEI:",$IMEI, "<br>";
		echo "refID:",$refID, "<br>";	
	}
}

function GetOrderNumberByIMEI($imei_num)
{
    $con=mysqli_connect("Localhost","rapidunl_admin","itaym777","rapidunl_orderSystem");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	else
	{        
		$result = mysqli_query($con,"SELECT * FROM CheckCarrierTbl WHERE IMEI = '$imei_num'");	
		$row = mysqli_fetch_array($result);
	}
    return $row['OrderID'];
}


function XMLtoARRAY($rawxml)
{
    $xml_parser = xml_parser_create();
    xml_parse_into_struct($xml_parser, $rawxml, $vals, $index);
    xml_parser_free($xml_parser);
    $params = array();
    $level = array();
    $alreadyused = array();
    $x = 0;
    foreach ($vals as $xml_elem)
    {
        if ($xml_elem['type'] == 'open')
        {
            if (in_array($xml_elem['tag'], $alreadyused))
            {
                ++$x;
                $xml_elem['tag'] = $xml_elem['tag'].$x;
            }
            $level[$xml_elem['level']] = $xml_elem['tag'];
            $alreadyused[] = $xml_elem['tag'];
        }
        if ($xml_elem['type'] == 'complete')
        {
            $start_level = 1;
            $php_stmt = '$params';
            while ($start_level < $xml_elem['level'])
            {
                $php_stmt .= '[$level['.$start_level.']]';
                ++$start_level;
            }
            $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
            eval($php_stmt);
            continue;
        }
    }
    return $params;
}

?>



