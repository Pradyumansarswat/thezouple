@extends('front.layout.default_layout')
@section('content')

<script>
    const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;

    let countDown = new Date('{{$count_down}}').getTime(),
        x = setInterval(function() {

            let now = new Date().getTime(),
                distance = countDown - now;

            document.getElementById('days').innerText = Math.floor(distance / (day)),
                document.getElementById('hours').innerText = Math.floor((distance % (day)) / (hour)),
                document.getElementById('minutes').innerText = Math.floor((distance % (hour)) / (minute)),
                document.getElementById('seconds').innerText = Math.floor((distance % (minute)) / second);

            //do something later when date is reached
            //if (distance < 0) {
            //  clearInterval(x);
            //  'IT'S MY BIRTHDAY!;
            //}

        }, second)


    $('.btn-num-product-down').on('click', function(e) {
        e.preventDefault();
        var numProduct = Number($(this).next().val());
        if (numProduct > 1) $(this).next().val(numProduct - 1);
    });

    $('.btn-num-product-up').on('click', function(e) {
        e.preventDefault();
        var numProduct = Number($(this).prev().val());
        $(this).prev().val(numProduct + 1);
    });

</script>
<script>
    /* videos = document.querySelectorAll("video");
for (var i = 0, l = videos.length; i < l; i++) {
    var video = videos[i];
    var src = video.src || (function () {
        var sources = video.querySelectorAll("source");
        for (var j = 0, sl = sources.length; j < sl; j++) {
            var source = sources[j];
            var type = source.type;
            var isMp4 = type.indexOf("mp4") != -1;
            if (isMp4) return source.src;
        }
        return null;
    })();
    if (src) {
        var isYoutube = src && src.match(/(?:youtu|youtube)(?:\.com|\.be)\/([\w\W]+)/i);
        if (isYoutube) {
            var id = isYoutube[1].match(/watch\?v=|[\w\W]+/gi);
            id = (id.length > 1) ? id.splice(1) : id;
            id = id.toString();
            var mp4url = "http://www.youtubeinmp4.com/redirect.php?video=";
            video.src = mp4url + id;
        }
    }
}*/

</script>


<!--======================   slider wrapper ======================-->

<div class="container-fluid sliderWrapper">
    <div class="row">
        <div class="col-12 p-0 slidehover text-center m-auto">
            @foreach($main_video as $main)
            @if($main->video_id == 1)

            <video autoplay preload="metadata" muted loop id="myVideo" src="{{URL::asset('public/upload/video/'.$main->video)}}">
                <source type="video/mp4" width="100%">

            </video>
            @endif
            @endforeach
            <!-- <iframe class="embed-player slide-media" width="980" height="520" src="https://www.youtube.com/embed/fVo9mjOPmvk?controls=0&fs=0&rel=0&showinfo=0&loop=1&start=1&autoplay=1" frameborder="0" allowfullscreen style="overflow:hidden;height:100%;width:100%" height="100%" width="100%" ></iframe>-->
            @if(!$slider_data->isEmpty())
            <div class="slideHoverContent  text-center m-auto  maxWidhtContainer">
                <div class="owl-carousel  owl-theme owl-custom-arrow align-self-center" id="owl-attractions">
                    @foreach($slider_data as $slider)
                    <div class="item pr-2 pl-1 ">
                        <div class="h-100">
                            <div class="h1 m-0  text-center ">
                                {{$slider->name}}
                            </div>
                            <div class="h4 m-0 text-center">
                                <?php echo $slider->heading;?>
                            </div>
                            <div class="viewBtn2">
                                <a href="{{$slider->slug}}">View More</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>

</div>

<section class="bg-white">

    <!--====================   category =============-->

    <div class="container">
        <div class="row maxWidhtContainer">
            <div class="col-12 text-center headpadding">
                <div class="h6 m-0 font-weight-bold headStyle">Category</div>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row maxWidhtContainer">
            <div class="col-12 pl-0">
                <div class="owl-carousel owl-theme owl-custom-arrow" id="owl-tour-offers">
                    @foreach($cate_data as $data)
                    <div class="item px-2">
                        <a href="{{url('category',$data->slug)}}" class="text-dark">
                            <div class="cards cardCat">
                                <img src="{{URL::asset('public/upload/category/'.$data->image)}}" class="img-responsive" alt="Cards Image" width="100%">
                                <div class="text-white px-2 cartCatTitle">
                                    <?php
                                $des = $data->title;
                                echo $description = Str::words($des, '4');
                            ?>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <!--=====================   end category ================-->

    <!--=====================   about us =================-->
    <div class="container-fluid">
        <div class="row  maxWidhtContainer">
            <div class="col-12   text-center headpadding">
                <div class="h5 m-0 font-weight-bold headStyle">Zouple Shirts</div>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        @foreach($customer_data as $data)
        <div class="row pb-5 maxWidhtContainer ">
            <div class="col-sm-5">
                <img src="{{URL::asset('public/upload/customershirt/'.$data->image)}}" width="100%">
            </div>
            <div class="col-sm-7 font-weight-normal text-justify m-sm-0 m-xs-3 " style="font-size=" 14px !important>
                <h6 style="font-size:18px !important" class="mt-sm-0 mt-3">{{$data->heading}}</h6>
                <hr>
                <div style="font-size:14px !important">
                    <?php echo $data->description;?>
                </div>


                <div class="col-12 text-center mt-3">
                    <a href="{{url('designShirt')}}">
                        <button type="submit" class="cta border-0">
                            <span>Customize Shirt</span>
                            <svg width="13px" height="10px" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
                        </button>
                    </a>

                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!--========================  end of about us ==============-->

    <!--====================  new arivals ====================-->



    <div class="container-fluid">
        <div class="row position-relative maxWidhtContainer">
            <div class="col-12 pb-3 pb-sm-5  text-center ">
                <div class="m-0 font-weight-bold headStyle">Best Selling On Amazon</div>
            </div>

            <div class="col-12 btnDefault pb-sm-0 pb-4">
                <a href="{{url('newArrivals')}}">
                    <button type="submit" class="cta border-0">
                        <span>View More</span>
                        <svg width="13px" height="10px" viewBox="0 0 13 10">
                            <path d="M1,5 L11,5"></path>
                            <polyline points="8 1 12 5 8 9"></polyline>
                        </svg>
                    </button>
                </a>
            </div>

        </div>
    </div>




    <div class="container-fluid pb-5">
        <div class="row maxWidhtContainer">
            <div class="col-12 px-0">
                <div class="owl-carousel owl-theme owl-custom-arrow" id="owl-team">

                    @foreach($new_arrivals as $arrivals)
                    <div class="item px-3">
                        <a href="{{url('product', $arrivals->slug)}}">
                            <div class="card " style="border-radius: 15px; overflow: hidden;">
                                <div class="card-body p-0 position-relative">
                                    <img src="{{URL::asset('public/upload/product/'.$arrivals->product_header_image)}}" width="100%">
                                </div>
                                <?php 
                            $currencySession = Session::get('currency');
                            
                             if($currencySession == "rupee_price")
                             {
                                 $iicon = "fa fa-inr";
                                 $proPrice = $arrivals->rupee_price;
                                 $netAmount = $arrivals->rupee_net_amount;
                                 $finalAmount = round($arrivals->rupee_net_with_gst);
                                 
                             }
                             elseif($currencySession == "dollar_price")
                             {
                                 $iicon = "fa fa-usd";
                                 $proPrice = $arrivals->dollar_price;
                                 $netAmount = $arrivals->dollar_net_with_gst;
                                 $finalAmount = round($arrivals->dollar_net_with_gst);
                             } 
                             elseif($currencySession == "euro_price")
                             {
                                 $iicon = "fa fa-eur";
                                 $proPrice = $arrivals->euro_price;
                                 $netAmount = $arrivals->euro_net_amount;
                                 $finalAmount = round($arrivals->euro_net_with_gst);
                             }
                             else
                             {
                                $iicon = "fa fa-inr";
                                $proPrice = $arrivals->rupee_price;
                                 $netAmount = $arrivals->rupee_net_with_gst;
                                 $finalAmount = round($arrivals->rupee_net_with_gst);
                             }
                            
                            $proDiscount = $arrivals->product_discount;
                            
                            $maxAmount = round(($proPrice * ($arrivals->product_gst / 100)) + $proPrice);
                            ?>
                                <a href="{{url('product', $arrivals->slug)}}">
                                    <div class="px-2 py-2 pt-0 text-white" style="background-color:black;">
                                        <div class="" style="bottom:10%;">
                                            @if($proDiscount > 0 )
                                            <span style="font-size: 14px!important;"><i class="{{$iicon}} text-white pr-1"></i><span>{{$finalAmount}}</span>/-</span>

                                            <span class="text-secondary ml-1 ml-sm-3" style="text-decoration: line-through; font-size: 14px!important;"><i class="{{$iicon}}  pr-1"></i><span>{{$maxAmount}}</span>/-</span>
                                            @else
                                            <div style="font-size: 14px!important;" class=""><i class="{{$iicon}} text-white pr-1"></i><span>{{$finalAmount}}</span>/-</div>
                                            @endif


                                        </div>


                                        <div class="font-weight-normal m-0 text-white p-0 proTitleText" style="font-size:14px">
                                            <?php
                                    $des = $arrivals->product_title;
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

        </div>

    </div>




    <!--=====================   end new arivals ================-->

    <!--====================   video blog ====================-->
@php
    $subVideo = null;
    foreach($main_video as $sv) {
        if($sv->video_id == 2) { $subVideo = $sv; break; }
    }
@endphp
@if($subVideo && !empty($subVideo->video))
<div class="container-fluid pb-5">
    <div class="row">
        <div class="col-12 p-0 videoBlog position-relative">
            <video autoplay muted loop id="myVideo2">
                <source src="{{URL::asset('public/upload/video/'.$subVideo->video)}}" type="video/mp4" width="100%" height="400px">
            </video>
            <div class="videoHover d-flex maxWidhtContainer">
                <div class="align-self-center text-white">
                    <div class="h3 m-0 text-center">{{$subVideo->title}}</div>
                    <div class="h5 m-0 text-center"><?php echo $subVideo->description; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
    <!--============================ end video blog ==============-->


    <!--====================  feature / Why Choose Zouple ====================-->

<div class="container-fluid py-5" id="why-choose-zouple">
    <style>
    #why-choose-zouple {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%);
        position: relative;
        overflow: hidden;
    }
    #why-choose-zouple::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: radial-gradient(ellipse at top, rgba(198,168,103,0.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .reviews-heading {
        font-size: 2.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #C6A867, #f0d080, #C6A867);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: 2px;
        margin-bottom: 6px;
    }
    .reviews-sub {
        color: rgba(255,255,255,0.5);
        font-size: 1rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 40px;
    }
    .review-slide-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(198,168,103,0.2);
        border-radius: 20px;
        padding: 36px 30px 28px;
        margin: 10px 15px;
        position: relative;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    .review-slide-card:hover {
        border-color: rgba(198,168,103,0.5);
        background: rgba(255,255,255,0.07);
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(198,168,103,0.15);
    }
    .review-quote {
        font-size: 60px;
        line-height: 1;
        color: rgba(198,168,103,0.3);
        font-family: Georgia, serif;
        position: absolute;
        top: 12px;
        left: 22px;
    }
    .review-stars {
        color: #C6A867;
        font-size: 18px;
        letter-spacing: 2px;
        margin-bottom: 14px;
    }
    .review-text {
        color: rgba(255,255,255,0.82);
        font-size: 0.97rem;
        line-height: 1.75;
        margin-bottom: 22px;
        min-height: 80px;
    }
    .review-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(198,168,103,0.5);
        margin-right: 14px;
        background: rgba(198,168,103,0.2);
    }
    .review-avatar-placeholder {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, #C6A867, #8B6914);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        color: white;
        margin-right: 14px;
        flex-shrink: 0;
    }
    .review-name {
        font-weight: 700;
        color: #fff;
        font-size: 1rem;
    }
    .review-heading-tag {
        color: rgba(198,168,103,0.8);
        font-size: 0.82rem;
        letter-spacing: 0.5px;
    }
    .owl-dots .owl-dot span {
        background: rgba(198,168,103,0.3) !important;
    }
    .owl-dots .owl-dot.active span {
        background: #C6A867 !important;
    }
    </style>
    <div class="maxWidhtContainer">
        <div class="text-center mb-4">
            <div class="reviews-heading">Why Choose Zouple</div>
            <div class="reviews-sub">What our customers say</div>
        </div>
        @if(isset($testimonials) && !$testimonials->isEmpty())
        <div class="owl-carousel owl-theme" id="owl-reviews">
            @foreach($testimonials as $t)
            <div class="item">
                <div class="review-slide-card">
                    <div class="review-quote">&ldquo;</div>
                    <div class="review-stars">★★★★★</div>
                    <div class="review-text">{{ strip_tags($t->description) }}</div>
                    <div class="d-flex align-items-center">
                        @if($t->image)
                        <img src="{{URL::asset('public/upload/testimonial/'.$t->image)}}" class="review-avatar" alt="{{$t->name}}">
                        @else
                        <div class="review-avatar-placeholder">{{ strtoupper(substr($t->name, 0, 1)) }}</div>
                        @endif
                        <div>
                            <div class="review-name">{{ $t->name }}</div>
                            @if($t->heading)
                            <div class="review-heading-tag">{{ $t->heading }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <script>
        $(document).ready(function(){
            $('#owl-reviews').owlCarousel({
                loop: true,
                margin: 10,
                autoplay: true,
                autoplayTimeout: 4000,
                autoplayHoverPause: true,
                dots: true,
                nav: false,
                responsive: {
                    0: { items: 1 },
                    600: { items: 2 },
                    1000: { items: 3 }
                }
            });
        });
        </script>
        @else
        <div class="text-center" style="color:rgba(255,255,255,0.4);padding:40px 0;">No reviews yet. Be the first to share your experience!</div>
        @endif
    </div>
</div>

    <!--=====================   end features ================-->

    <!--======================   flash sale and banner =======-->

    <div class="container-fluid">
        <div class="row maxWidhtContainer">
            @if($is_flash == "ACTIVE")
            <div class="col-md-12 col-lg-6 h-100 pb-5 m-0">
                @foreach($view_flash_data as $view_flash)
                <div class="row shadow ">
                    <div class="col-12  col-sm-4 col-md-4 px-0">
                        <div class="box">
                            <div class="ribbon ribbon-top-left"><span>Flash Sale</span></div>
                            <img src="{{URL::asset('public/upload/product/'.$view_flash->product_header_image)}}" width="100%">
                        </div>
                    </div>

                    <div class="col-12 col-sm-8 col-md-8 p-sm-0">
                        <div class=" m-0 pt-2 pb-1 px-3" style="font-size: 18px!important;background-color:black; color:white; "><b>{{$view_flash->product_title}}</b> </div>

                        <div class="text-secondary px-3 py-1 text-justify" style="height:140px; overflow:hidden">
                            <?php

                         $des = $view_flash->product_description;

                          echo $description = Str::words($des, '36'); 

                         ?>
                        </div>

                        <div class="d-flex px-3 justify-content-between">
                            <div class="h6 d-flex py-1 align-self-center">
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
                            @if($proPrice > 0 )
                            <div>
                                    <i class='{{$iicon}} pr-2 pl-1'></i><span>{{$amt}}</span>/-
                            </div>
                            <span class="text-secondary ml-1 ml-sm-3 mt-1" style="text-decoration: line-through; font-size: 14px!important;"><i class="{{$iicon}}  pr-1"></i><span>{{$proPrice}}</span>/-</span>
                            @else
                            <div>
                                    <i class='{{$iicon}} pr-2 pl-1'></i><span>{{$amt}}</span>/-
                            </div>
                            @endif


                            </div>

                            <div class="d-flex align-self-center ">
                                <div class='btn h-100 align-self-center m-0 px-2 py-0 pb-1 prdBtn '><a href="{{url('flashproduct', $view_flash->slug)}}" class="text-dark">Buy now</a></div>

                                <!--  <div class="align-self-center px-2 btn"><a href="{{url('buyNowFlashProduct',$view_flash->product_id)}}" style="color:black!Important;"><div class='btn h-100 align-self-center m-0 px-2 py-0 pb-1 prdBtn ' ><a href="#" class="text-dark">Add to cart</a></div></a></div>-->
                                <!--<div class="align-self-center px-2 btn" title="Add to Wishlist"><i class="fa fa-heart-o"></i></div>
                            <div class="align-self-center px-2 "><a href="#" class='text-dark' title="View Details"><i class="fa fa-eye"></i></a></div>-->
                            </div>
                        </div>

                        <b class="text-white  ">
                            <div class='countDown pl-3' style="background-color:black; margin-top:12px;">
                                <ul class="p-0 m-0">
                                    <li><span id="days"></span>days</li>
                                    <li><span id="hours"></span>Hours</li>
                                    <li><span id="minutes"></span>Minutes</li>
                                    <li><span id="seconds"></span>Seconds</li>
                                </ul>
                            </div>
                        </b>
                    </div>

                </div>
                @endforeach
            </div>
            @else
            @foreach($flash_sales_data as $flash)
            <div class="col-md-12 col-lg-6 pb-5 h-100">
                <img src="{{URL::asset('public/upload/flashbanner/'.$flash->image)}}" width="100%">
            </div>
            @endforeach
            @endif
            <div class="col-md-12 col-lg-6 pb-5 h-100 pr-2">
                <div class="owl-carousel shadow owl-theme owl-custom-arrow" id="owl-testimonials">
                    @foreach($banner_data as $banner)
                    <div class="item">
                        <img src="{{URL::asset('public/upload/offerbanner/'.$banner->image)}}" width="100%" height="100%">

                    </div>
                    @endforeach
                </div>
            </div>


        </div>

    </div>


    <!--===========================  blog =========================-->


    <!-- <div class="container-fluid">
        <div class="row position-relative maxWidhtContainer">
            <div class="col-12 pb-sm-5 pb-3  text-center ">
                <div class="h5 m-0 font-weight-bold headStyle">Bulk Ordering</div>
                <div class="h5 m-0 font-weight-bold headStyle">Latest Blogs</div>
            </div>

            <div class="col-12 btnDefault pb-sm-0 pb-4">
                <a href="{{url('blog')}}">
                    <button type="submit" class="cta border-0">
                        <span>View More</span>
                        <svg width="13px" height="10px" viewBox="0 0 13 10">
                            <path d="M1,5 L11,5"></path>
                            <polyline points="8 1 12 5 8 9"></polyline>
                        </svg>
                    </button>
                </a>
            </div>

        </div>
    </div> -->



     <!-- bulk ordering code start -->


     <style>
.bulk-enquiry-wrapper{
    text-align:center;
    padding:20px 0;
}

.bulk-enquiry-btn{
    display:inline-flex;
    align-items:center;
    gap:12px;
    padding:16px 34px;
    border-radius:60px;
    text-decoration:none;
    color:#fff !important;
    font-size:16px;
    font-weight:600;
    letter-spacing:.5px;
    background:linear-gradient(135deg,#25D366,#128C7E);
    box-shadow:0 10px 30px rgba(37,211,102,.25);
    transition:all .35s ease;
    position:relative;
    overflow:hidden;
}

.bulk-enquiry-btn::before{
    content:'';
    position:absolute;
    top:0;
    left:-120%;
    width:100%;
    height:100%;
    background:linear-gradient(
        90deg,
        transparent,
        rgba(255,255,255,.25),
        transparent
    );
    transition:.8s;
}

.bulk-enquiry-btn:hover::before{
    left:120%;
}

.bulk-enquiry-btn:hover{
    transform:translateY(-4px);
    box-shadow:0 18px 40px rgba(37,211,102,.35);
}

.bulk-enquiry-btn .icon{
    width:42px;
    height:42px;
    min-width:42px;
    border-radius:50%;
    background:rgba(255,255,255,.18);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
}

.bulk-enquiry-btn .content{
    display:flex;
    flex-direction:column;
    align-items:flex-start;
    line-height:1.2;
}

.bulk-enquiry-btn .small-text{
    font-size:11px;
    opacity:.85;
    text-transform:uppercase;
    letter-spacing:1px;
}

.bulk-enquiry-btn .main-text{
    font-size:17px;
    font-weight:700;
}

@media(max-width:768px){
    .bulk-enquiry-btn{
        width:95%;
        justify-content:center;
        padding:14px 20px;
    }
}
</style>

<div class="container-fluid">
    <div class="row position-relative maxWidhtContainer">

        <div class="col-12 pb-sm-5 pb-3 text-center">
            <div class="h5 m-0 font-weight-bold headStyle">
                Bulk Enquiry
            </div>
        </div>

        <div class="col-12 bulk-enquiry-wrapper">

            <a href="https://wa.me/916375134498?text=Hello%20Zouple%20Team,%0A%0AI%20am%20interested%20in%20placing%20a%20bulk%20order.%20Please%20share%20product%20details,%20wholesale%20pricing,%20MOQ%20(Minimum%20Order%20Quantity),%20and%20delivery%20timelines.%0A%0AThank%20you."
               target="_blank"
               class="bulk-enquiry-btn">

                <div class="icon">📦</div>

                <div class="content">
                    <span class="small-text">Wholesale & Bulk Orders</span>
                    <span class="main-text">Bulk Enquiry on WhatsApp</span>
                </div>

            </a>

        </div>

    </div>
</div>

    <!-- <div class="container-fluid">
    <div class="row maxWidhtContainer">
        @foreach($blog_data as $blog)
        <div class="col-md-6  pb-5">
            <div class="blog-card row mx-0">
                <div class="meta col-12 col-md-6">
                    <div class="photo" style="background-image: url({{URL::asset('public/upload/blog/'.$blog->front_image)}}); height:300px; background-size:cover"></div>
                    <ul class="details list-unstyled" >
                        <li class="author" ><a href="{{url('blogShow',$blog->slug)}}">{{$blog->heading}}</a></li>
                        <li class="date py-2" >
                            <?php 

                                $dates = $blog->date;
                                $date=date_create($dates);
                                echo date_format($date,"M, d Y");

                                ?>
                        </li>

                    </ul>
                </div>
                <div class="description col-12 col-md-6 text-justify">
                    <h6 style="font-size:16px !important; height:19px; overflow:hidden;">{{$blog->heading}}</h6>
                    <div style="font-size:14px;height:150px">
                     <?php
                         $des = $blog->description;
                          echo $description = str_limit($des, 200) ; 
                     ?>
                        </div>
                    <p class="read-more" style="font-size:16px">
                        <a href="{{url('blogShow',$blog->slug)}}">Read More</a>
                    </p>
                </div>
            </div>
        </div>
        @endforeach





    </div> -->

</div>
</section>
<!--=========================   sub scribe ===========================-->



@stop
