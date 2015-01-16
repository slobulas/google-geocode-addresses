<?php
  
  // This is your Google Maps API key.
  // You can setup and manage your keys on the Google Developers Console.
  // https://console.developers.google.com/project
  $apiKey = "YOUR_KEY";
  
  $addresses = array();
  
  require 'vendor/autoload.php';
  use GuzzleHttp\Client;
  
  // Change the fopen value to the name of your CSV with addresses in the first column.
  // Note that you should NOT include a column header.
  $file = fopen("to-geocode.csv", "r");
  while (($line = fgetcsv($file)) !== FALSE) {
    $addresses[] = $line[0];
  }
  fclose($file);
  
  // Loop through all of the address values, formatting them and geocoding.
  // We've added a second delay, using sleep(). Use usleep() if you want
  // a shorter delay.
  foreach ($addresses as $address) {
    sleep(1);
    $formattedAddress = str_replace(" ", "+", $address);
    geocodeAddress($address, $formattedAddress);
  }

  // Geocodes and prints the results. If you want an output file, just send
  // the script to a TSV file using the command line.
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
