@extends('front.layout.default_layout')
@section('content')

<!--===============  section =====================-->

<!-- Banner Code Start -->

@include('front.layout.banner')

<!-- Banner Code End -->

<!--======================   breadcumbs =======================-->
<div class="container-fluid  mb-3 " style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
    <div class="row px-5 maxWidhtContainer">
        <div class=" col-12 align-self-center col-sm-12 col-md-12  h6 m-0  text-white">
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item text-white">Return Order</li>
                </ol>
            </nav>
        </div>
    </div>


</div>

<div class="container">
    <div class="row mb-5">
        <div class="col-md-3 my-4">
            <div class="bg4 py-4">
                <div class="text-white ">
                    <div class="text-center h5">WELCOME</div>
                    <div class="text-center h4"><?php echo Auth::user()->name; ?></div>
                </div>
                <ul class="list-unstyled m-0   dashboardLi">
                    <li class=""><a href="{{url('dashboard')}}" class="colorwhite color0-hov ">
                            <i class="fa fa-user"></i> MY PROFILE
                        </a>
                    </li>
                    <li class=""><a href="{{url('yourOrder')}}" class="colorwhite color0-hov">
                            <i class="fa fa-shopping-bag"></i> ORDERS
                        </a>
                    </li>

                    <li class="p-t-10 p-b-10"><a href="{{url('cancleOrder')}}" class="colorwhite color0-hov">
                            <i class="fa fa-close"></i> CANCEL ORDERS
                        </a>
                    </li>

                    <li class="dashLiactive p-t-10 p-b-10"><a href="{{url('returnOrder')}}" class="colorwhite color0-hov">
                            <i class="fa fa-retweet"></i> RETURN ORDERS
                        </a>
                    </li>

                    <li class="p-t-10 p-b-10"><a href="{{url('exchangeOrder')}}" class="colorwhite color0-hov">
                            <i class="fa fa-retweet"></i> EXCHANGE ORDERS
                        </a>
                    </li>

                    <li class="p-t-10 p-b-10"><a href="{{url('wishlist')}}" class="colorwhite color0-hov">
                            <i class="fa fa-heart"></i> WISHLIST
                        </a>
                    </li>

                    <li class="p-t-10 p-b-10"><a href="{{url('shippingAddress')}}" class="colorwhite color0-hov">
                            <i class="fa fa-map-marker"></i> SHIPPING ADDRESS
                        </a>
                    </li>
                    <li class=" p-t-10 p-b-10"><a href="{{url('billingAddress')}}" class="colorwhite color0-hov color0">
                            <i class="fa fa-map-marker"></i> BILLING ADDRESS
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="col-md-9 my-4">
            <div class="headDash">
                MY RETURN ORDER
            </div>
            @if(!$returnData->isEmpty())
            @foreach($returnData as $data)
            @if($data->order_type != "DESIGN-SHIRT")
            <div class="border mb-3">
                <div class="bg4">
                    <div class="row profileHead">
                        <div class="col-12 col-sm-6 text-white ">
                            Order Number : <span>{{$data->order_number}}</span>
                        </div>

                        <!-- <div class="col-12 col-sm-6 text-white ">
                            Tracking Number : <span>{{$data->tracking_number}}</span>
                        </div>-->
                    </div>
                </div>

                <div class='col-12 ' style="padding:15px auto;">

                    <div class="row  py-1" style="font-size: 16px;">
                        <div class="col-8 ">Items</div>
                        <div class="col-2 d-none d-sm-block  text-right">Unit Price</div>
                        <div class="col-2 d-none d-sm-block  text-right">Net Amount</div>
                    </div>
                    <?php
                    $proDetails = json_decode($data->product_details);
                    ?>
                    @foreach($proDetails as $key => $pros)
                    <div class="row py-1 my-2 border-top border-bottom">
                        <div class="col-4 col-sm-2 p-0">
                            <img src="{{URL::asset('public/upload/product/'.$proImage[$key])}}" width="100%">
                        </div>
                        <?php
                        $pro_Det = explode('-',$pros);
                        
                        ?>
                        <div class="col-8 col-sm-6  py-2">
                            <div class="h6 font-weight-normal m-0"><b>{{$proTitle[$key]}}</b></div>

                            @if($pro_Det[0] != "Self:self")
                            <div class="py-3 row " style="font-size:13px;">
                                <div class="col-12 col-sm-6  text-danger">
                                    ({{$pro_Det[0]}})
                                </div>
                            </div>
                            @endif
                            <div class="pb-3 row " style="font-size:13px;">
                                <div class="col-12 col-sm-6">Quantity : {{$pro_Det[1]}} </div>
                            </div>
                        </div>
                        <?php $currenceType = $data->amount_type; ?>
                        <div class="col-6 col-sm-2  untPrcSm py-2 text-right">
                            <div class="d-block d-sm-none" style="font-size: 13px;">Unit Price</div>
                            @if($currenceType == "rupee_price")
                            <div> <i class="fa fa-inr pr-2"></i><span>{{$pro_Det[2]}}</span></div>
                            @elseif($currenceType == "dollar_price")
                            <div> <i class="fa fa-usd pr-2"></i><span>{{$pro_Det[2]}}</span></div>
                            @elseif($currenceType == "euro_price")
                            <div> <i class="fa fa-eur pr-2"></i><span>{{$pro_Det[2]}}</span></div>
                            @else
                            <div> <i class="fa fa-usd pr-2"></i><span>{{$pro_Det[2]}}</span></div>
                            @endif

                        </div>
                        <div class="col-6 col-sm-2 untPrcSm  py-2 text-right ">
                            <div class="d-block d-sm-none" style="font-size: 13px;">Sub- Total</div>
                            @if($currenceType == "rupee_price")
                            <div> <i class="fa fa-inr pr-2"></i><span>{{$pro_Det[2] * $pro_Det[1]}}</span></div>
                            @elseif($currenceType == "dollar_price")
                            <div> <i class="fa fa-usd pr-2"></i><span>{{$pro_Det[2] * $pro_Det[1]}}</span></div>
                            @elseif($currenceType == "euro_price")
                            <div> <i class="fa fa-eur pr-2"></i><span>{{$pro_Det[2] * $pro_Det[1]}}</span></div>
                            @else
                            <div> <i class="fa fa-usd pr-2"></i><span>{{$pro_Det[2] * $pro_Det[1]}}</span></div>
                            @endif
                        </div>


                    </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-12 col-sm-6 ">
                        <div class="d-flex pad justify-content-between t-left">
                            <div class="font-weight-bold w-full">Order Date : </div>
                            <div class='t-left w-full'><span>{{date('d/M/Y - h:i A ', strtotime($data->order_date))}}</span></div>
                        </div>

                        <!--<div class="d-flex pad2 justify-content-between t-left">
                            <div class="font-weight-bold w-full">Invoice : </div>
                            <div class='t-left w-full'><a href="{{url('printInvoice',$data->order_number)}}" target="_blank"><span class="color0 btn p-0 m-0">Print</span></a></div>
                        </div>-->
                        <div class="d-flex pad2 justify-content-between t-left">
                            <div class="font-weight-bold w-full">Order Status : </div>
                            <div class='t-left w-full'><span>{{$data->order_status}}</span></div>
                        </div>
                        @if($data->user_report != "")
                        <div class="d-flex pad2 justify-content-between t-left">
                            <div class="font-weight-bold w-full">Order Action By You : </div>
                            <div class='t-left w-full'><span>{{$data->user_report}}</span></div>
                        </div>
                        @endif

                    </div>
                    <div class="col-12 col-sm-6 ">
                        <div class="d-flex pad justify-content-between t-left" style="border-top:2px solid black;">
                            <div class=" w-full">Net Amount : </div>
                            <?php $currenceType = $data->amount_type; ?>
                            @if($currenceType == "rupee_price")
                            <div class='t-left w-full'><span><i class="fa fa-inr"></i>{{$data->total_amount}}</span>/-</div>
                            @elseif($currenceType == "dollar_price")
                            <div class='t-left w-full'><span><i class="fa fa-usd"></i>{{$data->total_amount}}</span>/-</div>
                            @elseif($currenceType == "euro_price")
                            <div class='t-left w-full'><span><i class="fa fa-eur"></i>{{$data->total_amount}}</span>/-</div>
                            @else
                            <div class='t-left w-full'><span><i class="fa fa-usd"></i>{{$data->total_amount}}</span>/-</div>
                            @endif
                        </div>
                        @if($data->discount > 0)
                        <div class="d-flex pad2 justify-content-between t-left">
                            <div class=" w-full">Coupon Discount ({{$data->coupon_code}} with {{$data->coupon_discount}}%): </div>
                            <?php $currenceType = $data->amount_type; ?>
                            @if($currenceType == "rupee_price")
                            <div class='t-left w-full'><span class=" btn p-0 m-0"><i class="fa fa-inr"></i>{{$data->discount}}</span>/-</div>
                            @elseif($currenceType == "dollar_price")
                            <div class='t-left w-full'><span class=" btn p-0 m-0"><i class="fa fa-usd"></i>{{$data->discount}}</span>/-</div>
                            @elseif($currenceType == "euro_price")
                            <div class='t-left w-full'><span class=" btn p-0 m-0"><i class="fa fa-eur"></i>{{$data->discount}}</span>/-</div>
                            @else
                            <div class='t-left w-full'><span class=" btn p-0 m-0"><i class="fa fa-usd"></i>{{$data->discount}}</span>/-</div>
                            @endif
                        </div>
                        @endif
                        <div class="d-flex pad2 justify-content-between t-left">
                            <div class=" w-full">Shippings : </div>
                            <?php $currenceType = $data->amount_type; ?>
                            @if($currenceType == "rupee_price")
                            <div class='t-left w-full'><span><i class="fa fa-inr"></i>{{$data->shipping}}</span>/-</div>
                            @elseif($currenceType == "dollar_price")
                            <div class='t-left w-full'><span><i class="fa fa-usd"></i>{{$data->shipping}}</span>/-</div>
                            @elseif($currenceType == "euro_price")
                            <div class='t-left w-full'><span><i class="fa fa-eur"></i>{{$data->shipping}}</span>/-</div>
                            @else
                            <div class='t-left w-full'><span><i class="fa fa-usd"></i>{{$data->shipping}}</span>/-</div>
                            @endif
                        </div>
                        <!--<div class="d-flex pad2 justify-content-between t-left">
                            <div class=" w-full">GST : </div>
                            <div class='t-left w-full'><span>{{$data->product_gst}}</span>/-</div>
                        </div>-->
                        <div class="d-flex font-weight-bold pad2 justify-content-between t-left">
                            <?php $currenceType = $data->amount_type; ?>
                            @if($currenceType == "rupee_price")
                            <div class=" w-full"><b>Total <i class="fa fa-inr"></i>.</b><small>( With Round )</small>: </div>
                            @elseif($currenceType == "dollar_price")
                            <div class=" w-full"><b>Total <i class="fa fa-usd"></i>.</b><small>( With Round )</small>: </div>
                            @elseif($currenceType == "euro_price")
                            <div class=" w-full"><b>Total <i class="fa fa-eur"></i>.</b><small>( With Round )</small>: </div>
                            @else
                            <div class=" w-full"><b>Total <i class="fa fa-usd"></i>.</b><small>( With Round )</small>: </div>
                            @endif
                            @if($currenceType == "rupee_price")
                            <div class='t-left w-full'><span><i class="fa fa-inr"></i>{{$data->total_amount}}</span>/-</div>
                            @elseif($currenceType == "dollar_price")
                            <div class='t-left w-full'><span><i class="fa fa-usd"></i>{{$data->total_amount}}</span>/-</div>
                            @elseif($currenceType == "euro_price")
                            <div class='t-left w-full'><span><i class="fa fa-eur"></i>{{$data->total_amount}}</span>/-</div>
                            @else
                            <div class='t-left w-full'><span><i class="fa fa-usd"></i>{{$data->total_amount}}</span>/-</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="border mb-3">
                <div class="bg4">
                    <div class="row profileHead">
                        <div class="col-12 col-sm-6 text-white ">
                            Order Number Shirt : <span>{{$data->order_number}}</span>
                        </div>

                        <div class="col-12 col-sm-6 text-white ">
                            Tracking Number : <span>{{$data->tracking_number}}</span>
                        </div>
                    </div>
                </div>

                <div class='col-12 ' style="padding:15px auto;">
                    <div class="row  py-2" style="font-size: 16px;">
                        <div class="col-12 pt-2"><b>CUSTOMIZE SHIRT DETAILS</b></div>
                        <!--<div class="col-2 d-none d-sm-block  text-right"></div>
                        <div class="col-2 d-none d-sm-block  text-right"></div>-->
                    </div>
                    <?php
                    $proDetails = json_decode($data->product_details);
                    
                    
                    ?>
                    <div class="row py-1 my-2 border-top border-bottom">
                        @foreach($proDetails as $key => $dt)
                        <div class="col-3 col-sm-2 p-0">
                            <div class="text-center border"><b>{{$key}}</b></div>
                            <div class="text-center border">
                                @if($key == "febric" || $key == "FEBRIC" || $key == "Febric")
                                <img src="{{URL::asset('public/upload/shirt/'.$febricImage[$dt])}}" width="100%">
                                @else
                                <img src="{{URL::asset('public/upload/shirt/'.$elementValueImage[$dt])}}" width="100%">
                                @endif
                            </div>
                            <div class="text-center border">
                                @if($key == "febric" || $key == "FEBRIC" || $key == "Febric")
                                {{$febricName[$dt]}}
                                @else
                                {{$elementValueName[$dt]}}
                                @endif
                            </div>
                        </div>
                        @endforeach



                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-6 ">
                        <div class="d-flex pad justify-content-between t-left">
                            <div class="font-weight-bold w-full">Order Date : </div>
                            <div class='t-left w-full'><span>{{date('d/M/Y - h:i A ', strtotime($data->order_date))}}</span></div>
                        </div>

                        <div class="d-flex pad2 justify-content-between t-left">
                            <div class="font-weight-bold w-full">Invoice : </div>
                            <div class='t-left w-full'><a href="{{url('printInvoice',$data->order_number)}}" target="_blank"><span class="color0 btn p-0 m-0">Print</span></a></div>
                        </div>
                        <div class="d-flex pad2 justify-content-between t-left">
                            <div class="font-weight-bold w-full">Order Status : </div>
                            <div class='t-left w-full'><span>{{$data->order_status}}</span></div>
                        </div>
                        @if($data->user_report == "")
                        <div class="d-flex pad2 justify-content-between t-left">
                            <div class="font-weight-bold w-full">Click For Order Action : </div>
                            <div class='t-left w-full'>
                                @if($data->order_status == "Accepted" && $data->user_report != "CANCEL ORDER")
                                <div onclick="changeorder('CANCEL ORDER','{{$data->order_number}}')" class=" btn btn-dark m-0 prcPay2" data-toggle="modal" data-target="#cancelOrder">Cancel Order</div>

                                @elseif($data->order_status == "Dispatch" && $data->user_report == "")
                                <a href="#">
                                    <div class=" btn btn-dark m-0 prcPay2">Track Order</div>
                                </a>

                                @elseif($data->order_status == "Delivered" && ($data->user_report != "RETURN ORDER" || $data->user_report != "EXCHANGE ORDER") )

                                <div onclick="changeorder('RETURN ORDER','{{$data->order_number}}')" class=" btn btn-dark prcPay2" data-toggle="modal" data-target="#cancelOrder">Return Order</div>
                                <div onclick="changeorder('EXCHANGE ORDER','{{$data->order_number}}')" class="btn btn-dark m-t-10 prcPay2" data-toggle="modal" data-target="#cancelOrder">Exchange Order</div>


                                @else
                                <div class="col-6 btn m-0 py-0 text-right">
                                    Waiting For Order Accept
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($data->user_report != "")
                        <div class="d-flex pad2 justify-content-between t-left">
                            <div class="font-weight-bold w-full">Order Action By You : </div>
                            <div class='t-left w-full'><span>{{$data->user_report}}</span></div>
                        </div>
                        @endif
                    </div>
                    <div class="col-12 col-sm-6 ">
                        <div class="d-flex pad justify-content-between t-left" style="border-top:2px solid black;">
                            <div class=" w-full">Net Amount : </div>
                            <div class='t-left w-full'><span>{{$data->total_amount}}</span>/-</div>
                        </div>

                        <div class="d-flex font-weight-bold pad2 justify-content-between t-left">
                            <div class=" w-full"><b>Total Rs.</b><small>( With Round )</small>: </div>
                            <div class='t-left w-full'><span>{{$data->total_amount}}</span>/-</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            <div class="row my-4">
                <nav class="col-12" aria-label="Page navigation example">
                    <ul class="pagination">
                        {{ $returnData->links() }}
                    </ul>
                </nav>
            </div>
            @else
            <div class="px-4 col-12 py-3 m-0 text-danger border">No Product in Wishlist.</div>
            @endif
        </div>




    </div>
</div>
<div class="modal fade" id="cancelOrder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Order Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('cancleOrderByUserSave')}}" method="post" name="form">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="user_report" value="CANCEL ORDER" id="cancle_order">

                    <input type="hidden" name="order_number" id="ordNumber">

                    <div>Ops! We are sorry to hear that. Request you to please share the reason for the <span id="change_Order_text">cancelation</span>.</div>
                    <br>
                    <textarea rows="5" cols="10" style="resize:none;" name="user_description" class="form-control rounded-0" required></textarea>

                </div>
                <div class="modal-footer">
                    <div class="w-size2 ">
                        <!-- Button -->
                        <button type="submit" class="flex-c-m size2 bg4 bo-rad-23 hov1 m-text3 trans-0-4 btn text-white">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--===================  end section  ====================-->


@stop
