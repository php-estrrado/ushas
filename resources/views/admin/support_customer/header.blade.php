
<style> .no-border{ border: none } .invoice i.fa{ color: #ff0000; font-size: 10px; } .imageThumb {
            max-height: 75px;
            border: 2px solid;
            padding: 1px;
            cursor: pointer;
          }
          .pip {
            display: inline-block;
            margin: 10px 10px 0 0;
          }
          .remove {
            display: block;
            background: #444;
            border: 1px solid black;
            color: white;
            text-align: center;
            cursor: pointer;
          }
          .remove:hover {
            background: white;
            color: black;
          } </style>
@php 
$n_img = 1; $currency = getCurrency()->name;
@endphp
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Support</a></li>
            <li class="breadcrumb-item active list-li" aria-current="page"><a href="#">{{$title}}</a></li>
            <!-- <li class="breadcrumb-item view-li no-disp"><a id="bc_list" href="">Order List</a></li> -->
            <li class="breadcrumb-item active view-li no-disp" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
    
</div>


<div id="content_list">@include('admin.support_customer.list')</div>
<div id="content_detail"></div>
@section('js') 
 <script src="{{asset('admin/assets/js/datatable/tables/ticket-datatable.js')}}"></script>

<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    $(document).ready(function(){ 
        $('#adminForm #can_submit').val(0); 
        @if(Session::has('success')) toastr.success("{{ Session::get('success')}}"); 
        @elseif(Session::has('error')) toastr.error("{{ Session::get('error')}}");  @endif
       
        
        
  //CHAT
        $('body').on('click','.viewBtn',function(){
            
            var id      =   this.id.replace('viewBtn-','');  
            var sellerId = 0;
            
            $.ajax({
                type: "GET",
               url: '{{url("admin/customer/support/chat")}}/'+id+"/chat",
                success: function (data) {
                    $('#chatmodel').modal('show');
                    $('.chat_content').html(data);
                 
                } 
            }); return false;
        });

//Create
        $('body').on('click','.createbtn',function(){
            
            var id      =   this.id.replace('create-','');  
          
            
            $.ajax({
                type: "GET",
               url: '{{url("admin/customer/support/create")}}/'+id+"/view",
                success: function (data) {
                    
                    $('.modal-body').html(data);
                     $('#normalmodal').modal('show'); 
                     // $('#content_detail').fadeIn(700); $('#content_list').hide(); 
                 
                } 
            }); return false;
        });


    

    });

     function submitThisForm(id){
       // e.preventDefault(); 
        var support_id = $('.support_id').val();
        var msg = $('.textareas').val();
        var cust_id = $('.cust_id').val();
        var file_data = $('.img_up')[0].files[0];   
        var form_data = new FormData();                  
        form_data.append('file_data', file_data);
        form_data.append('support_id', support_id);
        form_data.append('msg', msg);
        form_data.append('cust_id', cust_id);
       // var postid = $('#post_id').val();
       var id=0;
       //var form_data = new FormData(document.getElementById("form_submit"));
       
       //alert(msg);
       if(msg=='' && $('.img_up').val()=='')
       {
          toastr.warning('Please enter the message.'); 
       }
       else
       {
       $.ajax({
           type: "POST",
           enctype: 'multipart/form-data',
           contentType: false,
           processData: false,
           url: '{{url("admin/customer/support/create")}}/'+id+"/create",
           data: form_data,
           success: function( msg ) {
              // alert( msg );
               $('#normalmodal').modal('hide'); 
               toastr.success('Replied successfully.');
           }
       });
       }
   }

   function imgchange(e)
   {
    $(".pip").remove();
    var files = e.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          $("<span class=\"pip\">" +
            "<input type=\"file\" id=\"havefil\" hidden name=\"havefil[]\" value=\"" + e.target.result + "\"/>"+
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/>" +
            "</span>").insertAfter("#view_img");
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });

          // <span class=\"remove\">Remove image</span>Old code here
          /*$("<img></img>", {
            class: "imageThumb",
            src: e.target.result,
            title: file.name + " | Click to remove"
          }).insertAfter("#files").click(function(){$(this).remove();});*/

        });
        fileReader.readAsDataURL(f);
      }
   }
  
    
    
    function readURL(input) { 
        if (input.files && input.files[0]) { 
            var reader = new FileReader();
            reader.onload = function (e) { $('#adminForm #'+input.id+'Img').attr('src', e.target.result); $('#adminForm #'+input.id+'Img').show(); }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
