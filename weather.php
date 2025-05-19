<!-- sql_php_pro/weather.php -->

<?php
$apiKey = "1d5bdffb70316b69a64d186951994564";
$city = "Delhi"; 
$url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

$response = file_get_contents($url);
$data = json_decode($response, true);

$weather = $data['weather'][0]['description'];
$temp = $data['main']['temp'];
$icon = $data['weather'][0]['icon'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Weather in <?php echo $city; ?></title>
</head>
<body>
  <h2>Current Weather in <?php echo $city; ?></h2>
  <p><strong>Condition:</strong> <?php echo ucfirst($weather); ?></p>
  <p><strong>Temperature:</strong> <?php echo $temp; ?>°C</p>
  <img src="https://openweathermap.org/img/wn/<?php echo $icon; ?>@2x.png" alt="Weather icon">
  <br><br>
  <a href="index.php">Back to Dashboard</a>
</body>
</html>
