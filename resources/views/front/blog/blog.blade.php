@extends('front.layout.default_layout')
@section('content')



    <!-- Banner Code Start -->

    @include('front.layout.banner')

    <!-- Banner Code End -->
    <!--======================   breadcumbs =======================-->
    <div class="container-fluid   mb-5" style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
        <div class="row px-5 maxWidhtContainer">
            <div class=" col-12 align-self-center col-sm-12 col-md-12  h6 m-0  text-white">
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item text-white">Blogs</li>
                </ol>
            </nav>
        </div>
        </div>


    </div>



    <div class="container">

        @foreach($blog_data as $blog)
        <div class="row my-5">

            <div class="col-12 p-0 col-md-4 border">

                <img src="{{URL::asset('public/upload/blog/'.$blog->image)}}" width="100%">
            </div>

            <div class="card  border-0 col-12 col-md-8 p-0">
                <div class="card-body p-0">
                    <div class="h4 m-0 py-2 px-3">
                        {{$blog->heading}}
                    </div>
                    <div class="p text-justify px-3 py-2 pb-3">
                        
                        <?php
                            $des = $blog->description;

                            echo $description = Str::words($des, '130');
                         ?>
                       <a href="{{url('blogShow',$blog->slug)}}"><b>Read More</b></a>
                    </div>
                </div>
                <div class="card-footer text-white  border-0 text-right " style="background-color:black;">
                    Posted: <?php

                            $date = $blog->date; 

                            $date=date_create($date);
                            echo date_format($date,"M, d Y");

                            ?>
                </div>

            </div>
        </div>

        @endforeach

        {{$blog_data->links() }}




    </div>





</section>


@stop
