@extends('front.layout.default_layout')
@section('content')

<!-- Banner Code Start -->

@include('front.layout.banner')

<!-- Banner Code End -->

<!--======================   breadcumbs =======================-->
<div class="container-fluid  mb-5 " style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
    <div class="row px-5 maxWidhtContainer">
        <div class=" col-12 align-self-center col-sm-12 col-md-12  h6 m-0  text-white">
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item text-white">{{$page_head}}</li>
                </ol>
            </nav>
        </div>
    </div>


</div>

<div class="container">
    @if(!$reviews_list->isEmpty())
    @foreach($reviews_list as $data)
    <?php
        $str = $data->star;
        $strs = substr($str, 5, 6);
    ?>
    <div class="row py-3">
        <div class="col-sm-1">
            <?php $userImageProfile = $data->user_profile; ?>
            @if($userImageProfile == "")
            <h6>No Image Found </h6>
            @else
            <img src="{{ z_media_url($data->user_profile, 'review') }}" width="50px" height="50px" class="rounded-circle border">
            @endif
        </div>
        <div class="col-sm-11">
            <div class="row">
                <div class="col-sm-12">
                    {{$data->name}}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    @for($i=1; $i<=$strs; $i++) <i class="fa fa-star text-warning"></i>
                        @endfor
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                                         $des = $data->description;
                                          echo $description = str_limit($des, 250) ; 
                                     ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php 
                                        $reviewProductImage = $data->review_product_image;
                                        $imgs = json_decode($data->review_product_image);
                                    ?>
                    @if($reviewProductImage == "")
                    <h6>No Image Found </h6>
                    @else
                    @foreach($imgs as $vals)
                    <img src="{{ z_media_url($vals, 'review') }}" width="50px" height="50px">
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>


    @endforeach
    @else
    <div class="text-left">Currently no reviews available for this product</div>
    @endif

</div>





@stop
