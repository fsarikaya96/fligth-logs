<?php

require_once('connect.php');
/** @var TYPE_NAME $connect */
$limit = 10;
if (isset($_GET['count'])) {
    $limit = $_GET['count'];
}

$query = $connect->prepare(
    "SELECT flight_logs.*, SUM(COUNT(flight_logs.id)) OVER() AS total_count, captains.full_name FROM `flight_logs` 
          INNER JOIN captains ON captains.id = flight_logs.id GROUP BY flight_logs.id ORDER BY total_count;"
);
$query->execute();

$result = $query->fetchAll();
echo json_encode(['data' => $result,'limit' => $limit], JSON_PRETTY_PRINT);
