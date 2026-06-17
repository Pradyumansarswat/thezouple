@extends('front.layout.default_layout')
@section('content')
<div class="container"> 
    <div class="row justify-content-center py-5 mt-1">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white" style="background-color:#e65540">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body text-dark">
                    <h6 class="text-center text-dark">
                    {{$user_data['name']}}, Before proceeding, please check your email for a verification link.<br>
                    If you do not receive your email within five minutes check your spam folder or <br> <a href="{{ url('resend_verify',$user_data['_token']) }}">click here to request another</a>.
                    </h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection