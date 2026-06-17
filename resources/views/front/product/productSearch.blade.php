@extends('front.layout.default_layout')
@section('content')

<script>
    function showAssessories(id) {
        $('#ass_product_id').val(id);
        $.ajax({
            url: 'getAssessories/' + id,
            type: "GET",
            beforeSend: function() {
                $('#wait').show();
            },
            complete: function() {
                $('#wait').hide();
            },
            success: function(data) {
                $('#asscessoiresData').html("");
                $('#asscessoiresData').html(data.product_Asscess);
            }
        });
    }

</script>

<!-- Banner Code Start -->

@include('front.layout.banner')

<!-- Banner Code End -->
<!--======================   breadcumbs =======================-->
<div class="container-fluid  mb-3 " style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
    <div class="row px-0 px-sm-5 maxWidhtContainer">
        <div class=" col-12 align-self-center col-sm-5 col-md-6  h6 m-0  text-white" >
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.html" class=" h6 m-0 text-white">{{$page_head}}</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>






<!--======================  content of product listing ===============-->
<div class="container-fluid px-0 px-sm-5">
    <div class="row px-0 px-5">
        @if(isset($products))
        @foreach($products as $data)
        <?php
        
    ?>
        <div class="col-sm-2 col-md-2 col-6 my-4 ">
            <a href="{{url('product', $data->slug)}}">
                 <div class="card " style="border-radius: 15px; overflow: hidden;">
                                <div class="card-body p-0 position-relative">
                        <img src="{{URL::asset('public/upload/product/'.$data->product_header_image)}}" width="100%">


                        

                    </div>
                    <?php 
                                $currencySession = Session::get('currency');

                                 if($currencySession == "rupee_price")
                                 {
                                     $iicon = "fa fa-inr";
                                     $proPrice = $data->rupee_price;
                                     $netAmount = $data->rupee_net_amount;
                                     $finalAmount = round($data->rupee_net_with_gst);

                                 }
                                 elseif($currencySession == "dollar_price")
                                 {
                                     $iicon = "fa fa-usd";
                                     $proPrice = $data->dollar_price;
                                     $netAmount = $data->doller_net_amount;
                                     $finalAmount = round($data->doller_net_with_gst);
                                 } 
                                 elseif($currencySession == "euro_price")
                                 {
                                     $iicon = "fa fa-eur";
                                     $proPrice = $data->euro_price;
                                     $netAmount = $data->euro_net_amount;
                                     $finalAmount = round($data->euro_net_with_gst);
                                 }
                                 else
                                 {
                                    $iicon = "fa fa-usd";
                                    $proPrice = $data->dollar_price;
                                     $netAmount = $data->doller_net_amount;
                                     $finalAmount = round($data->doller_net_with_gst);
                                 }
                                $maxAmount = round(($proPrice * ($data->product_gst / 100)) + $proPrice);
                                ?>
                    <a href="{{url('product', $data->slug)}}">
                        <div class="card-footer text-white" style="background-color:black;">
                                        <div class="" style="bottom:10%;">
                                            @if($netAmount != $proPrice)
                                            <span style="font-size: 14px!important;"><i class="{{$iicon}} text-white pr-2"></i><span>{{$finalAmount}}</span>/-</span>

                                            <span class="text-secondary py-2 " style="text-decoration: line-through;"><i class="{{$iicon}}  pr-2"></i><span>{{$maxAmount}}</span>/-</span>
                                            @else
                                            <span style="font-size: 14px!important;" class=""><i class="{{$iicon}} text-white pr-2"></i><span>{{$finalAmount}}</span>/-</span>
                                            @endif
                                        </div>


                                        <div class="font-weight-normal m-0 text-white proTitleText" style="font-size:14px">
                                            <?php
                                            $des = $data->product_title;
                                            echo $description = Str::words($des, '4');
                                        ?>
                                        </div>


                                    </div>
                    </a>

                </div>
            </a>
        </div>
        @endforeach
        @else
        <div class="col-sm-12 pt-4 px-4 mt-3">
            No Record Found
        </div>
        @endif
    </div>
</div>


@stop
