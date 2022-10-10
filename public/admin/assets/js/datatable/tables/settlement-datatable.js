$(document).ready(function () {

    $(".settlement-table tfoot th").each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" />');
    });

    var table = $(".settlement-table").DataTable({
        pageLength: 10,
        rowReorder: false,
        colReorder: true,
        paging: true,
        pagingType: "simple_numbers",
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        fixedHeader: true,
        orderCellsTop: false,
        keys: false,
        responsive: true,
        processing: true,
        scrollX: false,
        scrollCollapse: true,
        serverSide: false,
        search: {
            caseInsensitive: true,
            smart: true
        },
        orderMulti: false,
        dom: "Blfrtip",
        stateSave: true,
        order: [[0, "asc"]],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        buttons: [
//            {
//                extend: "selectAll",
//                text: '<i class="fa fa-check"></i>All',
//                titleAttr: "Select All"
//            },
//            {
//                extend: "selectNone",
//                text: '<i class="fa fa-times"></i>None',
//                titleAttr: "Deselect All"
//            },
//            
//            {
//                extend: "excelHtml5",
//                text: '<i class="fa fa-file-excel-o"></i>Excel',
//                title: "Kangtao - Settlement",
//                titleAttr: "Export to Excel",
//                filename: "Kangtao_Settlements",
//                exportOptions: {
//                    columns: ":visible :not(.notexport)",
//                    search: "applied",
//                    order: "applied",
//                    modifier: {
//                        selected: true
//                    }
//                }
//            },
//            {
//                extend: "colvis",
//                text: '<i class="fa fa-filter"></i>Filter',
//                titleAttr: "Filter Columns"
//            },
//            {
//                extend: 'print',
//                footer: false
//            }  
        ],
        columnDefs: [
            {
                className: "select-checkbox",
                targets: 0
            },
            {
                orderable: false,
                targets: [0]
            },
            {width: "7%", targets: 0},
            {width: "7%", targets: 1}
        ],
        select: {
            style: "multi",
            selector: "td:first-child"
        },
        language: {
            decimal: "",
            emptyTable: "No settlements found",
            info: "Showing _START_ to _END_ of _TOTAL_ settlements",
            infoEmpty: "Showing 0 to 0 of 0 settlements",
            infoFiltered: "(filtered from _MAX_ total settlements)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Show _MENU_ settlements",
            loadingRecords: "Loading...",
            processing: "Processing...",
            search: "Search:",
            zeroRecords: "No matching settlements found",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            },
            aria: {
                sortAscending: ": activate to sort column ascending",
                sortDescending: ": activate to sort column descending"
            },
            buttons: {
                copyTitle: 'Copied to clipboard',
                copySuccess: {
                    _: "%d rows copied",
                    1: "1 row copied"
                }
            }
        }
    });
    $(".dt-bootstrap4 input[type=search]").attr("placeholder", "Search All");
    $(".action-search input, .search-by input").attr("disabled", "disabled");
    $(".action-search input").attr("placeholder", "");

    $("thead tr th:first-child, thead tr th:last-child").removeClass("sorting_asc");
    $("thead tr th:first-child, thead tr th:last-child").removeClass("sorting_desc");

});