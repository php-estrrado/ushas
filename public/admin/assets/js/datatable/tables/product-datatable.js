$(document).ready(function () {

    $(".product-table tfoot th").each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" />');
    });

    var table = $(".product-table").DataTable({
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
        serverSide: true,
        bServerSide: false,
        search: {
            caseInsensitive: true,
            smart: true
        },
        ajax:{
            url: $('#baseurl').val()+"/admin/product/list",
            dataType: "json",
            type: "POST",
            data:{vType: 'ajax'},
        },
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "cat" },
            { data: "sub_cat" },
            { data: "created_at" },
            { data: "status" },
            { data: "statuss" },
            { data: "action" },
        ],
        orderMulti: false,
        dom: "Blfrtip",
        stateSave: true,
        // order: [[0, "asc"]],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        buttons: [
            {
                extend: "selectAll",
                text: '<i class="fa fa-check"></i>All',
                titleAttr: "Select All"
            },
            {
                extend: "selectNone",
                text: '<i class="fa fa-times"></i>None',
                titleAttr: "Deselect All"
            },
            {
                extend: "excelHtml5",
                text: '<i class="fa fa-file-excel-o"></i>Excel',
                title: "Products",
                titleAttr: "Export to Excel",
                filename: "Products",
                exportOptions: {
                columns: [1,2,3,4,6]
            }
            },
            {
                extend: "colvis",
                text: '<i class="fa fa-filter"></i>Filter',
                titleAttr: "Filter Columns",
                columns:[0,1,2,3,4,5,7]
            },
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
                targets: [0,5,6,7]
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
            emptyTable: "No product found",
            info: "Showing _START_ to _END_ of _TOTAL_ products",
            infoEmpty: "Showing 0 to 0 of 0 products",
            infoFiltered: "(filtered from _MAX_ total products)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Show _MENU_ products",
            loadingRecords: "Loading...",
            processing: "Processing...",
            search: "Search:",
            zeroRecords: "No matching product found",
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
    }).columns(6).visible(false);
//    table.columns().every(function () {
//        var that = this;
//
//    });

    $(".dt-bootstrap4 input[type=search]").attr("placeholder", "Search All");
    $(".action-search input, .search-by input").attr("disabled", "disabled");
    $(".action-search input").attr("placeholder", "");

    $("thead tr th:first-child, thead tr th:last-child").removeClass("sorting_asc");
    $("thead tr th:first-child, thead tr th:last-child").removeClass("sorting_desc");

});