<?php

//calcul la distance
function getvalueDistance($p1long, $p1lat, $p2long, $p2lat) {
        $pointFirst = $p1long.",".$p1lat;
        $pointSecond = $p2long.",".$p2lat;

        $aContext = array(
        'http' => array(
                        'proxy' => 'tcp://192.168.1.1:3128',
                        'request_fulluri' => true,
                ),
        );
        $cxContext = stream_context_create($aContext);

        $url = 'https://maps.googleapis.com/maps/api/distancematrix/xml?language=fr&units=meter&origins='.$pointFirst.'&destinations='.$pointSecond;
        $dataBrut = file_get_contents($url, False, $cxContext);
        $data = new SimpleXMLElement($dataBrut);

        return $data->row->element->distance->value[0];
}



//calcul la disatance sous un format text "100 km" 
function getDistance($p1long, $p1lat, $p2long, $p2lat) {
        $pointFirst = $p1long.",".$p1lat;
        $pointSecond = $p2long.",".$p2lat;

        $aContext = array(
        'http' => array(
                        'proxy' => 'tcp://192.168.1.1:3128',
                        'request_fulluri' => true,
                ),
        );
        $cxContext = stream_context_create($aContext);
	
        $url = 'https://maps.googleapis.com/maps/api/distancematrix/xml?language
	=fr&units=meter&origins='.$pointFirst.'&destinations='.$pointSecond;
        $dataBrut = file_get_contents($url, False, $cxContext);
        $data = new SimpleXMLElement($dataBrut);

        return $data->row->element->distance->text[0];
}
function getTemps($p1long, $p1lat, $p2long, $p2lat) {
        $pointFirst = $p1long.",".$p1lat;
        $pointSecond = $p2long.",".$p2lat;

        $aContext = array(
        'http' => array(
                        'proxy' => 'tcp://192.168.1.1:3128',
                        'request_fulluri' => true,
                ),
        );
        $cxContext = stream_context_create($aContext);
		  $type = "driving";
        $url = 'https://maps.googleapis.com/maps/api/distancematrix/xml?language=fr&mode='.$type.'&units=meter&origins='.$pointFirst.'&destinations='.$pointSecond.'&key=AIzaSyCcEzkBF9wNDJCA3RJo7BUcntR8OnFzQh8';
        $dataBrut = file_get_contents($url, False, $cxContext);
        $data = new SimpleXMLElement($dataBrut);

        return $data->row->element->duration->text[0];
}
?>
<!DOCTYPE html>
<body>

<?php
    
    	try
            {
            $bdd = new PDO('mysql:host=localhost;dbname=covoiturage;' , 'root', 'mysql');
            }
            catch(Exception $e)
            {
            die('Erreur : ' . $e->getMessage());
            }
            
		$reponse1 = $bdd->query('SELECT longi, lati FROM gps WHERE id=1');
		$reponse2 = $bdd->query('SELECT longi, lati FROM gps WHERE id=2');
		
		while ($donnees = $reponse1->fetch())

		{
			$long1 = $donnees['longi'];
		echo "<br>longitude ".$donnees['longi'];
		$lat1 = $donnees['lati'];
		echo "<br>latitude ".$donnees['lati'];
		}
		
		while ($donnees2 = $reponse2->fetch())

		{
			$long2 = $donnees2['longi'];
		echo "<br>longitude ".$donnees2['longi'];
		$lat2 = $donnees2['lati'];
		echo "<br>latitude ".$donnees2['lati'];
		}
		
		
      echo "<br>latitidue 1 : ".$lat1;
      echo "<br>longitude 1 : ".$long1;
      echo "<br>latitidue 2 : ".$lat2;
      echo "<br>longitude 2 : ".$long2;
      
      $distance = getDistance($lat1, $long1, $lat2, $long2);
      echo "<br>".$distance;
		$temps = getTemps($lat1, $long1, $lat2, $long2);
		echo "<br>".$temps;
		$valuedistance = getvalueDistance($lat1, $long1, $lat2, $long2);
		echo "<br>".$valuedistance;
		
		if($valuedistance <= 100){
			//commande affiche le temps et la distance		
			//exec("".$value)		
			echo "<br>valeur =< 100 km <br> distance = ".$distance;
			//commande qui affiche les pubs 		
			exec("/.PUB");
			sleep(30);
			exec("/.PUB2");
			sleep(30);
			exec("/.PUB3");		
		}
		else
		{
			exec("/.PUB");
			sleep(30);
			exec("/.PUB2");
			sleep(30);
			exec("/.PUB3");
		}
?>
</body>
</html>

