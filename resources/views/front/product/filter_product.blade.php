<div class="row">
    @foreach($products as $data)

    <?php
    $discount = $data->rupee_net_amount;
    ?>
    <div class="col-sm-3 col-md-3 col-6 my-4">
        <a href="{{url('product', $data->slug)}}">
            <div class="card " style="border-radius: 15px; overflow: hidden;">
                <div class="card-body p-0 position-relative">
                    <img src="{{URL::asset('public/upload/product/'.$data->product_header_image)}}" width="100%">



                </div>
                <a href="{{url('product', $data->slug)}}">
                    <div class="card-footer text-white" style="background-color:black;">

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
                             $netAmount = $data->dollar_net_amount;
                             $finalAmount = round($data->dollar_net_with_gst);
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
                             $netAmount = $data->dollar_net_amount;
                             $finalAmount = round($data->dollar_net_with_gst);
                         }
                        $proDiscount = $data->product_discount;
                        $maxAmount = round(($proPrice * ($data->product_gst / 100)) + $proPrice);
                        ?>

                        <div class="" style="bottom:10%;">
                            @if($proDiscount > 0)
                            <span style="font-size: 14px!important;"><i class="{{$iicon}} text-white pr-1"></i><span>{{$finalAmount}}</span>/-</span>

                            <span class="text-secondary py-2 ml-2" style="text-decoration: line-through;font-size: 14px!important;"><i class="{{$iicon}} pr-1"></i><span>{{$maxAmount}}</span>/-</span>
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
</div>
