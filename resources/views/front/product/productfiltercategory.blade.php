@extends('front.layout.default_layout')
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script>
    $('document').ready(function() {
        $('input[type="checkbox"]').click(function() {
            if ($(this).prop("checked") == true) {
                var st = concat_string(this.value);

            } else if ($(this).prop("checked") == false) {
                var st = remove_string(this.value);

            }
        });

    });


    var val = "";

    function concat_string(str) {
        var kno = str;
        val = val.concat(kno);
        fliter_data();

    }



    function remove_string(str) {
        val = val.replace(str, "");
        fliter_data();
        if (val == "") {
            location.reload();
        }
    }

    function priceSystem() {
        fliter_data();
    }

    function proListSort(str) {
        fliter_data();
    }

    function product_add_favs(product_id) {
        $.ajax({
            url: 'product_add_fav',
            type: "GET",
            beforeSend: function() {
                $('#wait').show();
            },
            complete: function() {
                $('#wait').hide();
            },
            data: "product_id=" + product_id,
            success: function(data) {
                swal("Product Added!", 'Product successfully added in your Wishlist!', "success");
                setTimeout(reloadPage, 2000);
            }
        });

    }

    function product_remove_favs(product_id) {
        $.ajax({
            url: 'product_remove_fav',
            type: "GET",
            beforeSend: function() {
                $('#wait').show();
            },
            complete: function() {
                $('#wait').hide();
            },
            data: "product_id=" + product_id,
            success: function(data) {
                swal("Product Remove!", 'Product successfully removed in your Wishlist!', "success");
                setTimeout(reloadPage, 2000);
            }
        });

    }



    function reloadPage() {
        location.reload();
    }


    function fliter_data() {
        var amount = $('#amount').val();
        var categorys = new Array();
        var filter = new Array();
        var review = new Array();
        var sorting = $('#sorting').val();
        $("input:checkbox[name=cate]:checked").each(function() {
            categorys.push($(this).val());
        });

        $("input:checkbox[name=review]:checked").each(function() {
            review.push($(this).val());
        });
        @if(isset($att_values))
        @foreach($att_values as $key => $data)
        var m = $('#{{$key}}').val();
        if (m != "0") {
            filter.push(m);
        }
        @endforeach
        @endif
        $('#pag_link').html(" ");
        $.ajax({
            url: 'filter_product',
            type: "GET",
            beforeSend: function() {
                $('#wait').show();
            },
            complete: function() {
                $('#wait').hide();
            },
            data: "cate_list=" + categorys + "&filter=" + filter + "&amount=" + amount + "&review=" + review + "&sorting=" + sorting,
            dataType: "json",
            success: function(data) {
                if (data.data == "Yes") {
                    $('#proList').html(data.product_filter);
                    $('#pag_link').html(" ");
                } else {
                    $('#proList').html("<div class='mt-4 py-4'>No products were found matching your selection.</div>");
                }

            }
        });
    }



    function currency(sel) {
        var currency = sel.value;
        $.ajax({
            url: 'changeCurrency/' + currency,
            type: "GET",
            beforeSend: function() {
                $('#wait').show();
            },
            complete: function() {
                $('#wait').hide();
            },
            success: function(data) {
                location.reload();
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
        <div class=" col-12 align-self-center col-sm-5 col-md-6  h5 m-0  text-white">
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


<div class="container-fluid px-0 px-sm-5 ">
    <div class="row py-4 px-0 px-sm-5 maxWidhtContainer">
        <div class="col-12 text-right py-2 d-flex justify-content-end d-block d-lg-none ">
            <div class="filterBtn"> <i class="fa fa-filter" aria-hidden="true"></i><span>Filter</span></div>
        </div>
        <div class="col-lg-3 filterSm  border py-4">
            <div class="col-12 d-flex justify-content-end canFltr d-block d-lg-none font-weight-bold text-right">X</div>
            <div class="p-0 col-12 h5 m-0 proListSortName text-danger">
                CATEGORY
            </div>

            <ul class="accordion list-unstyled mt-3" id="accordionExample">
                <?php $i=0; ?>
                @foreach($categories as $category)
                @if($category->is_active == "ACTIVE")
                @if(count($category->childs))
                <?php $childs = $category->childs;?>
                <li>
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <input type="checkbox" id="{{$category->category_id}}" name="cate" value="{{$category->category_id}}">
                            <label for="{{$category->category_id}}">{{$category->title}}</label>
                        </div>
                        <div data-toggle="collapse" data-target="#demo{{$i}}">
                            <i class="fa fa-caret-down fs-18 p-t-5" aria-hidden="true"></i>
                        </div>
                    </div>

                    <div id="demo{{$i}}" class="collapse pl-3" data-parent="#accordionExample">
                        @foreach($childs as $child)
                        @if($child->is_active == "ACTIVE")
                        <div class="form-group" style="margin-bottom: 0rem;">
                            <input type="checkbox" id="{{$child->category_id}}" name="cate" value="{{$child->category_id}}">
                            <label for="{{$child->category_id}}">{{$child->title}}</label>
                        </div>
                        @endif
                        @endforeach

                    </div>

                </li>
                @else
                <li class="p-t-4">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <input type="checkbox" id="{{$category->category_id}}" name="cate" value="{{$category->category_id}}">
                            <label for="{{$category->category_id}}">{{$category->title}}</label>
                        </div>
                    </div>
                </li>
                @endif
                @endif
                <?php $i++;?>
                @endforeach
            </ul>

            <div class="p-0 col-12 pt-4 m-0 h5 proListSortName text-danger">
                SHOP BY
            </div>
            
            <?php 
            $currencySession = Session::get('currency');

             if($currencySession == "rupee_price")
             {
                 $iicon = "fa fa-inr";
                 
             }
             elseif($currencySession == "dollar_price")
             {
                 $iicon = "fa fa-usd";
                
             } 
             elseif($currencySession == "euro_price")
             {
                 $iicon = "fa fa-eur";
                
             }
             else
             {
                $iicon = "fa fa-usd";
                
             }
        
        ?>
            <div class="col-12 py-3 h6 m-0">
                <u>Price range (<i class="{{$iicon}}" aria-hidden="true"></i>)</u>
            </div>

            <div class="col-12">
                <p>
                    <input type="text" class="bg-transparent" id="amount" readonly style="border:0; font-weight:bold;">
                </p>

                <div id="slider-range" onclick="priceSystem()"></div>
            </div>

            @if(isset($att_values))
            @foreach($att_values as $key => $data)
            @if($key != "Self")

            <div class="col-12 py-3 h6 m-0">
                {{$key}}
            </div>

            <div class="col-12">
                <select class="selection-2 form-control" name="{{$key}}" id="{{$key}}" onchange="fliter_data()">
                    @if($key == "color" || $key == "Color")
                    <option selected value="">Choose an {{$key}}</option>
                    @foreach($data as $dt)
                    <option value="{{$key}}:{{$dt}}" class="text-white" style="background-color:{{$dt}}">{{$dt}}</option>
                    @endforeach
                    @else
                    <option selected value="">Choose an {{$key}}</option>
                    @foreach($data as $dt)
                    <option value="{{$key}}:{{$dt}}">{{$dt}}</option>
                    @endforeach
                    @endif

                </select>
            </div>

            @endif
            @endforeach
            @endif



            <div class="col-12 py-3 h6 m-0">
                <u>Rating</u>
            </div>
            <div class="col-12">
                <div> <input type="checkbox" id="star_5" style="width:fit-content" value="star-5" name="review"><label for="star_5" class="px-4 check_box">
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                    </label>
                </div>
                <div>
                    <input type="checkbox" id="star_4" style="width:fit-content" value="star-4" name="review"><label for="star_4" class="px-4 check_box">
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>

                    </label>
                </div>
                <div>
                    <input type="checkbox" id="star_3" style="width:fit-content" value="star-3" name="review"><label class="px-4 check_box" for="star_3">
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>

                    </label>
                </div>
                <div> <input type="checkbox" id="star_2" style="width:fit-content" value="start-2" name="review"><label for="star_2" class="px-4 check_box">
                        <i class="fa fa-star text-warning"></i>
                        <i class="fa fa-star text-warning"></i>

                    </label>
                </div>
                <div> <input type="checkbox" id="star_1" style="width:fit-content" value="star-1" name="review"><label for="star_1" class="px-4 check_box">
                        <i class="fa fa-star text-warning"></i>

                    </label>
                </div>



            </div>


            <div class="p-0 col-12 pt-4 m-0 h5 proListSortName text-danger">
                MY WISH LIST
            </div>

            <div class="row">
                @if(isset(Auth::user()->id))
                @if(!$wishs_lists->isEmpty())
                @foreach($wishs_lists as $wish)
                <div class="col-12  py-4 col-sm-6 col-md-4 col-lg-12 d-flex">
                    <div class="col-4 p-0">
                        <img src="{{ z_media_url($wish->product_header_image, 'product') }}" alt="IMG" width="100%">
                    </div>

                    <div class="col-8 pr-0">
                        <a href="#" class="text-dark" style="font-size:14px;">
                            {{$wish->product_title}}
                        </a>
                        @if(isset($netAmount[$wish->product_id]))
                        <div class="d-flex py-2">
                            <i class="fa fa-inr pr-2 align-self-center"></i>{{$netAmount[$wish->product_id]}}
                        </div>
                        @endif

                        <span class=" d-flex justify-content-between align-self-end" style="font-size:12px!important;">
                            <span class='btn  m-0 px-1 py-0 prdBtn'><a href="{{url('addToCartSaveData', $wish->wishlist_id)}}" class="text-dark">Move to Cart</a></span>
                            <span class='btn m-0 px-1 py-0 prdBtn'><a href="{{url('wishDelete', $wish->wishlist_id)}}" onClick="return confirm('Are you sure?');" class="text-dark">Remove</a></span>
                        </span>
                    </div>
                </div>
                @endforeach
                @else
                No Product is Wishlist.
                @endif
                @else
                <div class="px-4 col-12 pt-3 m-0">Please <a href="#" data-target="#logSign" data-toggle="modal">Login</a> for your Wishlist.</div>
                @endif

            </div>


            <div class="col-12 d-flex py-4 justify-content-center d-block d-lg-none">


                <button type="submit" class="mx-3 cta border-0 subFltr">
                    <span style="z-index: 9;">Submit</span>
                    <svg width="13px" height="10px" viewBox="0 0 13 10">
                        <path d="M1,5 L11,5"></path>
                        <polyline points="8 1 12 5 8 9"></polyline>
                    </svg>
                </button>


                <button type="button" class="cta border-0 canFltr mx-3">
                    <span style="z-index: 9;">Cancel</span>
                    <svg width="13px" height="10px" viewBox="0 0 13 10">
                        <path d="M1,5 L11,5"></path>
                        <polyline points="8 1 12 5 8 9"></polyline>
                    </svg>
                </button>

            </div>


        </div>

        <div class="col-md-12 col-lg-9 border py-4">
            <div class="row justify-content-between px-3">

                <div class="d-flex">
                    <div class="h6 m-0 pr-3 align-self-center text-danger">Sort By</div>
                    <select name="sorting" id="sorting" onchange="fliter_data()" class="py-1 px-1" >
                        <option value="">Default Sorting</option>
                        <option value="popularity">Popularity</option>
                        <option value="lowtohigh">Price: low to high</option>
                        <option value="hightolow">Price: high to low</option>
                    </select>
                </div>
              <!--  <div class="" id="pag_link">Item {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} </div>-->
            </div>
            <div id="proList">
                <div class="row">
                    @foreach($products as $data)

                    <?php
                  $discount = $data->net_amount;
                    ?>
                    <div class="col-sm-3 col-md-3 col-6 my-4">
                        <a href="{{url('product', $data->slug)}}">
                            <div class="card " style="border-radius: 15px; overflow: hidden;">
                                <div class="card-body p-0 position-relative">
                                    <img src="{{ z_media_url($data->product_header_image, 'product') }}" width="100%">

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

                                <a href="{{url('product', $data->slug)}}">
                                    <div class="card-footer text-white" style="background-color:black;">
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
            </div>
            <div class="row my-4">
                <nav class="col-12" aria-label=" Page navigation example">
                    <ul class="pagination">
                        {{ $products->links() }}
                    </ul>
                </nav>
            </div>



        </div>







    </div>

</div>






</section>

@stop
