@extends('front.layout.default_layout')
@section('content')

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#nxtbtn").click(function() {
            var febric = $("input[name='febric']:checked").val();
            if (febric) {
                window.location = "elementsList?febric=" + febric + "&order_no=0";
            } else {
                swal("Oops!", 'Currently you are not select any febric type, please select any febric. ', "warning");
            }
        });
    });
    
    function currency(sel) {
        var currency= sel.value;
        $.ajax({
            url: 'changeCurrency/'+ currency,
            type: "GET",
            beforeSend: function() {$('#wait').show();},
            complete: function() {$('#wait').hide();},
            success: function(data) {
               location.reload();
            }
        });
    }

</script>





<!--===============  section =====================-->

<!-- Banner Code Start -->

@include('front.layout.banner')

<!-- Banner Code End -->

<!--======================   breadcumbs =======================-->
<div class="container-fluid  mb-4 " style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
    <div class="row px-5 maxWidhtContainer">
        <div class=" col-12 align-self-center col-sm-5 col-md-6  h6 m-0  text-white" >
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.html" class=" h6 m-0 text-white">Customize Shirt</a></li>


                </ol>
            </nav>

        </div>
    </div>
</div>
<div class="container-fluid scrSys">
    <div class="row position-relative maxWidhtContainer">
        <div class="col-12 pb-5  text-center ">
            <div class="h5 m-0  font-weight-bold headStyle headstyle2">
                Choose Your Shirt Febric <span class="">(Step 1 / {{count($element_show_data)+1}})</span>
               <!-- <br>
                <span class="text-danger">Step 1/10</span>-->
            
            </div>
        </div>
        <div class="col-12 btnDefault  d-flex  scrlmt  justify-content-between">
            <a href="#">
                <!--<button type="submit" class=" cta2 border-0">
                        <svg width="13px" height="10px" viewBox="0 0 13 10">
                            <path d="M1,5 L11,5"></path>
                            <polyline points="8 1 12 5 8 9"></polyline>
                        </svg>
                        <span>Prev</span>

                    </button>-->
            </a>

            <a>
                <button type="button" id="nxtbtn" class="cta border-0">
                    <span>Step 2</span>
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
                    <a href="#" class="colorwhite color0-hov active">
                        {{$element_show->name}}
                    </a>
                </li>
                @endforeach

            </ul>
        </div>
        <div class="col-sm-12 col-md-9 col-lg-9 border py-3">
            <div class="row shirtCheck">
                @foreach($febric_data as $febric)
                <div class="col-sm-6 col-md-4 col-lg-4 mb-5 ">
                    <label for="{{$febric->febric_id}}" class="card " style="border-radius: 15px; overflow: hidden;">
                        <div class="card-body  cardDesign p-0 position-relative">
                            <img src="{{ z_media_url($febric->image, 'shirt') }}" width="100%">
                            <?php
                                if(isset($febricss))
                                {
                                    if($febricss == $febric->febric_id)
                                    {
                                        $check = "checked";
                                    }
                                    else
                                    {
                                        $check = "";
                                    }
                                    
                                }
                                else
                                {
                                     $check = "";
                                }
                                
                                ?>
                            <div class='form  checkCloth bg-danger' style="border-radius: 30px;">
                                <input type="radio" name="febric" id="{{$febric->febric_id}}" class='checkbox check6' value="{{$febric->febric_id}}" {{$check}}>
                            </div>
                        </div>
                        <div class="card-footer text-white" style="background-color:black;">
                            <div class="h6  text-white"> {{$febric->name}}</div>
                            <?php 
                            $currencySession = Session::get('currency');
                            
                             if($currencySession == "rupee_price")
                             {
                                 $iicon = "fa fa-inr";
                                 $proPrice = $febric->rupee_price;
                                 $proDarkPrice = $febric->rupee_dark_price;
                                 
                             }
                             elseif($currencySession == "dollar_price")
                             {
                                 $iicon = "fa fa-usd";
                                 $proPrice = $febric->dollar_price;
                                 $proDarkPrice = $febric->dollar_dark_price;
                             } 
                             elseif($currencySession == "euro_price")
                             {
                                 $iicon = "fa fa-eur";
                                 $proPrice = $febric->euro_price;
                                 $proDarkPrice = $febric->euro_dark_price;
                             }
                             else
                             {
                                $iicon = "fa fa-usd";
                                $proPrice = $febric->dollar_price;
                                $proDarkPrice = $febric->dollar_dark_price;
                             }
                            ?>
                            <div class=" m-0 text-white">
                                @if($proDarkPrice > 0 )
                                <i class="{{$iicon}} pr-2"></i><span>{{$proPrice}}</span>/-
                                <span class="text-secondary ml-1 ml-sm-3" style="text-decoration: line-through; font-size: 14px!important;"><i class="{{$iicon}}  pr-1"></i><span>{{$proDarkPrice}}</span>/-</span>
                                @else
                                <i class="{{$iicon}} pr-2"></i><span>{{$proPrice}}</span>/-
                                @endif
                            </div>
                        </div>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3 px-0 border pb-3 d-block d-md-none " style="background-color:black;">

            <ul class="list-unstyled m-0   dashboardLi ">
                <li class="dashLiactive bg-secondary pt-3 font-weight-bold"><a href="#" class="colorwhite color0-hov color0">
                        CATEGORY
                    </a>
                </li>
                @foreach($element_show_data as $element_show)
                <li class="pl-5">
                    <a href="#" class="colorwhite color0-hov active">
                        {{$element_show->name}}
                    </a>
                </li>
                @endforeach

            </ul>
        </div>
    </div>
</div>
<div class="container-fluid d-block d-sm-none d-md-none">
    <div class="row">
        <div class="col-sm-12 bg-danger py-2 text-center text-white font-weight-bold" style="position:fixed; bottom:0;">
            Process Step 1 / {{count($element_show_data)+1}}
        </div>
    </div>
</div>
<!--=========  contnet =========-->
</section>
<!--===================  end section  ====================-->

@stop
