<?php
  
  $apiKey = "YOUR_KEY";
  $addresses = array();
  
  require 'vendor/autoload.php';
  use GuzzleHttp\Client;
  
  $file = fopen("to-geocode.csv", "r");
  while (($line = fgetcsv($file)) !== FALSE) {
    $addresses[] = $line[0];
  }
  fclose($file);
  
  foreach ($addresses as $address) {
    sleep(1);
    $formattedAddress = str_replace(" ", "+", $address);
    geocodeAddress($address, $formattedAddress);
  }

  function geocodeAddress($address, $formattedAddress) {
    $client = new Client();
    $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json?address=$formattedAddress&key=$apiKey", ['future' => true]);
    $json = $response->json();
  
    if (!empty($json['results'][0]['geometry']['location'])) {
      $lat = $json['results'][0]['geometry']['location']['lat'];
      $lng = $json['results'][0]['geometry']['location']['lng'];
      print $address . "\t" . $lat . "\t" . $lng . "\n";
    }
    else {
      print $address . "\t" . "No Lat" . "\t" . "No Lng" . "\n";
    }
  }
