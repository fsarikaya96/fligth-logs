<?php

require_once('connect.php'); ?>
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
        i {
            transition: all 0.35s ease-in-out;
            display: inline-block !important;
            position: relative;
            z-index: 1;
            padding: 2em;
            margin: -2em;
        }

        i:hover {
            opacity: 0.6;
            transition: all 0.35s ease-in;
        }

        ul.pagination a {
            cursor: pointer;
        }

        a.disabled {
            pointer-events: none;
            opacity: 0.6;
            background: #d5d5d5;
            color: #000;
        }

        #notFound {
            position: absolute;
            width: 250px;
            margin-left: auto;
            margin-right: auto;
            left: 0;
            right: 0;
            text-align: center;
        }

        .loader {
            height: 4vh;
            width:  4vh;
            border: 2px solid black;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
            transform: scale(0.5);
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
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
<script>
    $(document).ready(function () {
        load_data();
        $('th').append('<i class="fa-solid fa-arrow-down-z-a float-end" title="Sırala"></i>');
        $('#table_id').DataTable({
            searching: false,
            // ordering: false,
        });

        // We get the data to be added in the html function
        function appendData(result) {
            $('#notFound').remove();
            $('.odd').remove();
            result.data.forEach(function (item) {
                let start_count = $('#table_id').find('td:first').text();
                $('#table_id_info').text("Showing " + start_count + " to " + result.page_count + " of " + result.total_count + " entries");

                // Convert DateTime Format
                const dateTimeSplit = item['scheduled_date'].split(' ');
                const dateSplit = dateTimeSplit[0].split('-');
                const currentDate = dateSplit[2] + '.' + dateSplit[1] + '.' + dateSplit[0];
                const currentTime = dateTimeSplit[1];
                // It means “Done” if the status is true, “Planned” otherwise.
                const status = (item['status'] == 0) ? "Planned" : "Done";
                $('tbody').append(
                    '<tr>' +
                    '<td data-id=' + item['id'] + '>' + item['id'] + '</td>' +
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

        // Panigate
        function load_data(page) {
            $('#table_id tbody tr').text("");
            $.ajax({
                url: 'airline.php',
                method: "post",
                data: {page: page},
                dataType: 'json',
                success: function (result) {
                    $('#table_id_paginate').html(result.pagination);
                    appendData(result);

                }
            });
        }

        // Panigate Buttons
        $(document).on('click', '#table_id_paginate a', function (e) {
            let id = $(this).attr('data-dt-idx');
            $("select").val($("select option:first").val());
            $('#table_id tbody tr').empty().remove();
            load_data(id);
        })

        // Fetch as much data as Count
        $(document).on('change', 'select', function () {
            let count = $("select option:selected").val();
            $.ajax({
                url: 'airline.php',
                type: 'POST',
                dataType: 'json',
                data: {count: count},
                success: function (result) {
                    appendData(result);
                    $('#table_id_paginate').html(result.pagination);
                }
            });
        });

        // Search ajax function()
        let timeoutID = null;
        function findMember(search) {
            // console.log('search: ' + search)
            $.ajax({
                url: 'airline.php',
                type: 'POST',
                dataType: 'json',
                data: {search: search},
                beforeSend: function () {
                    $('.search-input').append('<span class="loader"></span>');
                },
                success: function (result) {
                    setTimeout(function () {
                        $('.loader').remove();
                        $('#table_id tbody tr').remove();
                        appendData(result);
                    }, 200);
                }
            });
        }

        // Search ajax
        $('#search').keyup(function (e) {
            clearTimeout(timeoutID);
            const value = e.target.value
            timeoutID = setTimeout(() => findMember(value), 200);

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


    });
</script>
</html>
