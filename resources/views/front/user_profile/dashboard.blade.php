@extends('front.layout.default_layout')
@section('content')

<!--===============  section =====================-->

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
                    <li class="breadcrumb-item text-white">Profile</li>
                </ol>
            </nav>
        </div>
    </div>


</div>



<div class="container">
    <div class="row">
        <div class="col-md-3 my-4">
            <div class="bg4 py-4">
                <div class="text-white ">
                    <div class="text-center h5">WELCOME</div>
                    <div class="text-center h4"><?php echo Auth::user()->name; ?></div>
                </div>

                <ul class="list-unstyled m-0   dashboardLi">
                    <li class="dashLiactive"><a href="{{url('dashboard')}}" class="colorwhite color0-hov ">
                            <i class="fa fa-user"></i> MY PROFILE
                        </a>
                    </li>
                    <li class=""><a href="{{url('yourOrder')}}" class="colorwhite color0-hov">
                            <i class="fa fa-shopping-bag"></i> ORDERS
                        </a>
                    </li>

                    <li class=" p-t-10 p-b-10"><a href="{{url('cancleOrder')}}" class="colorwhite color0-hov">
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
                    <li class="p-t-10 p-b-10"><a href="{{url('billingAddress')}}" class="colorwhite color0-hov color0">
                            <i class="fa fa-map-marker"></i> BILLING ADDRESS
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="col-md-9 my-4">
            <div class="bg4">
                <div class="text-white profileHead">
                    My Information
                </div>
            </div>
            @foreach($user_data as $user)
            <div class="contentProfile">
                Name: <span>{{$user->name}}</span>
            </div>
            <div class="contentProfile">
                Email: <span>{{$user->email}}</span>
            </div>
            <div class="contentProfile">
                Mobile: <span>{{$user->contact}}</span>
            </div>
            @endforeach


            <div class="bg4">
                <div class="text-white  profileHead">
                    Change Password
                </div>
            </div>
            <form action="{{url('passwordUpdate')}}" method="post">
                <input type="hidden" name="Auth::user()->id" value="<?php echo Auth::user()->id;?>">
                @csrf
                <div class="form-group profileForm">
                    <input class="form-control password-field" type="password" required placeholder="Enter Old Password" name="oldpassword" id="oldpassword">
                </div>

                <div class="row px-3">
                    <div class="col-12 border-0  p-0 col-sm-6 form-group profileForm">
                        <input class="form-control password-field-full" type="password" required name="password" id="password" data-password-hint="dashboard_pwd_hint" placeholder="Enter New Password">
                        <p class="password-static-hint invalid mb-0" id="dashboard_pwd_hint">Your password must be more than 8 characters long. It should contain atleast 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character</p>
                    </div>
                    <div class="col-12  border-0  p-0 col-sm-6 form-group profileForm">
                        <input class="form-control password-field" type="password" required id="password_confirmation" placeholder="Confirm New Password" name="password_confirmation">
                    </div>
                </div>

                <div class="w-size2 p-t-15">
                    <!-- Button -->
                    <button type="submit" class="cta border-0">
                        <span>Submit</span>
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





</section>







<!--===================  end section  ====================-->


@stop
