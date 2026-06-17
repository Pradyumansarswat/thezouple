@extends('front.layout.default_layout')
@section('content')

<!--======================   slider wrapper ======================-->

<div class="container-fluid pb-5 sliderWrapper">
    <div class="row">
        <div class="col-12 p-0 slidehover text-center m-auto">
            <video autoplay muted loop id="myVideo">
                <source src="{{URL::asset('public/front/video/Official%20Rolex%20Website%20-%20Swiss%20Luxury%20Watches_2.mp4')}}" type="video/mp4" width="100%">

            </video>

            <div class="slideHoverContent  text-center m-auto  maxWidhtContainer">
                <div class="owl-carousel  owl-theme owl-custom-arrow align-self-center" id="owl-attractions">
                    <div class="item pr-2 pl-1 ">
                        <div class="h-100">
                            <div class="h1 m-0  text-center ">
                                The New Arrival Shirt
                            </div>
                            <div class="h4 m-0 text-center">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </div>
                            <div class="viewBtn2">
                                <a href="#">view more</a>
                            </div>
                        </div>
                    </div>
                    <div class="item pr-2 pl-1 ">
                        <div class="">
                            <div class="h1 m-0  text-center ">
                                The New Arrival Shirt 2
                            </div>
                            <div class="h4 m-0 text-center">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </div>
                            <div class="viewBtn2">
                                <a href="#">view more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!--====================   category =============-->

<div class="container">
    <div class="row maxWidhtContainer">
        <div class="col-12 pb-5  text-center">
            <div class="h5 m-0 font-weight-bold headStyle">Category</div>
        </div>

    </div>
</div>


<div class="container-fluid pb-5">
    <div class="row maxWidhtContainer">
        <div class="col-12 pl-0">
            <div class="owl-carousel owl-theme owl-custom-arrow" id="owl-tour-offers">
                @foreach($cate_data as $data)
                <div class="item px-2 ">
                    <a href="{{url('categories',$data->slug)}}" class="text-dark">
                        <div class="cards cardCat cards--two">
                            <img src="{{URL::asset('public/upload/category/'.$data->image)}}" class="img-responsive" alt="Cards Image" width="100%">
                            <span class="cards--two__rect cards--two__rect__Cat"></span>
                            <p>{{$data->title}}</p>

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

<div class="container">
    <div class="row pb-5 maxWidhtContainer">
        <div class="col-12 text-center ">
            <div class="h2 font-weight-normal">ZOUPLE SHIRTS</div>
        </div>
        <div class="h5 px-5 font-weight-normal text-center">
            Zouple shirts are crafted from the finest raw materials and assembled with scrupulous attention to detail. Every component is designed, developed and produced in-house to the most exacting standards.
        </div>
    </div>
</div>

<!--========================  end of about us ==============-->

<!--====================  new arivals ====================-->



<div class="container-fluid">
    <div class="row position-relative maxWidhtContainer">
        <div class="col-12 pb-5  text-center ">
            <div class="h5 m-0 font-weight-bold headStyle">New Arrivals</div>
        </div>

        <div class="col-12 btnDefault">
            <a href="#">
                <button type="submit" class="cta border-0">
                    <span>view more</span>
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
                <div class="item px-2">
                    <a href="{{url('productShow', $arrivals->slug)}}">
                        <div class="card cusCard" style="border-radius: 15px; overflow: hidden;">
                            <div class="card-body p-0 position-relative">
                                <img src="{{URL::asset('public/upload/product/'.$arrivals->product_header_image)}}" width="100%">


                                <ul class="cards__list">
                                    <li><a href='#' class="text-white" title="Add to Cart"><i class="fa fa-shopping-bag"></i></a></li>

                                    
                                    @if(isset(Auth::user()->id))
                                    @if(in_array($arrivals->product_id, $mywishList))
                                    <li><a  onclick="removeProductFav({{$arrivals->product_id}})" class="text-white" title="Add to Wishlist">
                                        <i class="fa fa-heart" aria-hidden="true"></i>

                                    </a></li>
                                    @else
                                    <li><a  onclick="addProductFav({{$arrivals->product_id}})" class="text-white" title="Add to Wishlist">
                                        <i class="fa fa-heart-o"></i>
                                        
                                    </a></li>
                                    @endif
                                    @else
                                    <li><a data-toggle="modal" data-target="#logSign" class="text-white" title="Add to Wishlist"><i class="fa fa-heart-o"></i></a></li>
                                    @endif




                                    <li><a href="#" class="text-white" title="View Details"><i class="fa fa-eye"></i></a></li>

                                </ul>


                            </div>

                            <div class="card-footer text-white" style="background-color:black;">
                                <div class="position-absolute indexRate" style="bottom:12%;">
                                    
                                    <?php
                                    $discount = $proPrice[$arrivals->product_id]-($proPrice[$arrivals->product_id]*$proDiscount[$arrivals->product_id]/100);
                                    ?>
                                    <div style="font-size: 18px!important;"><i class="fa fa-inr text-white pr-2"></i><span>{{$discount}}</span>/-</div>

                                    <div class="text-secondary py-2 " style="text-decoration: line-through;"><i class="fa fa-inr  pr-2"></i><span>{{$proPrice[$arrivals->product_id]}}</span>/-</div>
                                </div>


                                <div class="h5 m-0 text-white"> {{$arrivals->product_title}}</div>


                            </div>

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

<div class="container-fluid pb-5">
    <div class="row">
        <div class="col-12 p-0 videoBlog position-relative">
            <video autoplay muted loop id="myVideo2">
                <source src="{{URL::asset('public/front/video/Official%20Rolex%20Website%20-%20Swiss%20Luxury%20Watches.mp4')}}" type="video/mp4" width="100%" height="400px">

            </video>

            <div class="videoHover d-flex maxWidhtContainer">
                <div class="align-self-center text-white">
                    <div class="h3 m-0 text-center">Lorem Ipsum is simply dummy text </div>
                    <div class="h5 m-0 text-center">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</div>
                </div>
            </div>
        </div>
    </div>

</div>






<!--============================ end video blog ==============-->


<!--====================  feature ====================-->



<div class="container-fluid">
    <div class="row position-relative maxWidhtContainer">
        <div class="col-12 pb-5  text-center ">
            <div class="h5 m-0 font-weight-bold headStyle">Feature Products</div>
        </div>

        <div class="col-12 btnDefault">
            <a href="#">
                <button type="submit" class="cta border-0">
                    <span>view more</span>
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
            <div class="owl-carousel owl-theme owl-custom-arrow" id="owl-company-logo">

                @foreach($featured_products as $feature)
                <div class="item px-2">
                    <a href="#">
                        <div class="card cusCard" style="border-radius: 15px; overflow: hidden;">
                            <div class="card-body p-0 position-relative">
                                <img src="{{URL::asset('public/upload/product/'.$feature->product_header_image)}}" width="100%">


                                <ul class="cards__list">
                                    <li><a href='#' class="text-white" title="Add to Cart"><i class="fa fa-shopping-bag"></i></a></li>
                                    <li><a href='#' class="text-white" title="Add to Wishlist"><i class="fa fa-heart-o"></i></a></li>
                                    <li><a href="#" class="text-white" title="View Details"><i class="fa fa-eye"></i></a></li>

                                </ul>


                            </div>

                            <div class="card-footer text-white" style="background-color:black;">
                                <div class="position-absolute indexRate" style="bottom:12%;">
                                    <?php
                                    $discount = $proPrice[$feature->product_id]-($proPrice[$feature->product_id]*$proDiscount[$feature->product_id]/100);
                                    ?>
                                    <div style="font-size: 18px!important;"><i class="fa fa-inr text-white pr-2"></i><span>{{$discount}}</span>/-</div>



                                    <div class="text-secondary py-2 " style="text-decoration: line-through;"><i class="fa fa-inr  pr-2"></i><span>{{$proPrice[$feature->product_id]}}</span>/-</div>
                                </div>


                                <div class="h5 m-0 text-white"> {{$feature->product_title}}</div>


                            </div>

                        </div>
                    </a>

                </div>
                @endforeach

            </div>

        </div>

    </div>

</div>




<!--=====================   end features ================-->

<!--======================   flash sale and banner =======-->

<div class="container-fluid ">
    <div class="row maxWidhtContainer">
        <div class="col-md-12 col-lg-6 h-100 pb-5">
            <div class="row shadow">
                <div class="col-12  col-sm-4 col-md-4 ">
                    <div class="box">
                        <div class="ribbon ribbon-top-left"><span>Flash Sale</span></div>
                        <img src="{{URL::asset('public/front/images/banner/flash_sale.jpg')}}" width="100%">
                    </div>
                </div>

                <div class="col-12 col-sm-8 col-md-8">
                    <div class=" m-0" style="font-size: 18px!important;">Boys Navy Blue Formal Shirt And Tie </div>

                    <div class="text-secondary py-1 text-justify">
                        Pearl & Jade Pothos is a plant which has small leaves with spots in white, grey and green color. She possesses the capacity to magically change any ....
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="h6 d-flex py-1 align-self-center">
                            <div>
                                <i class='fa fa-inr pr-2'></i><span>200</span>/-
                            </div>

                            <div style="color:gray; padding-left:1rem;">
                                <i class='fa fa-inr pr-2'></i><span style="text-decoration: line-through;">400</span>/-
                            </div>
                        </div>

                        <div class="d-flex align-self-center" style="font-size: 20px;">
                            <div class="align-self-center px-2 btn"><i class="fa fa-shopping-bag" title="Add to Cart"></i></div>
                            <div class="align-self-center px-2 btn" title="Add to Wishlist"><i class="fa fa-heart-o"></i></div>
                            <div class="align-self-center px-2 "><a href="#" class='text-dark' title="View Details"><i class="fa fa-eye"></i></a></div>
                        </div>
                    </div>


                    <div class='countDown'>
                        <ul class="p-0">
                            <li><span id="days"></span>days</li>
                            <li><span id="hours"></span>Hours</li>
                            <li><span id="minutes"></span>Minutes</li>
                            <li><span id="seconds"></span>Seconds</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-12 col-lg-6 pb-5 h-100">
            <div class="owl-carousel shadow owl-theme owl-custom-arrow" id="owl-testimonials">
                <div class="item pr-2 pl-1 ">
                    <img src="{{URL::asset('public/front/images/banner/1.jpg')}}" width="100%" height="100%">

                </div>
                <div class="item pr-2 pl-1 ">
                    <img src="{{URL::asset('public/front/images/banner/2.jpg')}}" width="100%">

                </div>
                <div class="item pr-2 pl-1 ">
                    <img src="{{URL::asset('public/front/images/banner/3.jpg')}}" width="100%">

                </div>



            </div>
        </div>


    </div>

</div>


<!--===========================  blog =========================-->


<!-- <div class="container-fluid">
    <div class="row position-relative maxWidhtContainer">
        <div class="col-12 pb-5  text-center ">
            <div class="h5 m-0 font-weight-bold headStyle">Latest Blogs</div>
        </div>

        <div class="col-12 btnDefault">
            <a href="#">
                <button type="submit" class="cta border-0">
                    <span>view more</span>
                    <svg width="13px" height="10px" viewBox="0 0 13 10">
                        <path d="M1,5 L11,5"></path>
                        <polyline points="8 1 12 5 8 9"></polyline>
                    </svg>
                </button>
            </a>
        </div>

    </div>
</div> -->


<!-- <div class="container-fluid">
    <div class="row maxWidhtContainer">
        @foreach($blog_data as $blog)
        <div class="col-md-6  pb-5">
            <div class="blog-card row mx-0">
                <div class="meta col-12 col-md-6">
                    <div class="photo" style="background-image: url({{URL::asset('public/upload/blog/'.$blog->image)}}); height:300px;"></div>
                    <ul class="details list-unstyled">
                        <li class="author py-2"><a href="#">{{$blog->heading}}</a></li>
                        <li class="date py-2">
                            <?php 

                                $dates = $blog->date;
                                $date=date_create($dates);
                                echo date_format($date,"M, d Y");

                                ?>
                        </li>

                    </ul>
                </div>
                <div class="description col-12 col-md-6">
                    <h5>{{$blog->heading}}</h5>

                    <p> <?php

                                 $des = $blog->description;

                                  echo $description = Str::words($des, '30'); 

                                 ?></p>
                    <p class="read-more">
                        <a href="{{url('blogShow',$blog->slug)}}">Read More</a>
                    </p>
                </div>
            </div>
        </div>
        @endforeach





    </div>

</div> -->


<!--=========================   sub scribe ===========================-->



@stop
