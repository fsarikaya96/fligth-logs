<?php

require_once('connect.php');
/** @var TYPE_NAME $connect */
/*
$limit = 10;
if (isset($_GET['count'])) {
    $limit = $_GET['count'];
}
*/
$page = $_POST['page'] ?? 1;
$limit = 5;
$query = $connect->prepare(
    "SELECT flight_logs.*, captains.full_name FROM `flight_logs` INNER JOIN captains ON captains.id = flight_logs.id "
);
$query->execute();
$result = $query->fetchAll();
$total_data =  $query->rowCount();
$total_pages = ceil($total_data / $limit);
$pagination = '';

$pagination = "<li class='page-item'><a class='page-link ".($page <= 1 ? 'disabled' : ' ')."' data-id='".($page-1)."' class='button'>Previous</a></li>";

for ($i =1; $i <= $total_pages; $i++)
{
    $pagination.="
        <li class='page-item'>
          <a class='page-link' data-id='$i'>$i</a>
        </li>
        ";
}
$pagination .= "<li class='page-item'><a class='page-link ".($page >= $total_pages ? 'disabled' : ' ')."' data-id='".($page+1)."' class='button'>Next</a></li>";
$offset = ($page - 1) * $limit;
$query = $connect->prepare("SELECT flight_logs.*, captains.full_name FROM `flight_logs` 
                                  INNER JOIN captains ON captains.id = flight_logs.id LIMIT $offset,$limit");
$query->execute();
$result = $query->fetchAll();
echo json_encode(['data' => $result,'pagination' => $pagination], JSON_PRETTY_PRINT);

