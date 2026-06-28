@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-credit-card"></i> Payment Gateway</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('site_information')}}"><i class="fa fa-cog"></i> Site Settings</a>
        </ul>
    </div>

    <div class="row">
        @foreach($gatewayStatus as $gateway)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="mb-0">{{ $gateway['name'] }}</h5>
                            <span class="badge {{ $gateway['status'] == 'Configured' || $gateway['status'] == 'Enabled' ? 'badge-success' : 'badge-warning' }}">
                                {{ $gateway['status'] }}
                            </span>
                        </div>
                        <p class="text-muted mb-2">Mode: {{ $gateway['mode'] }}</p>
                        <p>{{ $gateway['message'] }}</p>
                        @if(!empty($gateway['required']))
                            <hr>
                            <small class="text-muted">Required .env variables</small>
                            <ul class="mt-2 pl-3">
                                @foreach($gateway['required'] as $key)
                                    <li><code>{{ $key }}</code></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="alert alert-info">
        For security, gateway secret keys are not edited from the browser. Update them in <code>.env</code>, then rebuild Laravel config cache before going live.
    </div>
</main>
@stop
