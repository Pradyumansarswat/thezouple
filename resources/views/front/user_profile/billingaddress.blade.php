@extends('front.layout.default_layout')
@section('content')


<script>
    function edit_payment(address_name, mobile, address, landmark, pin, state, city_name, country, user_information_id) {

        $('#address_name').val(address_name);
        $('#mobile').val(mobile);
        $('#address').val(address);
        $('#landmark').val(landmark);
        $('#pin').val(pin);
        $('#state').val(state);
        $('#city_name').val(city_name);
        $('#country').val(country);
        $('#user_information_id').val(user_information_id);
        $("#btn_paumentbtn").attr('value', 'Update');
        $('#paymentfor_form').attr('action', 'billingpaymentUpdate');

    }

</script>






<!-- Banner Code Start -->

@include('front.layout.banner')

<!-- Banner Code End -->

<!--======================   breadcumbs =======================-->
<div class="container-fluid  mb-3 " style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
    <div class="row px-5 maxWidhtContainer">
        <div class=" col-12 align-self-center col-sm-12 col-md-12  h6 m-0  text-white">
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item text-white">Billing Address</li>
                </ol>
            </nav>
        </div>
    </div>


</div>



<div class="container">
    <div class="row mb-5">
        <div class="col-md-3 my-4">
            <div class="bg4 py-4">
                <div class="text-white ">
                    <div class="text-center h5">WELCOME</div>
                    <div class="text-center h4"><?php echo Auth::user()->name; ?></div>
                </div>

                <ul class="list-unstyled m-0   dashboardLi">
                    <li class=""><a href="{{url('dashboard')}}" class="colorwhite color0-hov ">
                            <i class="fa fa-user"></i> MY PROFILE
                        </a>
                    </li>
                    <li class=""><a href="{{url('yourOrder')}}" class="colorwhite color0-hov">
                            <i class="fa fa-shopping-bag"></i> ORDERS
                        </a>
                    </li>

                    <li class="p-t-10 p-b-10"><a href="{{url('cancleOrder')}}" class="colorwhite color0-hov">
                            <i class="fa fa-close"></i> CANCEL ORDERS
                        </a>
                    </li>

                    <li class="p-t-10 p-b-10"><a href="{{url('returnOrder')}}" class="colorwhite color0-hov">
                            <i class="fa fa-retweet"></i> RETURN ORDERS
                        </a>
                    </li>

                    <li class="p-t-10 p-b-10"><a href="{{url('exchangeOrder')}}" class="colorwhite color0-hov">
                            <i class="fa fa-retweet"></i> EXCHANGE ORDERS
                        </a>
                    </li>

                    <li class="p-t-10 p-b-10"><a href="{{url('wishlist')}}" class="colorwhite color0-hov">
                            <i class="fa fa-heart"></i> WISHLIST
                        </a>
                    </li>

                    <li class="p-t-10 p-b-10"><a href="{{url('shippingAddress')}}" class="colorwhite color0-hov">
                            <i class="fa fa-map-marker"></i> SHIPPING ADDRESS
                        </a>
                    </li>
                    <li class="dashLiactive p-t-10 p-b-10"><a href="{{url('billingAddress')}}" class="colorwhite color0-hov color0">
                            <i class="fa fa-map-marker"></i> BILLING ADDRESS
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="col-md-9 my-4">
            <div class="headDash">
                BILLING ADDRESS
            </div>
            <div class=" pad">
                <div class="row">
                    <div class="col-12 col-md-6 ">
                        <div class="border p-3">
                            <div class="h5 text-center m-0 my-3">
                                SAVED BILLING ADDRESS
                            </div>
                            @foreach($billing_address_list as $data)
                            <div class="border p-3 ">
                                <div class="col d-flex justify-content-between">
                                    <div class="h6">{{$data->address_name}}</div>
                                    <div class="btn m-0  p-0"><a href="{{url('billingDelete',$data->user_information_id)}}" onClick="return confirm('Do you want to delete shipping address details?');"><span class="basic_table_icon" style="font-size: 20px;color: red;margin-left: 20px;">X</span></a></div>
                                </div>
                                <div class="col">
                                    <div>{{$data->address}}</div>
                                    <div>{{$data->city_name}}, {{$data->state}} {{$data->pin}}</div>
                                    <div>{{$data->country}}</div>
                                    <div class="d-flex">
                                        <div class="h6 m-0 pr-2">Mobile No.</div>
                                        <div class="">+91 {{$data->mobile}}</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end btn" data-toggle="modal" data-target="#Editing">
                                    <a href="#" onclick="edit_payment('{{$data->address_name}}',{{$data->mobile}},'{{$data->address}}','{{$data->landmark}}', {{$data->pin}}, '{{$data->state}}', '{{$data->city_name}}','{{$data->country}}', {{$data->user_information_id}})">Edit</a>

                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-12 col-md-6 ">
                        <div class="border p-3">
                            <div class="h5 text-center m-0 my-3">
                                ADD NEW ADDRESS
                            </div>

                            <div class="border p-3 ">
                                <div class="text-center  " data-toggle="modal" data-target="#shipping">
                                    <div class="">
                                        <i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i>
                                    </div>
                                    <div>
                                        Add New Address
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>




        </div>




    </div>
</div>





</section>

<!--//////////////   add address modal /////////////////////-->
<div class="modal fade" id="shipping" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add <span>Billing </span>Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{url('billingaddress_save')}}" method="post">
                    @csrf
                    <input type="hidden" name="addresstype" value="Billing">
                    <div class="form-group">
                        <label for="name">Name*</label>
                        <input type="text" placeholder="Enter your Name" class="form-control" name="address_name" required>
                    </div>
                    <div class="form-group">
                        <label for="Phnumber">Mobile Number</label>
                        <div class="input-group mb-3">

                            <input type="number" id="Phnumber" class="form-control" placeholder="Enter Mobile Number" name="mobile" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Address">Address</label>
                        <input type="text" placeholder="Enter your Address" class="form-control" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="City">LandMark</label>
                        <input type="text" placeholder="Enter your nearest Landmark" class="form-control" required name="landmark">
                    </div>

                    <div class="row">
                        <div class="form-group col-12 col-sm-4">
                            <label for="City2">City</label>
                            <input type="text" placeholder="City" class="form-control" name="city_name" required>
                        </div>

                        <div class="form-group col-12 col-sm-4">
                            <label for="state">State</label>
                            <input type="text" placeholder="State" class="form-control" name="state" required>
                        </div>

                        <div class="form-group col-12 col-sm-4">
                            <label for="country">Country</label>
                            <input type="text" placeholder="Country" class="form-control" name="country" required>
                        </div>

                        <div class="form-group col-12 col-sm-4">
                            <label for="country">Pin Code</label>
                            <input type="number" placeholder="Pin Code" class="form-control" name="pin" required>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="cta border-0">
                            <span>Save</span>
                            <svg width="13px" height="10px" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
                        </button>
                    </div>
                </form>



            </div>

        </div>
    </div>
</div>


<!--////////////////////////   edit address modal //////////////-->

<div class="modal fade" id="Editing" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update <span>Billing </span>Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{url('billingaddressUpdates')}}" method="post" id="paymentfor_form">
                    @csrf

                    <input type="hidden" name="user_information_id" id="user_information_id">
                    <input type="hidden" name="addresstype" value="Billing">
                    <div class="form-group">
                        <label for="name">Name*</label>
                        <input type="text" placeholder="Enter your Name" class="form-control" id="address_name" name="address_name">
                    </div>
                    <div class="form-group">
                        <label for="Phnumber">Mobile Number</label>
                        <div class="input-group mb-3">

                            <input type="number" class="form-control" placeholder="Enter Mobile Number" name="mobile" required id="mobile">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Address">Address</label>
                        <input type="text" placeholder="Enter your Address" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="City">LandMark</label>
                        <input type="text" placeholder="Enter your nearest Landmark" class="form-control" id="landmark" name="landmark" required>
                    </div>

                    <div class="row">
                        <div class="form-group col-12 col-sm-4">
                            <label for="City2">City</label>
                            <input type="text" placeholder="City" class="form-control" id="city_name" name="city_name" required>
                        </div>

                        <div class="form-group col-12 col-sm-4">
                            <label for="state">State</label>
                            <input type="text" placeholder="State" class="form-control" id="state" name="state">
                        </div>

                        <div class="form-group col-12 col-sm-4">
                            <label for="country">Country</label>
                            <input type="text" placeholder="Country" class="form-control" id="country" name="country">
                        </div>

                        <div class="form-group col-12 col-sm-4">
                            <label for="country">Pin Code</label>
                            <input type="number" placeholder="Pin Code" class="form-control" id="pin" name="pin">
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="cta border-0 ml-auto">
                            <span>Update</span>
                            <svg width="13px" height="10px" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
                        </button>
                    </div>
                </form>



            </div>

        </div>
    </div>
</div>





<!--===================  end section  ====================-->

@stop
