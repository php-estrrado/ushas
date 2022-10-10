var minVal, maxVal;

$(document).ready(function () {

    $(".ticket tfoot th").each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" />');
    });

    var table = $(".ticket").DataTable({
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
                title: "Kangtao - Tickets",
                titleAttr: "Export to Excel",
                filename: "Kangtao_tickets",
                exportOptions: {
                    
                    search: "applied",
                    order: "applied",
                    columns: [  1,3,4,6],
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
         
       
        select: {
            style: "multi",
            selector: "td:first-child"
        },
        language: {
            decimal: "",
            emptyTable: "No Ticket found",
            info: "Showing _START_ to _END_ of _TOTAL_ Tickets",
            infoEmpty: "Showing 0 to 0 of 0 Tickets",
            infoFiltered: "(filtered from _MAX_ total Tickets)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Show _MENU_ Tickets",
            loadingRecords: "Loading...",
            processing: "Processing...",
            search: "Search:",
            zeroRecords: "No matching Tickets found",
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

table.column(3).visible(false);

        $('body').on('change','#filterSel',function(){ 
           console.log("innn");
            minVal = $(this).find(':selected').attr('data-min');
 maxVal = $(this).find(':selected').attr('data-max');
                table.draw();




            } );


$.fn.dataTable.ext.search.push(
function( settings, data, dataIndex ) {

var rating = data[2].trim();
if (( minVal <= rating   && rating <= maxVal )) {
return true;
}
return false;
}
);
          

// $("#viewfilter").click(function(){
// table.draw();
// } );


    $(".dt-bootstrap4 input[type=search]").attr("placeholder", "Search All");
    $(".action-search input, .search-by input").attr("disabled", "disabled");
    $(".action-search input").attr("placeholder", "");

    $("thead tr th:first-child, thead tr th:last-child").removeClass("sorting_asc");
    $("thead tr th:first-child, thead tr th:last-child").removeClass("sorting_desc");
     $('#ticket td').css('white-space','initial');
});