@extends('front.layout.default_layout')
@section('content')

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var element_name = $("#element_name").val();
        $("#nxtbtn").click(function() {
            var element = $("input[name='element']:checked").val();
            var order_no = $("#order_no").val();
            var element_name = $("#element_name").val();
            if (element) {
                window.location = "../nextElementList?element=" + element + "&order_no=" + order_no + "&element_name=" + element_name;
            } else {
                swal("Oops!", 'Currently you are not select any element type, please select any element. ', "warning");
            }
        });
        $('#show_' + element_name).addClass('border');
    });

</script>

<script type="text/javascript">
    $(document).ready(function() {
        var element_name = $("#element_name").val();
        $("#finbtn").click(function() {
            var element = $("input[name='element']:checked").val();
            var order_no = $("#order_no").val();
            var element_name = $("#element_name").val();
            if (element) {
                window.location = "../seeYourShirt?element=" + element + "&order_no=" + order_no + "&element_name=" + element_name;
            } else {
                swal("Oops!", 'Currently you are not select any element type, please select any element. ', "warning");
            }
        });
        $('#show_' + element_name).addClass('border');
    });

</script>



<!--===============  section =====================-->

<!-- Banner Code Start -->

@include('front.layout.banner')

<!-- Banner Code End -->

<!--======================   breadcumbs =======================-->
<div class="container-fluid  mb-5 " style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
    <div class="row px-5 maxWidhtContainer">
        <div class=" col-12 align-self-center col-sm-5 col-md-6  h5 m-0  text-white">
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.html" class=" h6 m-0 text-white">{{$element_name}}</a></li>


                </ol>
            </nav>

        </div>
    </div>
</div>
<?php 
$prev = "Step ".($order_no);
$Next = "Step ".($order_no+2);
$current = $order_no+1;
if(count($element_show_data)+1 == $current)
{
    $Next = "Shirt Process";
}
?>
<div class="container-fluid scrSys">
    <div class="row position-relative maxWidhtContainer">
        <div class="col-12 pb-5  text-center ">
            <div class="h5 m-0  font-weight-bold headStyle headstyle2">Choose Your Shirt {{$element_name}} ( Step {{$current}} / {{count($element_show_data)+1}} ) </div>
        </div>
        <div class="col-12 btnDefault  d-flex  scrlmt  justify-content-between">


            <a>
                <button type="button" id="nxtbtn" class="cta border-0">
                    <span>{{$Next}}</span>
                    <svg width="13px" height="10px" viewBox="0 0 13 10">
                        <path d="M1,5 L11,5"></path>
                        <polyline points="8 1 12 5 8 9"></polyline>
                    </svg>
                </button>
            </a>
            <a>
                <button type="button" id="finbtn" class="cta border-0">
                    <span>Shirt Process</span>
                    <svg width="13px" height="10px" viewBox="0 0 13 10">
                        <path d="M1,5 L11,5"></path>
                        <polyline points="8 1 12 5 8 9"></polyline>
                    </svg>
                </button>
            </a>
        </div>

    </div>
</div>
<div class="container">
    <div class="row mb-5">
        <div class="col-sm-12 col-md-3 col-lg-3 px-0 border pb-3 d-none d-md-block" style="background-color:black;">

            <ul class="list-unstyled m-0   dashboardLi ">
                <li class="dashLiactive bg-secondary pt-3 font-weight-bold"><a href="#" class="colorwhite color0-hov color0">
                        CATEGORY
                    </a>
                </li>
                @foreach($element_show_data as $element_show)
                <li class="pl-5">
                    <a href="#" class="colorwhite color0-hov">
                        {{$element_show->name}}
                    </a>
                </li>
                @endforeach

            </ul>
        </div>
        <div class="col-sm-12 col-md-9 col-lg-9 border py-3">
            <div class="row shirtCheck">
                <input type="hidden" name="element_name" id="element_name" value="{{$element_name}}">
                <input type="hidden" name="order_no" id="order_no" value="{{$order_no}}">
                @foreach($element_data as $element)
                <div class="col-sm-6 col-md-4 col-lg-3 mb-5 ">
                    <label for="{{$element->element_value_id}}" class="card" style="border-radius: 15px; overflow: hidden;">



                        <div class="card-body cardDesign p-0 position-relative">
                            <img src="{{ z_media_url($element->image, 'shirt') }}" width="100%">

                            <div class='form  checkCloth bg-danger' style="border-radius: 30px;">
                                <input type="radio" name="element" id="{{$element->element_value_id}}" class='checkbox check6' value="{{$element->element_value_id}}" {{$ele_val_id==$element->element_value_id ? "checked" : ""}}>

                            </div>
                        </div>
                        <div class="card-footer text-white" style="background-color:black;">
                            <div class="h6 m-0 text-white"> {{$element->attribut_name}}</div>

                        </div>
                    </label>
                </div>
                @endforeach
            </div>
            @if($element_name == "size" || $element_name == "Size" || $element_name == "SIZE")
            <div class="row">
                @foreach($size_img as $ims)
                <div class="col-sm-12">
                    <img src="{{ z_media_url($ims->image, 'shirt') }}" width="100%">
                </div>
                @endforeach
            </div>
            @endif
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3 px-0 border pb-3 d-block d-md-none" style="background-color:black;">

            <ul class="list-unstyled m-0   dashboardLi ">
                <li class="dashLiactive bg-secondary pt-3 font-weight-bold"><a href="#" class="colorwhite color0-hov color0">
                        CATEGORY
                    </a>
                </li>
                @foreach($element_show_data as $element_show)
                <li class="pl-5">
                    <a href="#" class="colorwhite color0-hov">
                        {{$element_show->name}}
                    </a>
                </li>
                @endforeach

            </ul>
        </div>
    </div>
</div>
<div class="container d-block d-sm-none d-md-none">
    <div class="row">
        <div class="col-sm-12 bg-danger py-2 text-center text-white font-weight-bold" style="position:fixed; bottom:0;">
            Process Step {{$current}} / {{count($element_show_data)+1}}
        </div>
    </div>
</div>
<!--=========  contnet =========-->

<!--===================  end section  ====================-->
@stop
