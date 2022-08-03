$(document).ready(function () {
    load_data();
    $('th').append('<i class="fa-solid fa-arrow-down-z-a float-end" title="Sırala"></i>');
    $('#table_id').DataTable({
        searching: false,
        ordering: false,
    });

    // We take the html elements to be added with ajax into the function
    function appendData(result) {
        $('.odd').remove();
        result.data.forEach(function (item) {
            // Show info under the table
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
        // console.log(search)
        $.ajax({
            url: 'airline.php',
            type: 'POST',
            dataType: 'json',
            data: {search: search},
            beforeSend: function () {
                $('.search-input').append('<span class="loader"></span>');
            },
            success: function (result) {
                $('#table_id tbody tr').text("");
                if ($.trim(result.data)) {
                    $('.empty-msg').remove();
                    $('.loader').remove();
                    $('#table_id tbody tr').remove();
                    appendData(result);
                } else {
                    $('.loader').remove();
                    $('#table_id tbody tr').remove();
                    $('#table_id tbody').append('<tr><td colspan="7" style="border-top:0;text-align: center; vertical-align: middle;">No matching records found</td></tr>');
                }
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
            let colFind = col + 1;
            $(this).closest('#table_id').find('td:nth-child(' + colFind + ')').addClass('highlighted');
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
            $(this).closest('#table_id').find('td:nth-child(' + colFind + ')').siblings().removeClass('highlighted');
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