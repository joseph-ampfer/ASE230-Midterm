<?php
include('scripts/read_data.php');
$data = readJsonData('data/uv_readings.json');
?>
<!DOCTYPE html>
<html>
<head>
    <title>UV Index Dashboard</title>
</head>
<body>
  <h1>UV Index Readings</h1>
  <table border="1">
      <tr>
          <th>ID</th>
          <th>UV Index</th>
          <th>Timestamp</th>
          <th>Location</th>
          <th>Intensity</th>
      </tr>
      <?php foreach ($data as $entry): ?>
          <tr>
              <td><?php echo $entry['id']; ?></td>
              <td><?php echo $entry['uv_index']; ?></td>
              <td><?php echo $entry['timestamp']; ?></td>
              <td><?php echo $entry['location']; ?></td>
              <td><?php echo $entry['intensity']; ?></td>
          </tr>
      <?php endforeach; ?>
  </table>

  <!-- Form for manual input, need to modify save_data.php to handle POST request from this form and store the data accordingly -->
  <form action="scripts/save_data.php" method="post">
      <label for="uv_index">UV Index:</label>
      <input type="number" id="uv_index" name="uv_index" required><br>
      <label for="location">Location:</label>
      <input type="text" id="location" name="location" required><br>
      <label for="intensity">Intensity:</label>
      <select id="intensity" name="intensity">
          <option value="Low">Low</option>
          <option value="Moderate">Moderate</option>
          <option value="High">High</option>
          <option value="Very High">Very High</option>
      </select><br>
      <input type="submit" value="Submit">
  </form>

</body>
</html>
