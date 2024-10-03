<?php

function saveToJson($filePath, $data) {
  $existingData = [];
  if (file_exists($filePath)) {
    $existingData = json_decode(file_get_contents($filePath), true);
  }
  $existingData[] = $data; // Append new data to existing array
  file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
}


// Example usage
$newReading = [
  "id" => 3,
  "uv_index" => 7,
  "timestamp" => date("Y-m-d H:i:s"),
  "location" => "New York",
  "intensity" => "Very High"
];

saveToJson('data/uv_readings.json', $newReading);