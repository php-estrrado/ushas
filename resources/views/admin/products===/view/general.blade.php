<div class="tab-pane active " id="tab1">
    <div class="card-header mb-4""><div class="card-title">General Information</div></div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            <label class="form-label view" for="fname">Seller: </label>
                    <p class="view_value">{{ $seller->fname }} </p>
      
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">

            <label class="form-label view" for="fname">Language: </label>
            @php
            $def_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
            $content_table=DB::table('cms_content')->where('cnt_id', $product->name_cnt_id)->first();
            if($content_table){ 
            $lang_id = $content_table->lang_id;
            }
            @endphp
            <p class="view_value"> @foreach ($languages as $lang=>$lv)
            @if($lang_id==$lang) {{ $lv }} @endif 
            @endforeach
            </p>
        </div>
    </div>
   <div class="clr"></div>
    <div id="seller_div" class="col-lg-6 fl">
        <div class="form-group">
           <label class="form-label view" for="fname">Product Name: </label>
                    <p class="view_value">{{ $product->name }} </p>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            <label class="form-label view" for="fname">Category: </label>

            <p class="view_value">  @foreach ($categories as $cat_id=>$cat_name)
            <?php if($product->category_id==$cat_id){ echo $cat_name ;
            ?> 
            <input type='hidden' id="category_id" value="{{ $product->category_id }}">
            <?php
            }?> 
            @endforeach
            </p>
            
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            <label class="form-label view" for="fname">Sub Category: </label>

            <p class="view_value">  @foreach ($sub_cats as $cat_id=>$cat_name)
            <?php if($product->sub_category_id==$cat_id){ echo $cat_name ;
            
             ?> 
            <input type='hidden' id="sub_category_id" value="{{ $product->sub_category_id }}">
            <?php
            }?> 
            @endforeach
            </p>
        </div>
    </div>
    <!--<div class="col-lg-6 fl">-->
    <!--    <div class="form-group">-->

    <!--        <label class="form-label view" for="fname">Brand: </label>-->

    <!--        <p class="view_value">  @foreach ($brands as $bnd_id=>$bnd_name)-->
    <!--        <?php if($product->brand_id==$bnd_id){ echo $bnd_name ;}?> -->
    <!--        @endforeach-->
    <!--        </p>-->
    <!--    </div>-->
    <!--</div>-->
    <div class="col-lg-6 fl">
        <div class="form-group">
        
           <label class="form-label view" for="fname">Short Description: </label>
            <p class="view_value">{{ getContent($product->short_desc_cnt_id); }} </p>
        </div>
    </div><div class="clr"></div>
    <div class="col-lg-6 fl">
        <div class="form-group">

             @php  if($product->is_active == 1){ $active = "Active";  }else if ($product->is_active == 0){ $active = "Inactive";  } @endphp
<label class="form-label view" >Status: </label>
            <p class="view_value">{{ $active }} </p>
        </div>
    </div><div class="clr"></div>
    <!--<div class="col-lg-6 fl">-->
    <!--    <div class="form-group">-->
            
    <!--         <label class="form-label view" for="fname">Long Description: </label>-->
    <!--        <p class="view_value"><?php echo getContent($product->desc_cnt_id,2); ?> </p>-->
    <!--    </div>-->
    <!--</div>-->
    <!--<div class="col-lg-6 fl">-->
    <!--    <div class="form-group">-->
  
    <!--         <label class="form-label view" for="fname">Content: </label>-->
    <!--        <p class="view_value"><?php echo getContent($product->content_cnt_id); ?> </p>-->
    <!--    </div>-->
    <!--</div>-->
</div>



 <script type="text/javascript">

$(document).ready(function(){

        
    var min_car = "{{ $min_carat_id }}";       
    $("#attr_"+min_car).prop("checked",true); 
    
    
        generatePrice();
                

                var cat_id = $('#category_id').val();
            var subcat_id = $('#sub_category_id').val();


            $.ajax({
            url:"{{ route('fieldlist_ajax') }}",
            type:"POST",
            data: {
            cat_id: cat_id,subcat_id:subcat_id
            },
            success:function (data) {
            console.log(data); $('.fieldgroup').hide();
            $.each(data.fields.existing_fields,function(index,field){
            //alert(subcategory.subcategory_id);
            console.log("fld--"+field);
            
            $('.fieldgroup .show_'+field).parents('.fieldgroup').show();
            $('.fieldgroup .show_'+field).parents('.fieldgroup').addClass("exist");
            $(".available_fields .fieldslist").hide();

            console.log($('.fieldgroup .show_'+field).html());
            $("#nav_tab_4").show();
            });
            // $(".fieldgroup:not(.exist)").remove();
            if($(".fieldgroup").hasClass("exist")){
                setTimeout( function(){ 
                $(".fieldgroup:not(.exist)").hide();
                setCarat();
                }  , 1000 ); 
            }
               
            }
            });
            
});
   




        $('body').on('click','.fieldgroup.exist [type="checkbox"]',function(){ 
        var fld_id = $(this).val();

            if ($(this).prop('checked')) {
            $(".available_fields .show_val_"+fld_id).show();
            } else {
            $(".available_fields .show_val_"+fld_id).hide();
            }

        });

function setCarat(){
    $('.fieldgroup.exist [type="checkbox"]').each(function(){
        var fld_id = $(this).val();
         if ($(this).prop('checked')) {
            $(".available_fields .show_val_"+fld_id).show();
            } else {
            $(".available_fields .show_val_"+fld_id).hide();
            }

    });
}

        $('body').on('change','.available_fields.exist [type="radio"]',function(){ 
        var carat = $(this).val();

        generatePrice(carat);

        });



    $('body').on('change','#weight',function(){ 
        total_price();
     });
    $('body').on('change','#fixed_price',function(){ 
        total_price();
     });

  function total_price(){

    var weight = $("#weight").val();
    var fixed_price = $("#fixed_price").val();
    var total_disp = var_price_disp = 0;
    var var_price = $('#varprice').val();


var tax_val = 0;
var tax_cal = 0;
tax_val  = $('#hidded_tax').val();


    if(var_price>0){
    total_disp = parseFloat(total_disp) + parseFloat(var_price);
    }
   

     if(weight>0){
        total_disp = parseFloat(total_disp) * weight;
        var_price_disp = parseFloat(var_price) * weight; 
    
    }else {
         var_price_disp = parseFloat(var_price) ; 
          
    }
    
     if(fixed_price>0)
    { 
    var fixed_price1 = parseFloat(fixed_price).toFixed(2);  
    $("#fixed_price_disp").text(fixed_price1);
    total_disp = parseFloat(total_disp) + parseFloat(fixed_price);
    $("#total_price_disp").text(Math.round(total_disp).toFixed(2));
    }

    tax_cal = (total_disp*tax_val)/100;
    total_disp = parseFloat(total_disp) + parseFloat(tax_cal);
    $("#total_tax_disp").text(tax_cal.toFixed(2));
    $("#total_price_disp").text(Math.round(total_disp).toFixed(2));
    $("#var_price_disp").text(var_price_disp.toFixed(2));
     $("#fixed_price_disp").text(fixed_price_disp.toFixed(2));

 }    



function generatePrice(carat=0){

    if(carat ==0){
        var min_car = "{{ $min_carat_id }}";   
        min_car = $("#attr_"+min_car).val();
       if(min_car){  carat = min_car; }
    }
    $("#varprice").val('0.00');
    $("#var_price_disp").text('0.00');
    $("#total_tax_disp").text('0.00');
    $("#total_price_disp").text('0.00');

   var subcat_id = $('#sub_category_id').val();

   // alert(subcat_id);
   if(subcat_id){
       $.ajax({
            url:"{{ route('fetchPrice') }}",
            type:"POST",
            data: {
            subcat_id: subcat_id,carat:carat
            },
            success:function (data) {
                var dd = parseFloat(data);
            console.log(dd.toFixed(2)); 
       
               $("#varprice").val(dd.toFixed(2));
               $("#var_price_disp").text(dd.toFixed(2));
               total_price();
            }
            });
   }



}

</script>
                        
                        