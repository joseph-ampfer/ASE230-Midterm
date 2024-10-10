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
$data = readJsonData('data/uv_readings.json');