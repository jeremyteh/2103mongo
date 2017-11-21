<?php
$datamallKey = 'SFHPvNC5RP+jFTzftMxxFQ==';
$carparkLotsJson = "http://datamall2.mytransport.sg/ltaodataservice/CarParkAvailability";

$ch      = curl_init($carparkLotsJson);
$options = array(
    CURLOPT_HTTPHEADER     => array( "AccountKey: ". $datamallKey . ", Accept: application/json" ),
);
curl_setopt_array($ch, $options);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$carparkJsonResult = curl_exec($ch);
echo $carparkJsonResult;
