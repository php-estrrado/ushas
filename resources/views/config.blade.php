@extends('layouts.master2')
@section('css')
        <!--- INTERNAL JQUERY-COUNTDOWN CSS -->
        <link href="{{URL::asset('assets/plugins/jquery-countdown/jquery.countdown.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('content')

<div class="container">
    <div class="row">

        <div class="col-md-12 text-center mt-5" style="color: #fff">
            <h2>Configure Settings</h2>
        </div>
        <div class="col-md-12 mt-10" style="color: #fff">

            {{Form::open(['url' => "admin/save-config", 'id' => 'userForm', 'name' => 'userForm', 'class' => '','files'=>'true', 'novalidate'])}}
        <?php
            // if(isset($errors) && count($errors)>0){
            //     dd($errors); 
            // } 
       
         ?>
        <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="fname">App name <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="config[app_name]" id="fname" placeholder="App name" value="{{ $app_name }}"  required>
                    <span class="error"></span>
                    @error('app_name')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lname">Base URL <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="config[base_url]" id="lname" placeholder="Base URL" value="{{ $base_url }}"  required>
                    <span class="error"></span>
                    @error('base_url')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
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
                    @error('m_lang')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="m_currency">Multi-currency <span class="text-red">*</span></label>
                    <select class="form-control" name="config[m_currency]" id="m_currency">
                        <option value="">Select</option>
                        <option value="yes" <?php if($m_currency =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($m_currency =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    @error('m_currency')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
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
                    @error('prod_type')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="payment_gateway">Payment Gateway <span class="text-red">*</span></label>
                    <select class="form-control" name="config[payment_gateway]" id="payment_gateway">
                        <option value="">Select</option>
                        <option value="stripe" <?php if($payment_gateway =="stripe"){ echo 'selected'; } ?> >Stripe</option>
                        <option value="other" <?php if($payment_gateway =="other"){ echo 'selected'; } ?> >Other</option>
                    </select>
                    <span class="error"></span>
                    @error('payment_gateway')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
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
                    @error('shipping_md')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="return_flow">Product Return Flow <span class="text-red">*</span></label>
                    <select class="form-control" name="config[return_flow]" id="return_flow">
                        <option value="">Select</option>
                        <option value="flow_1" <?php if($return_flow =="flow_1"){ echo 'selected'; } ?> >Flow 1</option>
                        <option value="flow_2" <?php if($return_flow =="flow_2"){ echo 'selected'; } ?> >Flow 2</option>
                    </select>
                    <span class="error"></span>
                    @error('return_flow')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
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
                    @error('seller_panel')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cust_approval">Customer Approval <span class="text-red">*</span></label>
                    <select class="form-control" name="config[cust_approval]" id="cust_approval">
                        <option value="">Select</option>
                        <option value="yes" <?php if($cust_approval =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($cust_approval =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    @error('cust_approval')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
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
                    @error('cust_credits')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cust_referral">Customer Referral <span class="text-red">*</span></label>
                    <select class="form-control" name="config[cust_referral]" id="cust_referral">
                        <option value="">Select</option>
                        <option value="yes" <?php if($cust_referral =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($cust_referral =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    @error('cust_referral')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
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
                    @error('extra_fields')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="brands">Brands <span class="text-red">*</span></label>
                    <select class="form-control" name="config[brands]" id="brands">
                        <option value="">Select</option>
                        <option value="yes" <?php if($brands =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($brands =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    @error('brands')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
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
                    @error('prod_return')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="refund">Refund <span class="text-red">*</span></label>
                    <select class="form-control" name="config[refund]" id="refund">
                        <option value="">Select</option>
                        <option value="yes" <?php if($refund =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($refund =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    @error('refund')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
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
                    @error('discount')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="rewards">Rewards <span class="text-red">*</span></label>
                    <select class="form-control" name="config[rewards]" id="rewards">
                        <option value="">Select</option>
                        <option value="yes" <?php if($rewards =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($rewards =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    @error('rewards')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
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
                    @error('blog')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="support_ticket">Support Ticket <span class="text-red">*</span></label>
                    <select class="form-control" name="config[support_ticket]" id="support_ticket">
                        <option value="">Select</option>
                        <option value="yes" <?php if($support_ticket =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($support_ticket =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    @error('support_ticket')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
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
                    @error('loyality_points')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="branches">Branches <span class="text-red">*</span></label>
                    <select class="form-control" name="config[branches]" id="branches">
                        <option value="">Select</option>
                        <option value="yes" <?php if($branches =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($branches =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    @error('branches')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
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
                    @error('auction')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="crm_integration">CRM Integration <span class="text-red">*</span></label>
                    <select class="form-control" name="config[crm_integration]" id="crm_integration">
                        <option value="">Select</option>
                        <option value="yes" <?php if($crm_integration =="yes"){ echo 'selected'; } ?> >Yes</option>
                        <option value="no" <?php if($crm_integration =="no"){ echo 'selected'; } ?> >No</option>
                    </select>
                    <span class="error"></span>
                    @error('crm_integration')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <input type="submit" class="btn btn-success fr" name="saveconfig" value="Save">
            </form>
        </div>

        <a href="{{ url('admin/clear-cache');}}" style="color:#fff;">Clear Cache</a>

    </div>   
</div>




    
    

@endsection
