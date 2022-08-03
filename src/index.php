<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="resources/app.css">
    <title>Flight Logs</title>
</head>
<body>

<div class="container py-2 mt-2">
    <div class="row float-end search-input">
        <label for="search" class="col-2 col-form-label col-form-label-sm">Search:</label>
        <div class="col-md-8">
            <input type="text" id="search" class="form-control" name="search">
        </div>
    </div>
    <table id="table_id" class="display">
        <thead>
        <tr>
            <th>Flight Log Id</th>
            <th>Airline Code</th>
            <th>Scheduled</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Captain</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.js" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="resources/app.js"></script>

</html>
