<?php if($prdType !="") { if($prdType ==1) { $simple_prod = "display:block;"; $var_prod = "display:none;"; }else {  $simple_prod = "display:none;"; $var_prod = "display:block;"; } }else { $simple_prod = "display:block;"; $var_prod = "display:none;"; }  ?>
<div class="tab-pane" id="tab2">
    <div class="card-header mb-4"><div class="card-title">Product Info</div></div>
    <div class="simple_prod" style="<?php echo $simple_prod ; ?>">
    <div class="col-lg-6 fl">
        <div class="form-group">
        
            <label class="form-label view" for="fname">Price: </label>
            <p class="view_value">{{ $price; }} </p>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            
             <label class="form-label view" for="fname">Sale Price: </label>
            <p class="view_value">{{ $sPrice; }} </p>
        </div>
    </div> 
    <div class="col-lg-6 fl">
        <div class="form-group">
         
             <label class="form-label view" for="fname">Sale From Date: </label>
            <p class="view_value"> {{date('d M Y',strtotime($stDate))}}  </p>
        </div>
    </div> 
    <div class="col-lg-6 fl">
        <div class="form-group">
           
            <label class="form-label view" for="fname">Sale To Date: </label>
            <p class="view_value"> {{date('d M Y',strtotime($edDate))}}  </p>
        </div>
    </div> 
    <div class="col-lg-6 fl">
        <div class="form-group">
         
             <label class="form-label view" for="fname">Tax: </label>

            <p class="view_value">  @foreach ($taxes as $tx_id=>$tx_name)
            <?php if($product->tax_id==$taxId){ echo $tx_name ;}?> 
            @endforeach
            </p>
        </div>
    </div>
</div>

<div class="variable_prod" style="<?php echo $var_prod;  ?>">
    <div class="tab-content col-12">
                <div class="tab-pane active " id="tab1">
                <div class="card-header mb-4"><div class="card-title">Variation List</div></div>
                <div class="row">

                     <div class="variation_table_div table-responsive tabs-menu-body">


                        <table class="variation_table table" id="variation_table">
                            <thead>
                                <tr>
                                    <?php if($attr_1_name !=""){ ?> <th class="text-center init_name"><?php echo $attr_1_name; ?></th> <?php }else { ?> 
                                     <th class="text-center init_name">Name</th>
                                 <?php } ?>
                                 <?php if($attr_2_name !=""){ ?> <th class="text-center init_name"><?php echo $attr_2_name; ?></th> <?php } ?>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">SKU</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  


               if(isset($attr_stock) && $attr_stock !=""  )
                {


                foreach($attr_stock as $dyk=>$dyv){
              
                if(is_array($dyv)) {
                    $at_2 = 0;
                    foreach($dyv as $dyk2=>$dyv2){
                         $at_2++;
                     
                    ?>

                   <tr>
                   <?php if($at_2 ==1){ ?> <td class="text-center init_value" rowspan="<?php echo count($dyv) ?>"><input class="form-control" placeholder="Option" value="<?php echo $attr_1_value->$dyk[0]; ?>" readonly="true" type="text"></td> <?php } ?>
                    <td class="text-center"><input class="form-control" placeholder="Option" value="<?php echo $attr_2_value->$dyk2[0]; ?>" type="text"></td>
                    <td class="text-center"><input class="form-control price_field" name="price[<?php echo $dyk; ?>][<?php echo $dyk2; ?>]" placeholder="Price" value="<?php echo $attr_price[$dyk][$dyk2]; ?>" type="text"></td>
                    <td class="text-center"><input class="form-control stock_field" name="stock[<?php echo $dyk; ?>][<?php echo $dyk2; ?>]" placeholder="Stock" value="<?php echo $attr_stock[$dyk][$dyk2]; ?>" type="text"></td>
                    <td class="text-center"><input class="form-control sku_field" name="sku[<?php echo $dyk; ?>][<?php echo $dyk2; ?>]" placeholder="SKU" value="<?php echo $attr_sku[$dyk][$dyk2]; ?>" type="text"></td>
                    </tr>

              <?php } }else { ?>
                    <tr>
                     <td class="text-center init_value" ><input class="form-control" placeholder="Option" value="<?php echo $attr_1_value->$dyk[0]; ?>" readonly="true" type="text"></td> 
                    
                    <td class="text-center"><input class="form-control" placeholder="Price" name="price[<?php echo $dyk; ?>]" value="<?php echo $attr_price[$dyk]; ?>" type="text"></td>
                    <td class="text-center"><input class="form-control" placeholder="Stock" name="stock[<?php echo $dyk; ?>]" value="<?php echo $attr_stock[$dyk]; ?>" type="text"></td>
                    <td class="text-center"><input class="form-control" placeholder="SKU" name="sku[<?php echo $dyk; ?>]" value="<?php echo $attr_sku[$dyk]; ?>" type="text"></td>
                    </tr>

                <?php }
                }
                }else { ?>

                                <tr>
                                    <td class="text-center init_value"><input class="form-control" placeholder="Option" readonly="true" type="text"></td>
                                    <td class="text-center"><input class="form-control" placeholder="Price" type="text"></td>
                                    <td class="text-center"><input class="form-control" placeholder="Stock" type="text"></td>
                                    <td class="text-center"><input class="form-control" placeholder="SKU" type="text"></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            
                        </table>

                    </div>
                        
                        </div>    <!--  row ends -->
                     </div> <!--  tab1 ends -->


                </div><!--  tab content ends -->
</div>
</div>
                        