
<div class="row">
              <div class="col-md-12">
                <div class="card overflow-hidden">
                  <div class="card-body">
                    <div class="card-header mb-4""><div class="card-title" style="margin-bottom: 10px; text-align: center;"><strong>Sales Invoice</strong></div></div>
                    

                    <div class="card-body pl-0 pr-0">
                      <div class="row">
                        
                       

                <table>
                <tr>
                <td><div class="col-sm-6">
                <span>Invoice No.</span><br>
                <strong>{{$order->order_id}}</strong>
                </div>
                </td>
                <td style=" text-align: right; ">
                <div class="col-sm-6 text-right">
                <span>Invoice Date</span><br>
                <strong>{{date('d M, Y',time())}}</strong><br>
                <span>Sale Date</span><br>
                <strong>{{date('d M, Y',strtotime($order->created_at))}}</strong>
                </div>
                </td>
                </tr>
                <tr>
                  <td>
                    <div class="col-lg-6 ">
                        <p class="h5 font-weight-bold">Bill From</p>
                        {{--  <address>
                        @if(isset($seller_address->store_name))  {{ $seller_address->store_name }}<br> @endif
                        @if(isset($seller_address->address)) {{ $seller_address->address }} @endif,@if(isset($seller_address_city['city'])) {{ $seller_address_city['city'] }}<br> @endif
                        @if(isset($seller_address_city['state'])) {{ $seller_address_city['state'] }}<br> @endif
                        @if(isset($seller_address_city['country'])) {{ $seller_address_city['country'] }} <br> @endif
                        <span>@if($seller_telecom->country_code!='null') {{ $seller_telecom->country_code }} @endif {{ $seller_telecom->value }}</span>
                        </address>--}}
                      </div>
                  </td>

                  <td style="text-align: right;">
                   <div class="col-lg-6 text-right">
                        <p class="h5 font-weight-bold">Bill To</p>
                        <address>
                        <span>{{ $info->first_name }} {{ $info->last_name }}</span><br>
                          @if(! is_null($customer_mst->user_address_sale($customer_mst->id,$order->id)))
                            @foreach($customer_mst->user_address_sale($customer_mst->id,$order->id) as $address)
                                
                                <span>{{ $address['address_1'] }}</span>
                                {{ $address['address_2'] }}<br>
                                @if(! is_null($address['city_data']))
                                @if(isset($address['city_data']['city'])){{ $address['city_data']['city'] }},@endif
                                @if(isset($address['city_data']['state'])){{ $address['city_data']['state'] }}@endif<br>
                                @if(isset($address['city_data']['country'])){{ $address['city_data']['country'] }}@endif<br>
                                @if($address['country_code']!='null') +{{ $address['country_code'] }} @endif {{ $address['phone'] }}
                                @endif
                                
                            @endforeach
                            @endif
                              
                        </address>
                      </div> 
                  </td>
                </tr>
                </table>

                      </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="row pt-4">
                      
                      
                    </div>
                    <div class="table-responsive push">

                      <table class="table table-bordered table-hover text-nowrap" style="width:100%;">
                        <tr class=" ">
                          <th class="text-center " ></th>
                          <th>Product</th>
                          <th class="text-center" >Qty</th>
                          <th class="text-right" >Unit Price</th>
                          <th class="text-right" >Amount</th>
                          <th class="text-right" >Discount Amount</th>
                        </tr>
                        @if($order_items && count($order_items) > 0)
                        @php $totals = $total_tax = $total_disc = $o= 0; @endphp
                                          @foreach($order_items as $row)
                                            @php $o++; $total= $row->price*$row->qty; @endphp
                        <tr>
                          <td class="text-center">{{ $o }}</td>
                          <td>
                            <p class="font-weight-semibold mb-1">{{ $row->prd_name }}</p>
                            <!-- <div class="text-muted">Logo and business cards design</div> -->
                          </td>
                          <td class="text-center">{{ $row->qty }}</td>
                          <td class="text-center">{{ $row->price }}</td>
                          <td class="text-center">{{ $total  }}</td>
                          <td class="text-right">{{ round($row->row_total) }}</td>
                        </tr>
                        @php $totals += $total;
                        $total_tax += $row->tax;
                        $total_disc += $row->discount;
                        
                         @endphp
                        @endforeach
                                          @endif
                          @php $currency = getCurrency()->name; @endphp
                        <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Subtotal</td>
                          <td class="text-right"> {{$currency}} {{ round($totals); }}</td>
                        </tr>
                        <tr>
                          
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Tax</td>
                          <td class="text-right">{{$currency}} {{ round($order->tax); }}</td>
                        </tr>
                        
                                                @if(isset($order->bid_charge) && ($order->bid_charge>0))
                                                <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Bid Charge </td>
                          <td class=" text-right ">{{$currency}} {{ round($order->bid_charge);  }}</td>
                        </tr>
                         @endif
                          @if(isset($order->wallet_amount) && ($order->wallet_amount>0))
                        <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Wallet Amount </td>
                          <td class=" text-right">{{$currency}} {{ round($order->wallet_amount);  }}</td>
                        </tr>
                         @endif
                        <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Shipping Charge </td>
                          <td class="text-right">{{$currency}} {{ round($order->shiping_charge);  }}</td>
                        </tr>
                        <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-semibold text-right">Discount</td>
                          <td class="text-right">{{$currency}} {{ round($total_disc);  }}</td>
                        </tr>
                        <tr>
                          <td colspan="5" style="text-align: right;" class="font-weight-bold text-uppercase text-right h4 mb-0">Grand Total </td>
                          <td class="font-weight-bold text-right h4 mb-0">{{$currency}} {{ round($order->g_total);  }}</td>
                        </tr>
                        
                      </table>
                    </div>
                    

                    <p class="text-muted text-center"><center>Email:bigbasket@gmail.com</center></p>
                  </div>
                </div>
              </div>
            </div>


<style type="text/css">
p.addr_p {
margin-bottom: 0px;
}
.plabels {
  display: flex;
}
.plabels p {
  margin: 0;
}
</style>            
<style>
table {  font-family: arial, sans-serif;  border-collapse: collapse;  width: 100%;}

td, th {   text-align: left;  padding: 8px;}
.table { margin-top: 20px; }
.table td,.table th {border: 1px solid #dddddd; }

</style>