@extends('front.layout.default_layout')
@section('content')

<!-- Banner Code Start -->

@include('front.layout.banner')

<!-- Banner Code End -->
<!--======================   breadcumbs =======================-->
<div class="container-fluid   mb-5" style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
    <div class="row px-5 maxWidhtContainer">
        <div class=" col-12 align-self-center col-sm-12 col-md-12  h5 m-0  text-white" style="letter-spacing: 4px; ">
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item text-white">{{$title}}</li>
                </ol>
            </nav>
        </div>
    </div>


</div>



<div class="container mb-5">
    <div class="row">
        <div class="col-sm-9 col-12 border-right">
            @foreach($blogs_datas as $blogs)
            <div class="row">
                <div class="col-md-6 my-4">
                    <img src="{{URL::asset('public/upload/blog/'.$blogs->image)}}" width="100%" alt="img" height="400px;">
                </div>
                <div class="col-md-6 my-4 align-self-center ">
                    <div class="h3 text-white p-5 position-relative shadow aboutContent" style="background-color:#2C2C2C; left:-100px;">
                        "{{$blogs->heading}}"

                    </div>
                </div>
            </div>
            @endforeach
            <div class="row my-3 ">
                @foreach($blogs_datas as $blogs)
                <div class="col-md-12 col-sm-12 col-12 card border-0 text-justify">
                    <div class="card-body p-2">

                        <p>
                            <?php echo $blogs->description; ?>
                        </p>
                    </div>

                    <div class="card-footer text-white  border-0 text-right " style="background-color:black;">
                        Posted: <?php
    
                                $date = $blogs->date; 
    
                                $date=date_create($date);
                                echo date_format($date,"M, d Y");
    
                                ?>
                    </div>
                </div>
                @endforeach
            </div>
        </div>


        <div class="col-md-3 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-sm-6 py-2 px-2 bg-dark text-white">
                    My Wishlist
                </div>
            </div>

            @if(isset(Auth::user()->id))
            @if(!$wishs_lists->isEmpty())
            @foreach($wishs_lists as $wish)

            <div class="col-md-12  col-sm-6 d-flex py-3">
                <div class="col-4 p-0">
                    <img src="{{URL::asset('public/upload/product/'.$wish->product_header_image)}}" alt="IMG" width="100%">
                </div>

                <div class="col-8 pr-0">
                    <a href="#" class="text-dark" style="font-size:14px;">
                        {{$wish->product_title}}
                    </a>
                    <?php 
                            $currencySession = Session::get('currency');
                            
                             if($currencySession == "rupee_price")
                             {
                                 $iicon = "fa fa-inr";
                                 $netAmount = round($wish->rupee_net_with_gst);
                             }
                             elseif($currencySession == "dollar_price")
                             {
                                 $iicon = "fa fa-usd";
                                 $netAmount = round($wish->doller_net_with_gst);
                             } 
                             elseif($currencySession == "euro_price")
                             {
                                 $iicon = "fa fa-eur";
                                 $netAmount = round($wish->euro_net_with_gst);
                             }
                             else
                             {
                                $iicon = "fa fa-usd";
                                $netAmount = round($wish->doller_net_with_gst);
                             }

                             $subTotal = $wish->product_qty * $netAmount;
                        ?>
                    <div class="">
                        <i class="fa fa-inr pr-2 align-self-center"></i>
                        {{$netAmount}}
                    </div>

                    <span class=" d-flex justify-content-between align-self-end" style="font-size:12px!important;">
                        <span class='btn  m-0 px-1 py-0 prdBtn'><a href="{{url('productShow', $wish->slug)}}">Move To Cart</a></span>
                        <span class='btn m-0 px-1 py-0 prdBtn'><a href="{{url('wishDelete', $wish->wishlist_id)}}" onClick="return confirm('Are you sure?');">Remove</a></span>
                    </span>
                </div>
            </div>
            @endforeach
            @else
            <div class="px-4 col-sm-12 col-12 pt-3 m-0">No Product in Wishlist.</div>
            @endif
            @else
            <div class="px-4 col-sm-12 col-12 pt-3 m-0">Please <a href="#" data-target="#logSign" data-toggle="modal">Login</a> for your Wishlist.</div>
            @endif
        </div>



    </div>

</div>





</section>

@stop
