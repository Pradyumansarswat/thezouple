@extends('front.layout.default_layout')
@section('content')
<script>
    function check_pincodeStatus() {
        var pincode = $('#pincode').val();
        $.ajax({
            url: '../check_pincodeStatus',
            type: "GET",
            beforeSend: function() { $('#wait').show(); },
            complete: function() { $('#wait').hide(); },
            data: "pincode=" + pincode,
            success: function(data) {
                if (data > 0) {
                    swal("Hurray!", 'We are delivering our products at this PINCODE.', "success");

                } else {
                    swal("Oops!", 'Currently we are not delivering our products on this PINCODE but we will start our services shortly.', "warning");

                }



            }
        });
    }


    function maxLengthCheck(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }

    function isNumeric(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }

    function addTocartloginJs() {
        var filter = new Array();
        var yesNow = 0;
        @foreach($att_val as $data)
        var m = $('#{{$data->attribute_name}}').val();
        if (m != "0") {
            /*alert(m);*/
            filter.push(m);
            yesNow = 1;
        } else {
            swal("Oops!", 'Please select {{$data->attribute_name}} of product', "warning");
            yesNow = 0;
        }

        @endforeach
        var pro_qty = $('#qty').val();
        var vendor_id = $('#vendor').val();
        var product_id = $('#product_id').val();
        var ip_address = $('#ip_address').val();
        if (yesNow > 0) {
            $.ajax({
                url: '../addJSCartloginProduct',
                type: "GET",
                beforeSend: function() { $('#wait').show(); },
                complete: function() { $('#wait').hide(); },
                data: "vendor_id=" + vendor_id + "&filter=" + filter + "&pro_qty=" + pro_qty + "&product_id=" + product_id + "&ip_address=" + ip_address,
                success: function(data) {
                    if (data['cart_status'] == 'No') {
                        swal("Product Added!", 'Product successfully added in your Cart.', "success");
                        setTimeout(reloadPage, 1500);
                    } else {
                        swal("Oops!", 'Product is already available in your cart.', "warning");

                    }
                }
            });
        }
    }



    function addTocartJs() {
        var filter = new Array();
        var yesNow = 0;
        @foreach($att_val as $data)
        var m = $('#{{$data->attribute_name}}').val();
        if (m != "0") {
            /*alert(m);*/
            filter.push(m);
            yesNow = 1;
        } else {
            swal("Oops!", 'Please select {{$data->attribute_name}} of product', "warning");
            yesNow = 0;
        }

        @endforeach
        var pro_qty = $('#qty').val();
        var vendor_id = $('#vendor').val();
        var product_id = $('#product_id').val();
        var ip_address = $('#ip_address').val();

        if (yesNow > 0) {
            $.ajax({
                url: '../addJSCartProduct',
                type: "GET",
                beforeSend: function() { $('#wait').show(); },
                complete: function() { $('#wait').hide(); },
                data: "vendor_id=" + vendor_id + "&filter=" + filter + "&pro_qty=" + pro_qty + "&product_id=" + product_id + "&ip_address=" + ip_address,
                success: function(data) {
                    if (data['cart_status'] == 'No') {
                        swal("Product Added!", 'Product successfully added in your Cart.', "success");
                        setTimeout(reloadPage, 1500);
                    } else {
                        swal("Oops!", 'Product is already available in your cart.', "warning");

                    }
                }
            });
        }
    }


    function buyNowAction() {
        var filter = new Array();
        var yesNow = 0;
        @foreach($att_val as $data)
        var m = $('#{{$data->attribute_name}}').val();
        if (m != "0") {
            /*alert(m);*/
            filter.push(m);
            yesNow = 1;
        } else {
            swal("Oops!", 'Please select {{$data->attribute_name}} of product', "warning");
            yesNow = 0;
        }

        @endforeach
        var pro_qty = $('#qty').val();
        var product_id = $('#product_id').val();
        var ip_address = $('#ip_address').val();

        if (yesNow > 0) {
            var url = "../buyNowFlashProduct?filter=" + filter + "&pro_qty=" + pro_qty + "&product_id=" + product_id;
            window.location = url;
        }


    }






    
    function showOutStock() {
        swal("Oops!", 'Your product is outstock.', "warning");
    }

    function reloadPage() {
        location.reload();
    }

    function changeFilter() {
        var filter = new Array();
        @foreach($att_val as $data)
        var m = $('#{{$data->attribute_name}}').val();
        if (m != "0") {
            /*alert(m);*/
            filter.push(m);
        }
        @endforeach
        var product_id = $('#product_id').val();
        var pro_qty = $('#qty').val();
        $.ajax({
            url: '../checkflashsalesFilterPrice',
            type: "GET",
            beforeSend: function() { $('#wait').show(); },
            complete: function() { $('#wait').hide(); },
            data: "filter=" + filter + "&pro_qty=" + pro_qty + "&product_id=" + product_id,
            success: function(data) {
               
                if (data['price'] == 0) {

                } else {
                    $('#price').html(data['price']);
                }


            }
        });
    }


    function addProductFavs(product_id) {
        $.ajax({
            url: '../product_add_fav',
            type: "GET",
            beforeSend: function() { $('#wait').show(); },
            complete: function() { $('#wait').hide(); },
            data: "product_id=" + product_id,
            success: function(data) {
                swal("Product Added!", 'Product successfully added in your Wishlist.', "success");

                setTimeout(reloadPage, 1000);

            }
        });

    }

    function removeProductFavs(product_id) {
        $.ajax({
            url: '../product_remove_fav',
            type: "GET",
            beforeSend: function() { $('#wait').show(); },
            complete: function() { $('#wait').hide(); },
            data: "product_id=" + product_id,
            success: function(data) {
                swal("Product Remove!", 'Product successfully removed in your Wishlist.', "success");
                setTimeout(reloadPage, 1000);
            }
        });

    }

     function currency(sel) {
        var currency= sel.value;
        $.ajax({
            url: '../changeCurrency/'+ currency,
            type: "GET",
            beforeSend: function() {$('#wait').show();},
            complete: function() {$('#wait').hide();},
            success: function(data) {
               location.reload();
            }
        });
    }

</script>

<!--Head Section-->
<!-- Banner Code Start -->

@include('front.layout.banner')

<!-- Banner Code End -->

<!--======================   breadcumbs =======================-->
<div class="container-fluid  mb-0 mb-sm-5 " style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
    <div class="row px-5 maxWidhtContainer">
        <div class=" col-12 align-self-center col-sm-12 col-md-12  h6 m-0  text-white"><nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item text-white">{{$title}}</li>


                </ol>
            </nav></div>
        <div class="maxWidhtContainer  h6 m-0 breadThum align-self-center   col-12 d-flex  col-sm-7 col-md-6 ">
            

        </div>
    </div>


</div>




<!-- ========================   contnet v==================-->


<div class="container-fluid px-0 px-sm-5 ">
    <div class="row pb-5 maxWidhtContainer">
        @foreach($products_show as $product)
        <div class="col-12 p-0 col-xs-12 d-block flex-wrap col-sm-12 col-md-5 border">
            <div class=" text-center"><a href="{{URL::asset('public/upload/product/'.$product->product_header_image)}}" class="MagicZoom" id="plant"><img src="{{URL::asset('public/upload/product/'.$product->product_header_image)}}"></a></div>
            <?php
                                    
	                $imgs = json_decode($product->product_images);
	                ?>
            <div class="d-flex  m-4">
                @foreach($imgs as $val)
                <div class="col"><a data-zoom-id="plant" href="{{URL::asset('public/upload/product/'.$val)}}" data-image="{{URL::asset('public/upload/product/'.$val)}}"><img src="{{URL::asset('public/upload/product/'.$val)}}" class="magicImg" width="100%" style="max-width:100px;"></a></div>
                @endforeach
            </div>
        </div>
        @endforeach

        @foreach($products_show as $products)
        <?php $proQty = $products->product_quantity;?>
        <div class="col-md-7  py-2">
            <div class="col-12 h4 m-0">
                {{$products->product_title}}
            </div>
            <div class="row px-3">


                <?php $sr = 0; $ir = 0;?>
                @foreach($review_list as $data)
                <?php
                               
                                $str = $data->star;
                                $strs = substr($str,5,6);
                                $sr = $sr + $strs;
                                 $ir=$ir+1;
                            ?>
                @endforeach
                <?php
                           if($sr>0)
                           {
                               $strss = ($sr/$ir);
                            }
                            else{
                                $strss=0;
                            }
                            ?>



                @if(isset(Auth::user()->id))
                <div class="col-12 col-sm-5 py-3 d-flex">
                    <div>
                        <a data-toggle="modal" data-target="#myRating" class="font-weight-bold text-danger wrre">Write a review</a>
                    </div>
                </div>
                @else
                <div class="col-12 col-sm-5 py-3 d-flex">
                    <div>
                        <a data-toggle="modal" data-target="#logSign" class="font-weight-bold text-danger wrre">Write a review</a>
                    </div>
                </div>
                @endif

                <div class="col-12  text-sm-right align-self-center justify-content-sm-end  col-sm-7  d-flex py-2">
                   
                    <div class=""> <a href="{{url('reviewDetail',$products->product_id)}}" class="text-dark"><b>Review(<span>{{$reviewCount}}</span>)</b></a></div>
                        <div class="d-flex align-self-center"> <a href="{{url('reviewDetail',$products->product_id)}}">
                            @for($i=1;$i<=$strss;$i++) <i class="fa fa-star text-warning"></i>
                                @endfor
                            </a>
                        </div>
                    
                </div>




                <div class="col-12 text-justify">
                    <?php echo $products->product_description; ?>
                </div>
             </div>
             
             <div class="row mx-3 py-2">
                 
                
                     @foreach($att_val as $data)
                        @if($data->attribute_name != "Self")
                        <div class="flex-m flex-w p-b-10 py-2  col-sm-4 pr-sm-0 col-md-4 col-lg-4 col-12 ">
    
                            <div class="rs2-select2 rs3-select2 bo4 of-hidden  d-flex flex-w">
                                <select class="form-control border-danger" id="{{$data->attribute_name}}" onchange="changeFilter()">
                                    <option value="0">Choose {{$data->attribute_name}}</option>
                                    <?php
                                $datas = json_decode($data->attribute_value);
                                ?>
                                    @foreach($datas as $dt)
                                    <option value="{{$data->attribute_name}}:{{$dt}}">{{$dt}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @else
                        <input type="hidden" id="Self" value="Self:Self">
                        @endif
    
                        @endforeach
                 
                 </div>
             
                <div class="row mx-3 py-2">
                        <div class="col-12 col-sm-6 py-2  priceCart2">
                            
                            <div class="text-danger h6 py-2 m-0" id="stock_avl">Quantity : 1 </div>
                            
                            
                            <!--<div class="flex-w bo5 of-hidden">
                                <button class="btn-num-product-down" style="background-color:black;" onclick="changeQuantity('min')">
                                    <i class="fs-12 fa fa-minus text-white" aria-hidden="true"></i>
                                </button>
        
                                <input class=" w-25 text-center num-product" type="number" name="qty" value="1" id="qty" onkeyup="changeQuantity('equal')">
        
                                <button class="btn-num-product-up " style="background-color:black;" onclick="changeQuantity('max')">
                                    <i class="fs-12 fa fa-plus text-white" aria-hidden="true"></i>
                                </button>
                            </div>-->
                           
                        </div>
                       <!-- <div class="col-12 pt-2 col-sm-4 text-md-center text-sm-left  ">

                          
                                
                                
                             @if(isset(Auth::user()->id))
                            @if(in_array($products->product_id, $mywishList))
                            
                            <h6 style="color:black; font-weight: bold;" onclick="removeProductFavs({{$products->product_id}})">
                                <i class="fa fa-heart pr-2 font-weight-bold"></i> Add To Wishlist</h6>
                            
                           
                            @else
                            
                            <h6 style="color:black; font-weight: bold;" onclick="addTowishlistloginJs({{$products->product_id}})">
                                <i class="fa fa-heart-o pr-2 font-weight-bold"></i> Add To Wishlist</h6>
                            
                            
                    
                            @endif
                            @else  
                            
                             
                            <h6 style="color:black; font-weight: bold;" data-toggle="modal" data-target="#logSign">
                                <i class="fa fa-heart-o pr-2 font-weight-bold"></i> Add To Wishlist</h6>
                             
                            
                              @endif
                                
                                
                                
                        </div>-->

                        <div class="col-12 col-sm-6  text-md-right text-sm-left justify-content-end ">
                            <?php $proQty = $products->product_quantity; ?>
                            <h6 style="color:black; font-weight: bold;" class="pt-2">
                                <span id="stockShow">
                                @if($proQty>0)
                                    IN STOCK
                                    @else
                                    OUT OF STOCK
                                    @endif
                            </span></h6>
                            
                        </div>
                        <!--<div class="col-sm-12">
                             <div class="text-danger h6 py-2 m-0" id="stock_avl">Availability : {{$proQty}}</div>
                            
                        </div>-->

                    </div>

            <div class="row mx-3 my-2 py-2 border border-left-0 border-right-0">
                    <?php 
                        $currencySession = Session::get('currency');
                        
                         if($currencySession == "rupee_price")
                         {
                             $iicon = "fa fa-inr";
                             $proPrice = $products->rupee_price;
                             $netAmount = $products->rupee_net_amount;
                             $finalAmount = round($products->rupee_net_with_gst);
                             
                         }
                         elseif($currencySession == "dollar_price")
                         {
                             $iicon = "fa fa-usd";
                             $proPrice = $products->dollar_price;
                             $netAmount = $products->doller_net_amount;
                             $finalAmount = round($products->dollar_net_with_gst);
                         } 
                         elseif($currencySession == "euro_price")
                         {
                             $iicon = "fa fa-eur";
                             $proPrice = $products->euro_price;
                             $netAmount = $products->euro_net_amount;
                             $finalAmount = round($products->euro_net_with_gst);
                         }
                         else
                         {
                            $iicon = "fa fa-usd";
                            $proPrice = $products->dollar_price;
                             $netAmount = $products->doller_net_amount;
                             $finalAmount = round($products->dollar_net_with_gst);
                         }
                         $proDiscount = $products->product_discount;
                         $maxAmount = round(($proPrice * ($products->product_gst / 100)) + $proPrice);
                        ?>
                        @foreach($flashSalesData as $flashSalesDatasss)
                                <?php 
                            $currencySession = Session::get('currency');
                            
                             if($currencySession == "rupee_price")
                             {
                                 $iicon = "fa fa-inr";
                                 $proPrice = $flashSalesDatasss->rupee_price;
                             }
                             elseif($currencySession == "dollar_price")
                             {
                                 $iicon = "fa fa-usd";
                                 $proPrice = $flashSalesDatasss->dollar_price;
                             } 
                             elseif($currencySession == "euro_price")
                             {
                                 $iicon = "fa fa-eur";
                                 $proPrice = $flashSalesDatasss->euro_price;
                             }
                             else
                             {
                                $iicon = "fa fa-inr";
                                 $proPrice = $flashSalesDatasss->rupee_price;
                             }
                            ?>
                            @endforeach
                        <div class="col-12 col-sm-5 align-self-center">
                            @if($proPrice > 0)
                            <h6>
                                <span style="text-decoration: line-through;" class="text-secondary">
                                    <i class="{{$iicon}}"></i>
                                    {{$proPrice}}/-
                                </span>
                            </h6>
                            <h2 class=" text-danger">
                                <i class="{{$iicon}}"></i>
        
                                <span id="price">{{$amt}}</span>/-
                            </h2>
                            @else
                            <h2 class="text-danger">
                                <i class="{{$iicon}}"></i>

                                <span id="price">{{$amt}}</span>/-
                            </h2>
                            @endif
                        </div>

                        <div class="col-12 col-sm-7 returnPloicy">
                            <ul class="list-unstyled">
                                <li class="py-1">
                                    <i class="fa fa-refresh pr-2"></i>7 day <a href="{{url('cms','return-policy')}}" alt="Go to return policy" target="_blank">return policy</a>
                                </li>
                                <li>
                                    <i class="fa fa-truck pr-2 py-1"></i>dispatch in 2-3 working days
                                </li>
                            </ul>
                        </div>

                 </div>
                 <div class="row mx-3">
                    <!--@if(isset(Auth::user()->id))
                    @if($proQty>0)
                    <div class="my-3 col-12 text-sm-center col-sm-6 col-md-6 col-lg-6 m-auto" id="cart_button">
                        <span class=" d-flex justify-content-between  align-self-end" style="font-size:24px !important;" onclick="addTocartloginJs()">
                            <span class='btn  m-0 px-5 py-2 prdBtn'  style="font-size:17px !important;">ADD TO CART</span>

                        </span>
                    </div>
                    @else
                    <div class="my-3 col-12 text-sm-center  col-sm-6 col-md-6 col-lg-6 m-auto" id="cart_button">
                        <span class="d-flex justify-content-between align-self-end" style="font-size:24px !important;" onclick="showOutStock()">
                            <span class='btn  m-0 px-5 py-2 prdBtn' onclick="showOutStock()" style="font-size:17px !important;">ADD TO CART</span>

                        </span>
                    </div>
                    @endif
                    @else
                    @if($proQty>0)
                    <div class="my-3 col-12text-sm-center   col-sm-6 col-md-6 col-lg-6 m-auto" id="cart_button">
                        <span class="d-flex justify-content-between align-self-end" style="font-size:24px !important;" onclick="addTocartloginJs()">
                            <span class='btn  m-0 px-5 py-2 prdBtn' onclick="addTocartJs()"  style="font-size:17px !important;">ADD TO CART</span>

                        </span>
                    </div>
                    @else
                    <div class="my-3 col-12 text-sm-center  col-sm-6 col-md-6 col-lg-6 m-auto" id="cart_button">
                        <span class="d-flex justify-content-between align-self-end" style="font-size:24px !important;" onclick="showOutStock()">
                            <span class='btn  m-0 px-5 py-2 prdBtn' onclick="showOutStock()" >ADD TO CART</span>

                        </span>
                    </div>
                    @endif

                    @endif-->

                    @if(isset(Auth::user()->id))
                    @if($proQty>0)
                    <div class="my-3 col-12 text-sm-center m-sm-t-2 col-sm-6 col-md-6 col-lg-6 m-auto" id="button_buy">
                        <span class=" d-flex justify-content-end align-self-end" style="font-size:24px !important;" onclick="buyNowAction()" id="btn_buy_now">
                            <span class='btn m-0  py-2 prdBtn ' style="padding-left:59px; padding-right:59px; font-size:17px !important;">BUY NOW</span>
                        </span>
                    </div>
                    @else
                    <div class="my-3 col-12 text-sm-center m-sm-t-2 col-sm-6 col-sm-6 col-md-6  col-lg-6 m-auto" id="button_buy">
                        <span class=" d-flex justify-content-end align-self-end" style="font-size:24px !important;" onclick="showOutStock()" id="btn_buy_now">
                            <span class='btn m-0  py-2 prdBtn ' style="padding-left:59px; padding-right:59px; font-size:17px !important;">BUY NOW</span>
                        </span>
                    </div>
                    @endif
                    @else

                    <div class="my-3 col-12 text-sm-center m-sm-t-2 col-sm-6 col-sm-6 col-md-6 col-lg-6 m-auto" data-toggle="modal" data-target="#logSign">
                        <span class=" d-flex justify-content-end align-self-end" style="font-size:24px !important;">
                            <span class='btn  m-0  py-2 prdBtn ' style="padding-left:59px; padding-right:59px; font-size:17px !important;">BUY NOW</span>
                        </span>
                    </div>

                    @endif
                 </div>
                 
                <div class="row mx-3 px-2 my-3">
                    <div class="col-12 col-sm-12 p-0 ">
                        <div class="input-group ">
                                    <input type="text" class="col-12 form-control rounded-0" placeholder="Enter 6 digit PIN code" id="pincode" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" maxlength="6" min="000000" max="999999">
                                    <div class="col-12 input-group-append btn p-0 border-0" onclick="check_pincodeStatus()">
                                        <span class="input-group-text bg-secondary text-white rounded-0" onclick="check_pincodeStatus()">Check Estimated Delivery</span>
                                    </div>
                                </div>
                        </div>
                    </div>
                   <div class="row mx-3 px-2 my-3">
                    <div class="col-12 col-sm-12 p-0 ">
                        Is your Item not available? <a href="#" class="text-danger" data-toggle="modal" data-target="#getNotify">Get notified.</a>
                        </div>
                        </div>
                


        </div>
        @endforeach

    </div>

    <div class="row pb-5 maxWidhtContainer">
        <div class="col-12 reviewContent">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#profile" role="tab" data-toggle="tab">Product Specification</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#buzz" role="tab" data-toggle="tab">Additional Infromation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#references" role="tab" data-toggle="tab">Review</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content p-3 text-justify">

                @foreach($prductss_datas as $data)
                <div role="tabpanel" class="tab-pane fade in active show text-justify" id="profile">
                    <?php echo $data->product_specification; ?>
                </div>
                <div role="tabpanel" class="tab-pane fade text-justify" id="buzz">
                    <?php echo $data->product_addition_information; ?>
                </div>
                @endforeach

                <div role="tabpanel" class="tab-pane fade text-justify" id="references">

                    @if(!$review_list->isEmpty())
                    @foreach($review_list as $data)

                    <?php
                                $str = $data->star;
                                $strs = substr($str, 5, 6);
                            ?>
                    <div>
                        <h6 class="text-center">{{$data->name}}</h6>
                    </div>
                    <div class="text-center m-0 p-0">

                        @for($i=1; $i<=$strs; $i++) <i class="fa fa-star text-warning"></i>
                            @endfor
                    </div>
                    <div class="text-secondary text-center">
                        <?php echo $data->review; ?>
                    </div>
                    @endforeach
                    <div class="text-secondary text-center">
                        <a href="{{url('reviewDetail',$data->product_id)}}" class="btn"><b>See More</b> </a>
                    </div>
                    <hr>

                    <hr>
                    @else
                    <div class="text-left">Currently no reviews available for this product</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>




<!--=======================    related product ===================-->
<div class="container">
    <div class="row maxWidhtContainer">
        <div class="col-12 pb-5  text-center ">
            <div class="h5 m-0 font-weight-bold headStyle">Related Products</div>
        </div>
    </div>
</div>





<div class="container-fluid pb-5 px-5">
    <div class="row maxWidhtContainer">
        <div class="col-12 px-0">
            <div class="owl-carousel owl-theme owl-custom-arrow" id="owl-team">

                @foreach($rel_product as $relproduct)
                <div class="item px-2">
                    <a href="{{url('product', $relproduct->slug)}}">
                        <div class="card " style="border-radius: 15px; overflow: hidden;">
                                <div class="card-body p-0 position-relative">
                                <img src="{{URL::asset('public/upload/product/'.$relproduct->product_header_image)}}" width="100%">


                            </div>
                            <a href="{{url('product', $relproduct->slug)}}">
                                <div class="card-footer text-white" style="background-color:black;">

                                    <?php 
                                        $currencySession = Session::get('currency');
                                        
                                         if($currencySession == "rupee_price")
                                         {
                                             $iicon = "fa fa-inr";
                                             $proPrice = $relproduct->rupee_price;
                                             $netAmount = $relproduct->rupee_net_amount;
                                             $finalAmount = round($relproduct->rupee_net_with_gst);
                                             
                                         }
                                         elseif($currencySession == "dollar_price")
                                         {
                                             $iicon = "fa fa-usd";
                                             $proPrice = $relproduct->dollar_price;
                                             $netAmount = $relproduct->dollar_net_amount;
                                             $finalAmount = round($relproduct->dollar_net_with_gst);
                                         } 
                                         elseif($currencySession == "euro_price")
                                         {
                                             $iicon = "fa fa-eur";
                                             $proPrice = $relproduct->euro_price;
                                             $netAmount = $relproduct->euro_net_amount;
                                             $finalAmount = round($relproduct->euro_net_with_gst);
                                         }
                                         else
                                         {
                                            $iicon = "fa fa-usd";
                                            $proPrice = $relproduct->dollar_price;
                                             $netAmount = $relproduct->dollar_net_amount;
                                             $finalAmount = round($relproduct->dollar_net_with_gst);
                                         }
                                        $proDiscount = $relproduct->product_discount;
                                        $maxAmount = round(($proPrice * ($relproduct->product_gst / 100)) + $proPrice);
                                        ?>
                                    
                                        <div class="" style="bottom:10%;">
                                            @if($proDiscount > 0)
                                            <span style="font-size: 14px!important;"><i class="{{$iicon}} text-white pr-2"></i><span>{{$finalAmount}}</span>/-</span>

                                            <span class="text-secondary py-2 " style="text-decoration: line-through;"><i class="{{$iicon}}  pr-2"></i><span>{{$maxAmount}}</span>/-</span>
                                            @else
                                            <span style="font-size: 14px!important;" class=""><i class="{{$iicon}} text-white pr-2"></i><span>{{$finalAmount}}</span>/-</span>
                                            @endif
                                        </div>


                                        <div class="font-weight-normal m-0 text-white" style="font-size:14px">
                                            <?php
                                            $des = $data->product_title;
                                            echo $description = Str::words($des, '2');
                                        ?>
                                        </div>


                                   


                                </div>
                            </a>

                        </div>
                    </a>
                    <input type="hidden" name="product_id" id="product_id" value="{{$products->product_id}}">
                    <input type="hidden" name="product_id" id="vendor_id" value="{{$products->vendor_id}}">
                    <input type="hidden" name="vendor" id="vendor" value="{{$vendor}}">
                    <input type="hidden" id="ip_address" value="{{$ip_address}}" disabled>
                </div>
                @endforeach




            </div>

        </div>

    </div>

</div>



<!--///////////////  model review rating  / ////////////-->

<div class="modal fade review_write_popup " id="myRating" role="dialog">
    <div class="modal-dialog rounded-0">

        <!-- Modal  content-->
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background-color:black; color:white;">
                <h6 class="modal-title m-0 align-self-center">Review & Rating</h6>
                <button type="button" class="close text-white" data-dismiss="modal"><span class="h2 m-0">&times;</span></button>

            </div>
            <div class="modal-body " style="background-color:gray;">
                <div class="stars">
                    <form action="{{url('review_saveds')}}" method="post">

                        @csrf

                        @foreach($prductss_datas as $data)
                        <input type="hidden" name="product_id" value="{{$data->product_id}}">
                        @endforeach
                        <div class="form-group str_po">
                            <label class="h6 m-0 text-white">Rating:</label>

                            <input class="star star-5" id="star-5" value="star-5" type="radio" name="star" />
                            <label class="star star-5" for="star-5"></label>
                            <input class="star star-4" id="star-4" value="star-4" type="radio" name="star" />
                            <label class="star star-4" for="star-4"></label>
                            <input class="star star-3" id="star-3" value="star-3" type="radio" name="star" />
                            <label class="star star-3" for="star-3"></label>
                            <input class="star star-2" id="star-2" value="star-2" type="radio" name="star" />
                            <label class="star star-2" for="star-2"></label>
                            <input class="star star-1" id="star-1" value="star-1" type="radio" name="star" />
                            <label class="star star-1" for="star-1"></label>
                        </div>

                        <div class="form-group rvw">
                            <label for="comment" class="h6 text-white">Review:</label>
                            <textarea class="form-control h6 rounded-0" rows="5" id="comment" style="resize:none;" name="review" required></textarea>
                        </div>
                        <button type="submit" class="cta border-0 ">
                            <span class="text-white">Submit</span>
                            <svg width="13px" height="10px" style="stroke:white!important;" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>

<!--///////////////  model Notification  / ////////////-->

<div class="modal fade review_write_popup " id="getNotify" role="dialog">
    <div class="modal-dialog rounded-0">

        <!-- Modal  content-->
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0" style="background-color:black; color:white;">
                <h6 class="modal-title m-0 align-self-center">Get notified Message</h6>
                <button type="button" class="close text-white" data-dismiss="modal"><span class="h2 m-0">&times;</span></button>

            </div>
            <div class="modal-body " style="background-color:gray;">
                <div class="stars">
                    <form action="{{url('getNotificationSave')}}" method="post">

                        @csrf

                        @foreach($prductss_datas as $data)
                        <input type="hidden" name="product_id" value="{{$data->product_id}}">
                        @endforeach
                        <div class="form-group">
                            <label class="h6 m-0 text-white">Your Email:</label>

                            <input  class="form-control mt-2 h6" type="email" name="user_email" required/>
                            
                        </div>

                        <div class="form-group">
                            <label for="comment" class="h6 text-white">Query</label>
                            <textarea class="form-control h6 rounded-0" rows="5" id="comment" style="resize:none;" name="message" required></textarea>
                        </div>
                        <button type="submit" class="cta border-0 ">
                            <span class="text-white">Send</span>
                            <svg width="13px" height="10px" style="stroke:white!important;" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>




</section>


@stop
