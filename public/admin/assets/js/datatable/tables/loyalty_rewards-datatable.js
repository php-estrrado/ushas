$(document).ready(function () {

    var table = $(".loyalty_reward_list").DataTable({
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
		responsive: {
        details: {
            type: 'column',
            target: 'tr'
        }
    },
        search: {
            caseInsensitive: true,
            smart: true
        },
		
	ajax:{
            url: $('#listUrl').val(),
            dataType: "json",
            type: "POST",
            data:{vType: 'ajax'},
        },
        columns: [
            { data: "id" },
			{ data: "product" },
			{ data: "points_required" },
			{ data: "quantity" },
            { data: "created_at" },
			//{ data: "status" },
            { data: "action" }
			
        ],
        orderMulti: false,
        dom: "Blfrtip",
        stateSave: false,
        order: [[0, "asc"]],
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
                title: "MJS - Tags",
                titleAttr: "Export to Excel",
                filename: "MJS_Tags",
                exportOptions: {
                    columns: ":visible :not(.notexport)",
                    search: "applied",
                    order: "applied",
                    modifier: {
                        selected: true
                    }
                }
            },
            {
                extend: "colvis",
                text: '<i class="fa fa-filter"></i>Filter',
                titleAttr: "Filter Columns"
            },
//            {
//                extend: 'print',
//                footer: false
//            }  
        ],
        columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
		},
            {
                orderable: false,
                targets: [0,3]
            },
		{ "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4 ] }	
			],
        select: {
            style: "multi",
            selector: "td:first-child"
			
		
        },
        language: {
            decimal: "",
            emptyTable: "No labels found",
            info: "Showing _START_ to _END_ of _TOTAL_ labels",
            infoEmpty: "Showing 0 to 0 of 0 labels",
            infoFiltered: "(filtered from _MAX_ total labels)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Show _MENU_ labels",
            loadingRecords: "Loading...",
            processing: "Processing...",
            search: "Search:",
            zeroRecords: "No matching labels found",
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

    table.columns().every(function () {
        var that = this;

        // $("input", this.footer()).on("keyup change", function () {
        //     if (that.search() !== this.value) {
        //         that.search(this.value).draw();
        //     }
        // });
    });
//       jQuery("#filterSel").on( 'change', function () {
//                       table.column( 6 )
//                    .search( "^" + $(this).val(), true, false, true )
//                    .draw();
//                    
//
//            } );

    $(".dt-bootstrap4 input[type=search]").attr("placeholder", "Title");
    $(".action-search input, .search-by input").attr("disabled", "disabled");
    $(".action-search input").attr("placeholder", "");

    $("thead tr th:first-child, thead tr th:last-child").removeClass("sorting_asc");
    $("thead tr th:first-child, thead tr th:last-child").removeClass("sorting_desc");

});