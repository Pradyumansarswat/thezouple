@extends('front.layout.default_layout')
@section('content')
<div class="container">
    <section>
            <!--//////////////////////////   content ///////////////////////-->
            <section class="border border-dark  my-3">
                <div class="container py-2 px-5">
                    <div class="row">
                        <div class="col-12 h1 text-center pt-4">
                            <u>INVOICE</u>
                        </div>
                    </div>
                    <div class="row m-auto">
                        <div class="col-md-6 align-self-center">
                            <img src="{{URL::asset('public/front/images/logo.png')}}" width="200px" alt="logo">
                        </div>
                        <div class="col-md-6 align-self-center">
                            <div class="h5">Earthly Plush</div>
                            <div class="h6">Malviya Nagar , Jaipur</div>
                            <div class="h6">Rajasthan, 302025</div>
                            <div class="h6">India</div>
                        </div>
                    
                    </div>
                     <div class="row py-4">
                        <div class="col-md-6 align-self-center ">
                            <div class="h5">Invoice to</div>
                             @foreach($billing_add as $data)
                                <div class="h6">{{$data->address_name}}</div>
                                <div class="h6">{{$data->address}}</div>
                                <div class="h6"> {{$data->city_name}}</div>
                                <div class="h6">{{$data->state_name}}, {{$data->pin}}</div>
                                <div class="h6">{{$data->country_name}}</div>
                            @endforeach
                        </div>
                        <div class="col-md-6 align-self-center">
                            <div class="h5">Ship to</div>
                            @foreach($shipping_add as $data)
                                <div class="h6">{{$data->address_name}}</div>
                                <div class="h6">{{$data->address}}</div>
                                <div class="h6"> {{$data->city_name}}</div>
                                <div class="h6">{{$data->state_name}}, {{$data->pin}}</div>
                                <div class="h6">{{$data->country_name}}</div>
                            @endforeach
                        </div>
                    
                    </div>
                    @foreach($order_data as $data)
                    <div class="row py-4">
                        <div class="col-md-6 align-self-center ">
                            <div class="h3">Invoice</div>
                            <div class="h5" style="color:#8cc542;"><span>000{{$data->order_id}}</span> / <span>{{date('Y')}}</span></div>
                            
                             <div class="h6">Order Number: <span>{{$data->order_number}}</span></div>
                            <div class="h6">Order Date: <span>{{date('d/M/Y',strtotime($data->order_date))}}</span></div>
                        </div>
                        <div class="col-md-5 align-self-center text-white" style="background-color:#8cc542;">
                            <div class="h3">
                                <i class="fa fa-inr pr-4"></i><span> @foreach($order_data as $data) {{$data->amount}}  @endforeach</span>/-
                            </div>
                            <div class="text-justify">
                                Thank you for your purchase. If you have any queries about your purchase or invoice, please feel free to contact us at your convenience. We will reply to you as soon as we get your message.
                            </div>
                        </div>
                    
                    </div>
                    @endforeach
                    <div class="row py-2">
                        <div class="col-12">
                            <table class="table table-striped table-responsive-sm invoice table-borderd">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Product Name</th>
                                            <th>Cost (<i class="fa fa-inr px-1"></i>)</th>
                                            <th>Qty</th>
                                            
                                            <th>Total(<i class="fa fa-inr px-1"></i>)</th>
                                        </tr>
                                
                                    </thead>
                                <tbody>
                                    <?php
                                    $count = sizeof($pro_id);
                                    for($i=0;$i<$count;$i++)
                                    {
                                    ?>
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$pro_name[$i]}}</td>
                                        <td><span>{{$pro_price[$i]}}</span>/-</td>
                                        <td>{{$pro_qty[$i]}}</td>
                                        
                                        <td><span>{{$pro_price[$i] * $pro_qty[$i]}}</span>/-</td>
                                    </tr>
                                     <?php } ?>
                                </tbody>
                            
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="h5">Optional Assessories </div>
                            <table class="table table-striped table-responsive-sm invoice table-borderd invoice">
                                <thead>
                                    <th>Sr. No.</th>
                                    <th>Product Details</th>
                                    
                                </thead>
                                <tbody>
                                    <?php for($j=0;$j<$count;$j++)
                                    {
                                    ?>

                                    <tr>
                                        <td>{{$j+1}}</td>
                                        <td>{{$accessName[$pro_opt[$j]]}} with {{$pro_name[$j]}}</td>
                                        
                                    </tr>
                                    <?php 
    
                                        }
                                    ?>  
                                </tbody>
                            </table>
                            
                        </div>
                        @foreach($order_data as $data)
                        <div class="col-md-6  p-4 ">
                             <div class="d-flex justify-content-between h6">
                                <div class="">Net Amount</div>
                                <div class=""><i class="fa fa-inr pr-2"></i><span>{{$data->net_amount}}</span>/-</div>
                            </div>
                            <div class="d-flex justify-content-between h6">
                                <div class="">Discount  @if(isset($data->offer_code))Coupen Code - {{$data->offer_code}} @endif</div>
                                <div class=""><i class="fa fa-inr pr-2"></i><span>{{$data->discount}}</span>/-</div>
                            </div>
                            <div class="d-flex justify-content-between h6">
                                <div class="">Shipping</div>
                                <div class=""><i class="fa fa-inr pr-2"></i><span>{{$data->shipping}}</span>/-</div>
                            </div>
                           
                            <div class="d-flex justify-content-between h6">
                                <div class="">GST</div>
                                <div class=""><i class="fa fa-inr pr-2"></i><span>{{$data->gst_amount}}</span>/-</div>
                            </div>
                            <hr style="width:100%; margin: 8px 0px; border:1px solid #8cc542;">
                            
                            <div class="d-flex justify-content-between h5">
                                <div class="">Totat Amount</div>
                                <div class=""><i class="fa fa-inr pr-2"></i><span>{{$data->amount}}</span>/-</div>
                            </div>
                            <!--<div class="d-flex justify-content-between h6 text-danger">
                                <div class="">Refunded</div>
                                <div class=""><i class="fa fa-inr pr-2"></i><span>50</span>/-</div>
                            </div>-->
                        </div>  
                        @endforeach
                    </div>
                    <hr style="border:2px solid #8cc542;">
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <div>
                               <span class="h6"> Email:-</span> wecare@earthlyplush.com
                         </div>  
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="">
                                <span class="h6">Website:-</span> www.earthlyplush.com
                            </div>
                        </div>
                    </div>
                </div>
            </section> 
    </section>   
    </div>
@stop

        
        