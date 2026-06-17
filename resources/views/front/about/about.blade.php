@extends('front.layout.default_layout')
@section('content')



    <!-- Banner Code Start -->

    @include('front.layout.banner')

    <!-- Banner Code End -->
    <!--======================   breadcumbs =======================-->
    <div class="container-fluid   mb-5" style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
        <div class="row px-5 maxWidhtContainer">
            <div class=" col-12 align-self-center col-sm-5 col-md-6  h6 m-0  text-white" >
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.html" class=" h6 m-0 text-white">About Us</a></li>


                </ol>
            </nav>

        </div>
        </div>


    </div>
    <?php 
        $i = 1;

    ?>
    <div class="container">

        @foreach($about_data as $data)
        @if($i % 2 == 0)
        <div class="row my-5">
            <div class="col-sm-6">
            <img src="{{URL::asset('public/upload/about/'.$data->image)}}" width="100%">
            </div>
            <div class="col-sm-6">
                <div style="font-size: 18px!important;background-color:black; color:white; " class="m-0 pt-2 pb-1 px-3">{{$data->title}}</div><br>
                <div> <?php echo $data->description; ?></div>
               
            </div>

        </div>
        @else
        <div class="row my-5">
            <div class="col-sm-6">
                <div style="font-size: 18px!important;background-color:black; color:white; " class="m-0 pt-2 pb-1 px-3">{{$data->title}}</div><br>
                <div> <?php echo $data->description; ?></div>
            </div>
            <div class="col-sm-6">
                <img src="{{URL::asset('public/upload/about/'.$data->image)}}" width="100%">
            </div>

        </div>

       @endif
        <?php $i++; ?>
        @endforeach




    </div>


@stop