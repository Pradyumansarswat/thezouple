@extends('front.layout.default_layout')
@section('content')
@include('front.layout.banner')
<!--======================   breadcumbs =======================-->
    <div class="container-fluid  mb-5 " style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
        <div class="row px-5 maxWidhtContainer">
            <div class=" col-12 align-self-center col-sm-12 col-md-12  h5 m-0  text-white" style="letter-spacing: 4px; ">
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item text-white">{{$pagehead}}</li>
                </ol>
            </nav>
        </div>
        </div>


    </div>


<div class="container"> 
    <div class="row justify-content-center py-5 mt-5">
        <div class="col-md-8">
            <div class="card">
               <!-- <div class="card-header">{{ __('Verify Your Email Address') }}</div>-->

                <div class="card-body">
                    

                  <h6 class="text-dark text-center"><?php echo $user_data['msg'];?></h6>
                   <h6 class="text-center"><a href="{{ url('/') }}">GO HOME</a></h6>
                      
                </div>
            </div>
        </div>
    </div>
</div>
@endsection