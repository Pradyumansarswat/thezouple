@extends('front.layout.default_layout')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    function yourQueryText() {
        var queryText = $('#your_query').val();
        $.ajax({
            type: 'get',
            beforeSend: function() {
                $('#wait').show();
            },
            complete: function() {
                $('#wait').hide();
            },
            url: 'querySetSession?queryText=' + queryText,
            success: function(data) {
                /*alert("Mahi");*/
                window.location = "goDesignCheckout";
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
<div class="container-fluid   mb-5" style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
    <div class="row px-5 maxWidhtContainer">
        <div class=" col-12 align-self-center col-sm-5 col-md-6  h6 m-0  text-white" >
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.html" class=" h6 m-0 text-white">Customized Shirt</a></li>


                </ol>
            </nav>

        </div>
    </div>


</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 text-danger mb-5">
            <div class="h5 m-0 font-weight-bold headStyle headstyle2 text-center">Thankyou for desgined Your Shirt </div>
        </div>
    </div>
</div>




<div class="container mb-5">
    <div class="row">
        <div class="col-12">
            <table class="table table-hover text-center table-bordered table-responsive-sm">
                <thead class="thead-dark ">
                    <tr>
                        <th scope="col">Sr. No.</th>
                        <th scope="col">Element</th>
                        <th scope="col">Element Value</th>
                        <th scope="col">Edit</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @php $i = 1 @endphp
                    @foreach($data as $key => $dt)
                    @if($key == "febric")
                    <tr>
                        <th scope="row">1</th>
                        <td class="align-self-center">Febric</td>
                        <td>
                            <img src="{{ z_media_url($febricImage[$dt], 'shirt') }}" width="100%" style="max-height: 100px; width:auto;">
                            <br>
                            <b>{{$febricName[$dt]}}</b>
                        </td>
                        <td class="text-center"><a href="{{url('selectedFebricChange',[$dt])}}" class="text-danger"><i class="fa fa-pencil pr-2" style="font-size:25px;"></i></a></td>
                    </tr>
                    @else
                    <tr>
                        <th scope="row">{{$i}}.</th>
                        <td class="align-self-center">{{$key}}</td>
                        <td>

                            <img src="{{ z_media_url($elementValueImage[$dt], 'shirt') }}" width="100%" style="max-height: 100px; width:auto;">
                            <br>
                            <b>{{$elementValueName[$dt]}}</b>
                        </td>
                        <td class="text-center"><a href="{{url('selectedElementChange',[$dt])}}" class="text-danger"><i class="fa fa-pencil pr-2" style="font-size:25px;"></i></a></td>
                    </tr>
                    @endif
                    @php $i++ @endphp
                    @endforeach
                </tbody>
            </table>



        </div>

    </div>
    <div class="row">
        <div class="col-12 col-md-12 col-sm-12 pb-3">
            <textarea name="your_query" id="your_query" class="form-control" placeholder="Please mention here for additional requirements"></textarea>
        </div>
    </div>
    <div class="row">
        @php $i = 1 @endphp
        @foreach($data as $key => $dt)
        @if($key == "febric")
        <div class="col-12 col-md-6  ">
            <?php 
                $currencySession = Session::get('currency');

                 if($currencySession == "rupee_price")
                 {
                     $iicon = "fa fa-inr";
                     $proPrice = $febricAmount[$dt];

                 }
                 elseif($currencySession == "dollar_price")
                 {
                     $iicon = "fa fa-usd";
                     $proPrice = $febricDollrtAmount[$dt];
                 } 
                 elseif($currencySession == "euro_price")
                 {
                     $iicon = "fa fa-eur";
                     $proPrice = $febricEuroAmount[$dt];
                 }
                 else
                 {
                    $iicon = "fa fa-usd";
                    $proPrice = $febricDollrtAmount[$dt];
                 }
                ?>
            <div style="border:2px solid black;">
                <div class="d-flex  py-1 px-2 justify-content-between text-left">
                    <div class="w-100">Net Amount : </div>

                    <div class='w-100 text-left'><i class="{{$iicon}} pr-2"></i><span>{{$proPrice}}</span>/-</div>

                </div>


                <div class="d-flex py-1 px-2  text-white justify-content-between text-left" style=" background-color:black; ">
                    <div class="w-100">Total : </div>
                    <div class='w-100 text-left'><i class="{{$iicon}} pr-2"></i><span>{{$proPrice}}</span>/-</div>
                </div>

            </div>

        </div>
        @endif
        @php $i++ @endphp
        @endforeach

        <div class="col-12 col-md-6 h-100 align-self-center row ">
            <div class="col-12  col-sm-12 col-lg-5 d-flex py-3">
                <div class="align-self-center">
                    <a href="{{url('clearDesign')}}" class="text-white align-self-center">
                        <button type="submit" class="cta border-0 ">
                            <span>Clear Cart</span>
                            <svg width="13px" height="10px" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
                        </button>
                    </a>
                </div>
            </div>




            @if(isset(Auth::user()->id))
            <div class="col-12 col-sm-12 col-lg-7  d-flex py-3">
                <!-- Button -->
                <a onClick="yourQueryText()" href="#" class="text-white align-self-center">
                    <button type="submit" class="cta border-0">
                        <span>Process to Checkout</span>
                        <svg width="13px" height="10px" viewBox="0 0 13 10">
                            <path d="M1,5 L11,5"></path>
                            <polyline points="8 1 12 5 8 9"></polyline>
                        </svg>
                    </button>
                </a>
            </div>

            @else

            <div class="col-12 col-sm-12 col-lg-7  d-flex py-3" data-target="#logSign" data-toggle="modal">
                <!-- Button -->
                <a class="text-white align-self-center">
                    <button type="submit" class="cta border-0">
                        <span>Process to Checkout</span>
                        <svg width="13px" height="10px" viewBox="0 0 13 10">
                            <path d="M1,5 L11,5"></path>
                            <polyline points="8 1 12 5 8 9"></polyline>
                        </svg>
                    </button>
                </a>
            </div>
            @endif
        </div>

    </div>

</div>








</section>


@stop
