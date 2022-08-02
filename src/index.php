<?php require_once('connect.php'); ?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <style>
        i{
            transition: all 0.35s ease-in-out;
        }
        i:hover{
            opacity: 0.6;
            transition: all 0.35s ease-in;
        }
    </style>
    <title>Document</title>


</head>
<body>
<div class="container py-2 mt-2">
    <div class="col-md-2 float-end">
        <span class="alert alert-danger position-relative" style="top:35px; right: 175px;" id="notFound">Kayıt Bulunamadı..</span>
        <label for="search"></label>
        <input type="text" id="search" class="form-control" name="search" placeholder="">

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
<script>
    $(document).ready(function () {

        $('th').append('<i class="fa-solid fa-arrow-down-z-a float-end" title="Sırala" style="display: inline-block !important; position: relative; z-index: 1; padding: 2em;margin: -2em;"></i>');
        $('#table_id').DataTable({
            searching: false,
            ordering: false,
        });
        // We get the data to be added in the html function
        function appendData(result) {
            $('.odd').remove();
            result.data.forEach(function (item) {

                $('#table_id_info').text("Showing 0 to 0 of " + item['total_count'] +" entries");
                // Convert DateTime Format
                const dateTimeSplit = item['scheduled_date'].split(' ');
                const dateSplit = dateTimeSplit[0].split('-');
                const currentDate = dateSplit[2] + '.' + dateSplit[1] + '.' + dateSplit[0];
                const currentTime = dateTimeSplit[1];
                // It means “Done” if the status is true, “Planned” otherwise.
                const status = (item['status'] == 0) ? "Planned" : "Done";
                $('tbody').append(
                    '<tr>' +
                    '<td>' + item['id'] + '</td>' +
                    '<td>' + item['code'] + '</td>' +
                    '<td>' + currentDate + ' ' + currentTime + '</td>' +
                    '<td>' + item['origin'] + '</td>' +
                    '<td>' + item['destination'] + '</td>' +
                    '<td>' + item['full_name'] + '</td>' +
                    '<td>' + status + '</td>' +
                    '</tr>'
                );
            });
        }

        // Fetch the data with ajax
        $.ajax({
            url: 'airline.php',
            dataType: 'json',
            type: 'GET',
            success: function (result) {
                appendData(result);
            }
        });

        // Fetch as much data as Count
        /*
        $(document).on('change', 'select', function () {
            let count = $("select option:selected").val();
            $.ajax({
                url: 'airline.php',
                type: 'GET',
                dataType: 'json',
                data: {count: count},
                success: function (result) {
                    appendData(result);
                }
            });
        });
        */
        // Search Input
        let notFound = $("#notFound");
        notFound.hide();
        $("#search").on("keyup", function() {
            $('.odd').remove();
            const value = $(this).val().toLowerCase()

            const allItems = $("#table_id tbody tr");

            const matchedItems = $(allItems).filter(function() {
                return $(this).text().toLowerCase().indexOf(value) > -1
            });

            allItems.toggle(false)

            matchedItems.toggle(true)

            if (matchedItems.length === 0) {
                notFound.show();

            }
            else {
                notFound.hide();
            }
        });

        // Sort Table
        $('th i').each(function (col) {
            $(this).hover(
                function () {
                    $(this).addClass('focus');
                },
                function () {
                    $(this).removeClass('focus');
                }
            );
            $(this).click(function () {
                let sortOrder;
                if ($(this).is('.asc')) {
                    $(this).removeClass('asc');
                    $(this).addClass('desc selected');
                    sortOrder = -1;
                } else {
                    $(this).addClass('asc selected');
                    $(this).removeClass('desc');
                    sortOrder = 1;
                }
                $(this).siblings().removeClass('asc selected');
                $(this).siblings().removeClass('desc selected');
                const arrData = $('table').find('tbody >tr:has(td)').get();
                arrData.sort(function (a, b) {
                    const val1 = $(a).children('td').eq(col).text().toUpperCase();
                    const val2 = $(b).children('td').eq(col).text().toUpperCase();

                    if ($.isNumeric(val1) && $.isNumeric(val2))
                        return sortOrder === 1 ? val1 - val2 : val2 - val1;
                    else
                        return (val1 < val2) ? -sortOrder : (val1 > val2) ? sortOrder : 0;
                });
                $.each(arrData, function (index, row) {
                    $('tbody').append(row);
                    $('.odd').remove();
                });
            });
        });

        // Paginate
        $.ajax({
           url:'paginate.php',
           type:'GET',
           dataType:'json',
            success:function (result) {
                console.log(result);
            }
        });
    });
</script>
</html>
