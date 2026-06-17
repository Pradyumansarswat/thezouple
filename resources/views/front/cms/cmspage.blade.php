@extends('front.layout.default_layout')
@section('content')


@foreach($cms_data as $data)
<!-- Banner Code Start -->

@include('front.layout.banner')

<!-- Banner Code End -->
<!--======================   breadcumbs =======================-->
<div class="container-fluid   " style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
    <div class="row px-5 maxWidhtContainer">
        <div class=" col-12 align-self-center col-sm-12 col-md-12  h6 m-0  text-white">
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item text-white">{{$data->title}}</li>
                </ol>
            </nav>
        </div>
        
    </div>
</div>
<div class="container">
    <div class="row mt-4 py-4">
        <div class=" col-12 text-left p">
            <?php echo $data->description; ?>
        </div>
    </div>
</div>





@endforeach
@stop
