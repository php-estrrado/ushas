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
        
        <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="fname">App name <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="config[app_name]" id="fname" placeholder="App name"  required>
                    <span class="error"></span>
                    @error('fname')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lname">Base URL <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="config[base_url]" id="lname" placeholder="Base URL"  required>
                    <span class="error"></span>
                    @error('lname')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="m_lang">Multi-language <span class="text-red">*</span></label>
                    <select class="form-control" name="config[m_lang]" id="m_lang">
                        <option value="">Select</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
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
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
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
                        <option value="from_api">Price from API</option>
                        <option value="manual">Manual Update</option>
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
                        <option value="stripe">Stripe</option>
                        <option value="other">Other</option>
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
                        <option value="fedex">Fedex</option>
                        <option value="custom">Custom</option>
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
                        <option value="flow_1">Flow 1</option>
                        <option value="flow_2">Flow 2</option>
                    </select>
                    <span class="error"></span>
                    @error('return_flow')
                    <p style="color: red">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <input type="submit" class="btn btn-success fr" name="saveconfig" value="Save">
            </form>
        </div>
    </div>   
</div>




    
    

@endsection
