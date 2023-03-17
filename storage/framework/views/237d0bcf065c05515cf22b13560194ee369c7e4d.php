<?php   
       use App\Models\SaleOrder;
       use App\Models\SalesOrderAddress;
       use App\Models\SaleorderItems;
       $val=[];
        $disData = '';
        $discount=0;
        $addSaleid = SaleOrder::where('order_id',$data['sale_id'])->first()->id; 
        $address   = SalesOrderAddress::where('sales_id',$addSaleid)->first();
        $saleIds   = SaleOrder::where('order_id',$data['sale_id'])->get(); 
        $subtottal = $pgcharge = $shipping = $g_total = $tax_value = 0; $allIds = []; $tot = 0;
        foreach ($saleIds as $key => $saldata) {
          $tot       += $saldata->total;  
          $pgcharge  += $saldata->payment_gateway_charge;
          $shipping  += $saldata->shiping_charge;
          $discount  += $saldata->discount; 
          $g_total   += $saldata->g_total;
          $tax_value += $saldata->tax;
          $Ids = $saldata->id; 
          $slIds[] = $Ids;
        }
        $subtottal = $tot;
        
        if($discount != 0)
            {
                $disData   =  '<tr>
                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                <div class="text-right" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right">Discount:</div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                            </td>
                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                            <td width="100">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                <div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right">- INR '.round($discount).'</div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                            </td>
                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                        </tr>';
            }
        foreach($slIds as $sId)
        {
        $orderItems = SaleorderItems::where('sales_id',$sId)->get();
            foreach ($orderItems as $items) {  
    
                $name = '<tr>
                            <td>&nbsp;</td>
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                <div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">'.$items->prd_name.'</div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                            </td>
                            <td>&nbsp;</td>
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                <div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">'.$items->qty.'</div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                            </td>
                            <td>&nbsp;</td>
                            <td style="width: 156px !important;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:right; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:right; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                <div class="text-right" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right">INR '.round($items->row_total).'</div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:right; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:right; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                            </td>
                            <td>&nbsp;</td>
                        </tr>';
                $val[] = $name;
            }
        }
        
        $tot = $subtottal + $pgcharge;
        ?>
       <head>
        <!--[if gte mso 9]><xml>
        <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml><![endif]-->
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
         <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="format-detection" content="date=no" />
        <meta name="format-detection" content="address=no" />
        <meta name="format-detection" content="telephone=no" />
        <title>Order</title>
        
    
        <style type="text/css" media="screen">
            /* Linked Styles */
            body { padding:0 !important; margin:0 !important; display:block !important; background:#1e1e1e; -webkit-text-size-adjust:none }
            a { color:#a88123; text-decoration:none }
            p { padding:0 !important; margin:0 !important } 
    
            /* Mobile styles */
            </style>
            <style media="only screen and (max-device-width: 480px), only screen and (max-width: 480px)" type="text/css">
            @media  only screen and (max-device-width: 480px), only screen and (max-width: 480px) { 
                div[class="mobile-br-5"] { height: 5px !important; }
                div[class="mobile-br-10"] { height: 10px !important; }
                div[class="mobile-br-15" { height: 15px !important; }
                div[class="mobile-br-20" { height: 20px !important; }
                div[class="mobile-br-25" { height: 25px !important; }
                div[class="mobile-br-30" { height: 30px !important; }
    
                th[class="m-td"], 
                td[class="m-td"], 
                div[class="hide-for-mobile"], 
                span[class="hide-for-mobile"] { display: none !important; width: 0 !important; height: 0 !important; font-size: 0 !important; line-height: 0 !important; min-height: 0 !important; }
    
                span[class="mobile-block"] { display: block !important; }
    
                div[class="wgmail"] img { min-width: 320px !important; width: 320px !important; }
    
                div[class="img-m-center"] { text-align: center !important; }
    
                div[class="fluid-img"] img,
                td[class="fluid-img"] img { width: 100% !important; max-width: 100% !important; height: auto !important; }
    
                table[class="mobile-shell"] { width: 100% !important; min-width: 100% !important; }
                td[class="td"] { width: 100% !important; min-width: 100% !important; }
                
                table[class="center"] { margin: 0 auto; }
                
                td[class="column-top"],
                th[class="column-top"],
                td[class="column"],
                th[class="column"] { float: left !important; width: 100% !important; display: block !important; }
    
                td[class="content-spacing"] { width: 15px !important; }
    
                div[class="h2"] { font-size: 44px !important; line-height: 48px !important; }
            } 
        </style>
        </head>
        <body class="body" style="padding:0 !important; margin:0 !important; display:block !important; background:#1e1e1e; -webkit-text-size-adjust:none">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#1e1e1e">
                <tr>
                    <td align="center" valign="top">
    
                        <table width="600" border="0" cellspacing="0" cellpadding="0" class="mobile-shell">
                            <tr>
                                <td class="td" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; width:600px; min-width:600px; Margin:0" width="600">
                                    <!-- Header -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="30" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                <div class="img-center" style="font-size:0pt; line-height:0pt; text-align:center"><a href="#" target="_blank"><img src="<?php echo e(URL::asset('admin/assets/images/brand/logo.png')); ?>" border="0" width="300" height="auto" alt="" /></a></div>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="30" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                            </td>
                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                        </tr>
                                    </table>
                                    <!-- END Header -->
    
                                    <!-- Main -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>
                                                <!-- Head -->
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#006fb4">
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="27"><img src="<?php echo e(URL::asset('admin/assets/images/email/top_left.jpg')); ?>" border="0" width="27" height="27" alt="" /></td>
                                                                    <td>
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" height="3" bgcolor="#014c8d">&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="24" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                    </td>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="27"><img src="<?php echo e(URL::asset('admin/assets/images/email/top_right.jpg')); ?>" border="0" width="27" height="27" alt="" /></td>
                                                                </tr>
                                                            </table>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#014c8d"></td>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="10"></td>
                                                                    <td>
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                        <div class="h2" style="color:#ffffff; font-family:Georgia, serif; min-width:auto !important; font-size:60px; line-height:64px; text-align:center">
                                                                            <em>Thank you</em>
                                                                        </div>
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
    
                                                                        <div class="h3-2-center" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:20px; line-height:26px; text-align:center; letter-spacing:5px">FOR YOUR ORDER!</div>
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="35" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                    </td>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="10"></td>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#014c8d"></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- END Head -->
    
                                                <!-- Body -->
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                                    <tr>
                                                        <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="35" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                            <div class="h3-1-center" style="color:#1e1e1e; font-family:Georgia, serif; min-width:auto !important; font-size:20px; line-height:26px; text-align:center">Hello, <?=$data['username']?> <br />Thank you for purchasing from our store. <br /> We will let you know as soon as your items have been shipped.</div>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="20" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>                                       
                                                            
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <th class="column-top" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top; Margin:0" valign="top" width="270">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f4f4f4">
                                                                                        <tr>
                                                                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                            <td>
                                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="10" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                                <div class="text-1" style="color:#006fb4; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">
                                                                                                    <strong>SHIPPING ADDRESS:</strong>
                                                                                                </div>
                                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="10" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                            </td>
                                                                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#fafafa">
                                                                                        <tr>
                                                                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                            <td>
                                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="10" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                                <div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">
                                                                                                    <strong><?=$address->s_name?></strong>
                                                                                                    <br />
                                                                                                    <?=$address->s_address1.', '.$address->s_address2?>
                                                                                                    <br />
                                                                                                    <?=$address->scity->city_name.', '.$address->sstate->state_name.','?>
                                                                                                    <br />
                                                                                                    <?=$address->scountry->country_name.'- '.$address->s_zip_code?>
                                                                                                    <br /> Phone:
                                                                                                    <?php if($address->s_country_code){ ?>
                                                                                                    <?='+'.$address->s_country_code?> <?php } ?> <?=$address->s_phone?>
                                                                                                </div>
                                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                            </td>
                                                                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                    <th class="column-top" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top; Margin:0" valign="top" width="20">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td><div style="font-size:0pt; line-height:0pt;" class="mobile-br-15"></div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                    <th class="column-top" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top; Margin:0" valign="top" width="270">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f4f4f4">
                                                                                        <tr>
                                                                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                            <td>
                                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="10" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                                <div class="text-1" style="color:#006fb4; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">
                                                                                                    <strong>ORDER NUMBER:</strong> <span style="color: #1e1e1e;"><?=$data['sale_id']?></span>
                                                                                                </div>
                                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="10" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                            </td>
                                                                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="20" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
    
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f4f4f4">
                                                                                        <tr>
                                                                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                            <td>
                                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="10" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                                <div class="text-1" style="color:#006fb4; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">
                                                                                                    <strong>ORDER DATE:</strong>
                                                                                                </div>
                                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="10" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                            </td>
                                                                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#fafafa">
                                                                                        <tr>
                                                                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                            <td>
                                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="10" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                                <div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left">
                                                                                                    <?=date("F d, Y")?> 
                                                                                                </div>
                                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                            </td>
                                                                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                </tr>
                                                            </table>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="40" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
    
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td style="border-bottom: 1px solid #f4f4f4;" class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                    <td style="border-bottom: 1px solid #f4f4f4;" width="225">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                        <div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left"><strong>Item</strong></div>
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                    </td>
                                                                    <td style="border-bottom: 1px solid #f4f4f4;" class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                    <td style="border-bottom: 1px solid #f4f4f4;">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                        <div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:left"><strong>Qty</strong></div>
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                    </td>
                                                                    <td style="border-bottom: 1px solid #f4f4f4;" class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                    <td style="border-bottom: 1px solid #f4f4f4;" width="60">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:righ; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:righ; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                        <div class="text-righ" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right"><strong>Total</strong></div>
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:righ; width:100%; min-width:100%"><tr><td height="8" class="spacer" style="font-size:0pt; line-height:0pt; text-align:righ; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                    </td>
                                                                    <td style="border-bottom: 1px solid #f4f4f4;" class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                </tr>
                                                                
                                                                <?=implode(" ",$val)?>
                                                                
                                                            </table>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="10" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
    
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" height="1" bgcolor="#006fb4">&nbsp;</td>
                                                                </tr>
                                                            </table>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
    
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td align="right">
                                                                        <table border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                <td>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                    <div class="text-right" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right">Subtotal:</div>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                </td>
                                                                                <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                                <td width="100">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                    <div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right">INR <?=round($tot)?></div>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                </td>
                                                                                <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>&nbsp;</td>
                                                                                <td>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                    <div class="text-right" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right">Shipping:</div>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                </td>
                                                                                <td>&nbsp;</td>
                                                                                <td width="100">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                    <div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right">INR <?=round($shipping)?></div>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                </td>
                                                                                <td>&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>&nbsp;</td>
                                                                                <td>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                                                                    <div class="text-right" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right">Tax:</div>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                                                                </td>
                                                                                <td>&nbsp;</td>
                                                                                <td width="100">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                                                                    <div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right">INR <?php echo round($tax_value); ?> </div>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                                                                </td>
                                                                                <td>&nbsp;</td>
                                                                            </tr>
                                                                           <?php echo $disData ?>
                                                                            <tr>
                                                                                <td>&nbsp;</td>
                                                                                <td>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                    <div class="text-right" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right"><strong>TOTAL:</strong></div>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                </td>
                                                                                <td>&nbsp;</td>
                                                                                <td width="100">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                    <div class="text" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:14px; line-height:20px; text-align:right"><strong>INR <?=round($g_total)?></strong></div>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="3" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                                </td>
                                                                                <td>&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="35" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                        </td>
                                                        <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                                    </tr>
                                                </table>
                                                <!-- END Body -->
    
                                                <!-- Foot -->
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#006fb4">
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#014c8d"></td>
                                                                    <td>
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="30" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                        <div class="h3-1-center" style="color:#1e1e1e; font-family:Georgia, serif; min-width:auto !important; font-size:20px; line-height:26px; text-align:center">
                                                                            <em>Follow Us</em>
                                                                        </div>
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
    
                                                                        <!-- Socials -->
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td align="center">
                                                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td class="img-center" style="font-size:0pt; line-height:0pt; text-align:center" width="38"><a href="#" target="_blank"><img src="<?php echo e(URL::asset('admin/assets/images/email/facebook.png')); ?>" border="0" width="28" height="28" alt="" /></a></td>
                                                                                            <td class="img-center" style="font-size:0pt; line-height:0pt; text-align:center" width="38"><a href="#" target="_blank"><img src="<?php echo e(URL::asset('admin/assets/images/email/twitter.png')); ?>" border="0" width="28" height="28" alt="" /></a></td>
                                                                                            <td class="img-center" style="font-size:0pt; line-height:0pt; text-align:center" width="38"><a href="#" target="_blank"><img src="<?php echo e(URL::asset('admin/assets/images/email/instagram.png')); ?>" border="0" width="28" height="28" alt="" /></a></td>
                                                                                            <td class="img-center" style="font-size:0pt; line-height:0pt; text-align:center" width="38"><a href="#" target="_blank"><img src="<?php echo e(URL::asset('admin/assets/images/email/linkedin.png')); ?>" border="0" width="28" height="28" alt="" /></a></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        <!-- END Socials -->
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                    </td>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#014c8d"></td>
                                                                </tr>
                                                            </table>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="27"><img src="<?php echo e(URL::asset('admin/assets/images/email/left.jpg')); ?>" border="0" width="27" height="27" alt="" /></td>
                                                                    <td>
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="24" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" height="3" bgcolor="#014c8d">&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="27"><img src="<?php echo e(URL::asset('admin/assets/images/email/right.jpg')); ?>" border="0" width="27" height="27" alt="" /></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- END Foot -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END Main -->
                                    
                                    <!-- Footer -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="30" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                                <div class="text-footer" style="color:#666666; font-family:Arial, sans-serif; min-width:auto !important; font-size:12px; line-height:18px; text-align:center">
                                                    <a href="mailto:email@yoursitename.com" target="_blank" class="link-1" style="color:#666666; text-decoration:none"><span class="link-1" style="color:#666666; text-decoration:none">ushas@gmail.com</span></a>
                                                    <span class="mobile-block"><span class="hide-for-mobile">|</span></span>
                                                    Phone: <a href="tel:+1655606605" target="_blank" class="link-1" style="color:#666666; text-decoration:none"><span class="link-1" style="color:#666666; text-decoration:none">+971 5555 45180</span></a>
                                                </div>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="30" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
    
                                            </td>
                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                        </tr>
                                    </table>
                                    <!-- END Footer -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html><?php /**PATH /home/qaushas/public_html/resources/views/emails/customer_msg_email.blade.php ENDPATH**/ ?>