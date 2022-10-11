
<?php $__env->startSection('css'); ?>
        <!--- INTERNAL JQUERY-COUNTDOWN CSS -->
        <link href="<?php echo e(URL::asset('assets/plugins/jquery-countdown/jquery.countdown.css')); ?>" rel="stylesheet" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="container">
    <div class="row">

        <div class="col-md-12 text-center mt-5" style="color: #fff">
            <h2>Configure Settings</h2>
        </div>
        <div class="col-md-12 mt-10" style="color: #fff">

            <?php echo e(Form::open(['url' => "admin/save-config", 'id' => 'userForm', 'name' => 'userForm', 'class' => '','files'=>'true', 'novalidate'])); ?>

        <?php
            // if(isset($errors) && count($errors)>0){
            //     dd($errors); 
            // } 
       
         ?>
        <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="fname">App name <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="config[app_name]" id="fname" placeholder="App name" value="<?php echo e($app_name); ?>"  required>
                    <span class="error"></span>
                    <?php $__errorArgs = ['app_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lname">Base URL <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="config[base_url]" id="lname" placeholder="Base URL" value="<?php echo e($base_url); ?>"  required>
                    <span class="error"></span>
                    <?php $__errorArgs = ['base_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="m_lang">Multi-language <span class="text-red">*</span></label>
                    <select class="form-control" name="config[m_lang]" id="m_lang">
                        <option value="">Select</option>
                        <option value="yes" <?php if($m_lang =="yes"){ echo 'selected'; } ?>>Yes</option>
                        <option value="no" <?php if($m_lang =="no"){ echo 'selected'; } ?>>No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['m_lang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="m_currency">Multi-currency <span class="text-red">*</span></label>
                    <select class="form-control" name="config[m_currency]" id="m_currency">
                        <option value="">Select</option>
                        <option value="yes" <?php if($m_currency =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($m_currency =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['m_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="prod_type">Product Type <span class="text-red">*</span></label>
                    <select class="form-control" name="config[prod_type]" id="prod_type">
                        <option value="">Select</option>
                        <option value="from_api" <?php if($prod_type =="from_api"){ echo 'selected'; } ?> >MJS Model(Price from API- No Stock)</option>
                        <option value="manual" <?php if($prod_type =="manual"){ echo 'selected'; } ?> >KT Model(Manual Update)</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['prod_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="payment_gateway">Payment Gateway <span class="text-red">*</span></label>
                    <select class="form-control" name="config[payment_gateway]" id="payment_gateway">
                        <option value="">Select</option>
                        <option value="stripe" <?php if($payment_gateway =="stripe"){ echo 'selected'; } ?> >Stripe</option>
                        <option value="other" <?php if($payment_gateway =="other"){ echo 'selected'; } ?> >Other</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['payment_gateway'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="shipping_md">Shipping Method <span class="text-red">*</span></label>
                    <select class="form-control" name="config[shipping_md]" id="shipping_md">
                        <option value="">Select</option>
                        <option value="fedex" <?php if($shipping_md =="fedex"){ echo 'selected'; } ?> >Fedex</option>
                        <option value="custom" <?php if($shipping_md =="custom"){ echo 'selected'; } ?> >Custom</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['shipping_md'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="return_flow">Product Return Flow <span class="text-red">*</span></label>
                    <select class="form-control" name="config[return_flow]" id="return_flow">
                        <option value="">Select</option>
                        <option value="flow_1" <?php if($return_flow =="flow_1"){ echo 'selected'; } ?> >Flow 1</option>
                        <option value="flow_2" <?php if($return_flow =="flow_2"){ echo 'selected'; } ?> >Flow 2</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['return_flow'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

             <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="seller_panel">Seller Panel <span class="text-red">*</span></label>
                    <select class="form-control" name="config[seller_panel]" id="seller_panel">
                        <option value="">Select</option>
                        <option value="yes" <?php if($seller_panel =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($seller_panel =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['seller_panel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cust_approval">Customer Approval <span class="text-red">*</span></label>
                    <select class="form-control" name="config[cust_approval]" id="cust_approval">
                        <option value="">Select</option>
                        <option value="yes" <?php if($cust_approval =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($cust_approval =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['cust_approval'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="cust_credits">Customer Credits <span class="text-red">*</span></label>
                    <select class="form-control" name="config[cust_credits]" id="cust_credits">
                        <option value="">Select</option>
                        <option value="yes" <?php if($cust_credits =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($cust_credits =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['cust_credits'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cust_referral">Customer Referral <span class="text-red">*</span></label>
                    <select class="form-control" name="config[cust_referral]" id="cust_referral">
                        <option value="">Select</option>
                        <option value="yes" <?php if($cust_referral =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($cust_referral =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['cust_referral'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="extra_fields">Extra Fields <span class="text-red">*</span></label>
                    <select class="form-control" name="config[extra_fields]" id="extra_fields">
                        <option value="">Select</option>
                        <option value="yes" <?php if($extra_fields =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($extra_fields =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['extra_fields'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="brands">Brands <span class="text-red">*</span></label>
                    <select class="form-control" name="config[brands]" id="brands">
                        <option value="">Select</option>
                        <option value="yes" <?php if($brands =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($brands =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['brands'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="prod_return">Product Return <span class="text-red">*</span></label>
                    <select class="form-control" name="config[prod_return]" id="prod_return">
                        <option value="">Select</option>
                        <option value="yes" <?php if($prod_return =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($prod_return =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['prod_return'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="refund">Refund <span class="text-red">*</span></label>
                    <select class="form-control" name="config[refund]" id="refund">
                        <option value="">Select</option>
                        <option value="yes" <?php if($refund =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($refund =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['refund'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="discount">Discount <span class="text-red">*</span></label>
                    <select class="form-control" name="config[discount]" id="discount">
                        <option value="">Select</option>
                        <option value="yes" <?php if($discount =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($discount =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="rewards">Rewards <span class="text-red">*</span></label>
                    <select class="form-control" name="config[rewards]" id="rewards">
                        <option value="">Select</option>
                        <option value="yes" <?php if($rewards =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($rewards =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['rewards'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="blog">Blog <span class="text-red">*</span></label>
                    <select class="form-control" name="config[blog]" id="blog">
                        <option value="">Select</option>
                        <option value="yes" <?php if($blog =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($blog =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['blog'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="support_ticket">Support Ticket <span class="text-red">*</span></label>
                    <select class="form-control" name="config[support_ticket]" id="support_ticket">
                        <option value="">Select</option>
                        <option value="yes" <?php if($support_ticket =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($support_ticket =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['support_ticket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
             <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="loyality_points">Loyality Points <span class="text-red">*</span></label>
                    <select class="form-control" name="config[loyality_points]" id="loyality_points">
                        <option value="">Select</option>
                        <option value="yes" <?php if($loyality_points =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($loyality_points =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['loyality_points'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="branches">Branches <span class="text-red">*</span></label>
                    <select class="form-control" name="config[branches]" id="branches">
                        <option value="">Select</option>
                        <option value="yes" <?php if($branches =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($branches =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['branches'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="auction">Auction <span class="text-red">*</span></label>
                    <select class="form-control" name="config[auction]" id="auction">
                        <option value="">Select</option>
                        <option value="yes" <?php if($auction =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($auction =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['auction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="crm_integration">CRM Integration <span class="text-red">*</span></label>
                    <select class="form-control" name="config[crm_integration]" id="crm_integration">
                        <option value="">Select</option>
                        <option value="yes" <?php if($crm_integration =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($crm_integration =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    <?php $__errorArgs = ['crm_integration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <input type="submit" class="btn btn-success fr" name="saveconfig" value="Save">
            </form>
        </div>

        <a href="<?php echo e(url('admin/clear-cache')); ?>" style="color:#fff;">Clear Cache</a>

    </div>   
</div>




    
    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\ushas-dev\resources\views/config.blade.php ENDPATH**/ ?>