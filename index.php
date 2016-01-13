<?php		
     header("Access-Control-Allow-Origin: *");		 
		 if(isset($_GET['street'])){
			 		
					$addr=$_GET['street'];
					$city=$_GET['city'];
					$state=$_GET['state'];
					$degree=$_GET['degree'];
					if ($degree=="si")
					{
						$dsignal="&deg; C";
					}	
					//Here we start to get the data from geocode
					$g_url="http://maps.google.com/maps/api/geocode/xml?address=$addr,$city,$state";
					/**** Here starts parse the xml file ****/
					$g_xml=simplexml_load_file($g_url);
					$lat=$g_xml->result->geometry->location->lat;
					$lng=$g_xml->result->geometry->location->lng;
					
					/**** Generate request url for forecast.io ****/
					$f_url="https://api.forecast.io/forecast/26a2a2112f22f5f1a5bef7ea61369844/$lat,$lng?units=$degree&exclude=flags";
					$weather=file_get_contents($f_url);
					$obj=json_decode($weather);
					$daily=$obj->daily;
					$timez=$obj->timezone;
		date_default_timezone_set($timez);
					$rise=$daily->data[0]->sunriseTime;
					$set=$daily->data[0]->sunsetTime;
					$sunrise=date("h:i A",$rise);
					$sunset=date("h:i A",$set);
					$obj->daily->data[0]->sunriseTime=$sunrise;
					$obj->daily->data[0]->sunsetTime=$sunset;
					$weather=json_encode($obj);
					echo ($weather);
			 }		 
 ?> 