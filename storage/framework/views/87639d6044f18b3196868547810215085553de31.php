<style> .no-border{ border: none } .invoice i.fa{ color: #ff0000; font-size: 10px; } .image-preview-input {
    position: relative;
    overflow: hidden;
    margin: 0px;    
    color: #333;
    background-color: #fff;
    border-color: #ccc;    
}
.image-preview-input input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}
.image-preview-input-title {
    margin-left:2px;
}
.popup{
    width: 900px;
    margin: auto;
    text-align: center
}
.popup img{
    width: 200px;
    height: 200px;
    cursor: pointer
}
.show{
    z-index: 999;
    display: none;
}
.show .overlay{
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,.66);
    position: absolute;
    top: 0;
    left: 0;
}
.show .img-show{
    width: 600px;
    height: 400px;
    background: #FFF;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    overflow: hidden
}
.img-show span{
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 99;
    cursor: pointer;
}
.img-show img{
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
}
.bell {
    display: inline-block;
    position: relative;
    background-color: #eee;
    width: 48px;
    height: 42px;
    text-align: center;
    padding-top: 20px;
}
.bellnumbers {
    margin-top: -34px;
    font-size:12px;
    background-color:red;
    width:39px;
    line-height: 16px;
    text-align: center;
    color:#fff;
    z-index: 2;
    border-radius: 3px;
    position: absolute;
    left: 63px;
}</style>
<?php 
$n_img = 1; $currency = getCurrency()->name;
?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0"><?php echo e($title); ?></h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Chat</a></li>
            <li class="breadcrumb-item active list-li" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
            <!-- <li class="breadcrumb-item view-li no-disp"><a id="bc_list" href="">Order List</a></li> -->
            <li class="breadcrumb-item active view-li no-disp" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
        </ol>
    </div>

    
</div>
<div id="content_list"><?php echo $__env->make('admin.chat.list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
<div class="show">
  <div class="overlay"></div>
  <div class="img-show">
    <span>X</span>
    <img src="">
  </div>
</div>

<div id="myModalImge" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <img class="showthisimg" src="">
    </div>
  </div>
</div>

<?php $__env->startSection('js'); ?>
        <!-- lightslider js -->
        <script src="<?php echo e(asset('admin/assets/plugins/lightslider/js/lightslider.min.js')); ?>"></script>
        <!-- INTERNAL Chat js-->
        <script src="<?php echo e(asset('admin/assets/js/chat2.js')); ?>"></script>
  <script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 

    var chat_category=$('.chat_categories').val();
    var chat_type = 'ajax';
    if(chat_category)
    {
    setInterval(function(){chat_ajax(chat_category,chat_category)}, 8000)
    }
    else
    {
        chat_ajax(chat_id="10x",viewType='temporary');
    }
 
    
 
    


//     $(document).ready(function(){ 

//         window.onload = function () {
//     chat_ajax(chat_id="10x",viewType='temporary');
// };
//         var id=0;

        
        
//         }); 

    function scrollSmoothToBottom (id) {
        //alert(id);
   var div = document.getElementById(id);
   $('#'+id).animate({
      scrollTop: div.scrollHeight - div.clientHeight
   }, 700);
}

var scrolled = false;
function updateScroll(){
    if(!scrolled){
        var element = document.getElementById("ChatBody");
        element.scrollTop = element.scrollHeight;
    }
}

$("#yourDivID").on('scroll', function(){
    scrolled=true;
});

function loadChat (id){
           // alert('vcv');
            var chat_ids      = id;  
            var type          ="ajax";
            
            $.ajax({
                type: "GET",
               url: '<?php echo e(url("chat/chat-message")); ?>/'+chat_ids+"/"+type,
                success: function (data) {
                    //$('#chatmodel').modal('show');
                   // window.location.href = '<?php echo e(url("chat/chat-message-new")); ?>/'+chat_ids+"/"+type;
                    $('.chat_response').html(data);
                  // scrollSmoothToBottom('theend');
                  updateScroll();
                 //setInterval(function(){chat_ajax(chat_ids,type)}, 8000)
                } 
            }); return false;
       }
    function chat_ajax(chat_id,viewType){
            
            if(chat_id=='')
            {
               var chat_ids=0;
               var type="view"
            }
            else
            {
                var chat_ids=chat_id;
                var type = viewType;
            }
        $.ajax({
                type: "GET",
               url: '<?php echo e(url("chat/chat-message")); ?>/'+chat_ids+"/"+type,
                success: function (data) {
                    //$('#chatmodel').modal('show');
                    
                    $('.chat_response').html(data);
                  // scrollSmoothToBottom('theend');
                  updateScroll();
                 
                } 
            }); return false;
    }

    function submitThis(id){

       // e.preventDefault(); 
        var chat_id = id;
        var msg = $('.msgchat').val();
        var cust_id = $('.cust_id').val();
        var file_data = $('.img_up')[0].files[0];   
        var form_data = new FormData();                  
        form_data.append('file_data', file_data);
        form_data.append('chat_id', chat_id);
        form_data.append('msg', msg);
        form_data.append('cust_id', cust_id);
        var viewType="ajax";
      
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
           url: '<?php echo e(url("chat/send-message")); ?>/'+chat_id+"/create",
           data: form_data,
           success: function( msg ) {
              // alert( msg );
              $('.msgchat').val('');
              // $('.chat_response').html(data); 
            
            
            chat_ajax(chat_id,viewType);
                
           }
       });
      }
   }
   
//   $("#myInput").on("keyup", function() {
//   var value = this.value.toLowerCase().trim();
//   $(".media-contact-name span").show().filter(function() {
//     return $(this).text().toLowerCase().trim().indexOf(value) == -1;
//   }).hide();
// });

// $("#myInput").on('keyup', function(){
//       var value = $(this).val().toLowerCase();
//       $("#ChatList span").each(function () {
//          if ($(this).text().toLowerCase().search(value) > -1) {
//             $(this).show();
//             $(this).prev('.sspan').last().show();
//          } else {
//             $(this).hide();
//          }
//       });
//   })

$("#myInput").on('keyup', function(){
      var value = $(this).val().toLowerCase();
      $("#ChatList .chatname").each(function () {
         if ($(this).text().toLowerCase().search(value) > -1) {
            $(this).parents(".parent_a").show();
            // $(this).prev('.sspan').last().show();
         } else {
             $(this).parents(".parent_a").hide();
         }
      });
   });
function imgchange(input){  
   $(".bellnumbers").text("");
    //     var img = $('<img/>', {
    //         id: 'dynamic',
    //         width:250,
    //         height:200
    //     });
    //     $('.image-preview').popover({
    //     trigger:'manual',
    //     html:true,
    //     title: "<strong>Preview</strong>",
    //     content: "There's no image",
    //     placement:'bottom'
    // });      
        var file = input.files[0];
        
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
          //  $(".image-preview-clear").show();
           // img.attr('src', e.target.result);
            $('.showthisimg').attr('src',e.target.result);

           // $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
            $(".bellnumbers").text("Image");
        };        
        reader.readAsDataURL(file);  

    }

    function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#showthisimg').attr('src', e.target.result).width(250).height(300);
    };

    reader.readAsDataURL(input.files[0]);
  }
}

        </script>
<?php $__env->stopSection(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/chat/header.blade.php ENDPATH**/ ?>