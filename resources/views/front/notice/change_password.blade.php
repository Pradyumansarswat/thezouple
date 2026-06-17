@extends('front.layout.default_layout')
@section('content')
@include('front.layout.banner')
<!--======================   breadcumbs =======================-->
    <div class="container-fluid  mb-5 " style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
        <div class="row px-5 maxWidhtContainer">
            <div class=" col-12 align-self-center col-sm-5 col-md-6  h5 m-0  text-white" style="letter-spacing: 4px; ">Change Password</div>
            <div class="maxWidhtContainer  h6 m-0 breadThum align-self-center   col-12 d-flex  col-sm-7 col-md-6  ">
                <nav aria-label="breadcrumb m-0 ">
                    <ol class="breadcrumb m-0 bg-transparent">
                        <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                        <li class="breadcrumb-item"><a href="" class=" h6 m-0 text-white">Change Password</a></li>


                    </ol>
                </nav>

            </div>
        </div>


    </div>
<div class="container"> 
    <div class="row justify-content-center py-5 mt-5">
        <div class="col-sm-4">
            <form action="{{url('update_pass')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <form>
                        <div class="form-group">
                            <label for='newPss'>New Password</label>
                            <input class="form-control w-100 password-field-full" required id='newPss' data-password-hint="reset_pwd_hint" type="password" name="password">
                            <input type="hidden" name="old_token" value="{{$old_token}}">
                            <p class="password-static-hint invalid mb-0" id="reset_pwd_hint">Your password must be more than 8 characters long. It should contain atleast 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character</p>
                        </div>
                        <div class="form-group">
                            <label for='conPss'>Confirm Password</label>
                            <input class="form-control w-100 password-field" required id='conPss' type="password" name="password_confirmation">
                        </div>
                        
                        <div class="form-group">
                           
                            <div class="w-size25">
                                    <button type="submit" class="btn btn-dark text-white flex-c-m size2 bg1 bo-rad-23 hov1 m-text3 trans-0-4">
                                        Update Password
                                    </button>
                                    </div>

                        </div>
                    </form>
                </div>
            </div>
           
            
        </form>

        </div>
    </div>
</div>
@endsection