<?php

function readJsonData($filepath)
{
  if (file_exists($filepath)) {
    $jsonData = file_get_contents($filepath);
    return json_decode($jsonData, true);
  }
  return [];
}
// Example usage:
//$data = readJsonData('data/uv_readings.json');


function saveToJson($filePath, $data)
{
  $existingData = [];
  if (file_exists($filePath)) {
    $existingData = json_decode(file_get_contents($filePath), true);
  }
  $existingData[] = $data; // Append new data to existing array
  file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
}
// Example usage
// $newReading = [
//   "id" => 3,
//   "uv_index" => 7,
//   "timestamp" => date("Y-m-d H:i:s"),
//   "location" => "New York",
//   "intensity" => "Very High"
// ];

// saveToJson('data/uv_readings.json', $newReading);


function formatDate($time) {
  // Create a Datetime object from the ISO 8601 string
  $date = new DateTime($time);

  // Format it as "2 Feb 2019"
  return $date->format('j M Y');
}

function formatTime($time) {
  // Create a Datetime object from the ISO 8601 string
  $date = new DateTime($time);

  // Format it as "2 Feb 2019"
  return $date->format('g: i A');
}