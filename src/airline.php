<?php

require_once('connect.php');
/** @var TYPE_NAME $connect */

$search = $_POST['search'] ?? '';
$limit = $_POST['count'] ?? 10;

$page = $_POST['page'] ?? 1;

$query = $connect->prepare(
    "SELECT flight_logs.*, captains.full_name FROM `flight_logs` INNER JOIN captains ON captains.id = flight_logs.id"
);

$query->execute();
$result = $query->fetchAll();

$count = $query->rowCount();
$total_pages = ceil($count / $limit);

$pagination = "<span> <a class='paginate_button " . ($page <= 1 ? 'disabled' : '') . "'  data-dt-idx='" . ($page - 1) . "'>Previous</a>";
for ($i = 1; $i <= $total_pages; $i++) {
    $pagination .= "<a class='paginate_button " . ($page == $i ? 'current' : '') . "' data-dt-idx='$i'>$i</a>";
}
$pagination .= "<a class='paginate_button " . ($page >= $total_pages ? 'disabled' : '') . "' data-dt-idx='" . ($page + 1) . "'>Next</a> </span>";
$offset = ($page - 1) * $limit;
$query = $connect->prepare(
    "SELECT flight_logs.*, captains.full_name FROM `flight_logs` 
                                  INNER JOIN captains ON captains.id = flight_logs.id 
                                         WHERE (code LIKE '%$search%') OR (flight_logs.id LIKE '%$search%') OR (scheduled_date LIKE  '%$search%')
                                         OR (origin LIKE  '%$search%') OR (destination LIKE  '%$search%')
                                         OR (captains.full_name LIKE '%$search%') OR IF(status, 'done', 'planned') LIKE '%$search%'
                                         LIMIT $offset,$limit"
);
$query->execute();
$result = $query->fetchAll();
$pageCount = $query->rowCount();
if ($page != 1) {
    $pageCount += $query->rowCount();
}
if ($page >= $total_pages) {
    $pageCount = $count;
}

echo json_encode(['data' => $result, 'pagination' => $pagination, 'page_count' => $pageCount, 'total_count' => $count],
    JSON_PRETTY_PRINT);

