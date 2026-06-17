@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-money"></i> Add Currency</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('currency')}}"><i class="fa fa-eye"></i> Currency List</a>
            </ul>
        </div>
        <div class="row bg-white py-3">
            <div class="col-md-12">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))

                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    </p>
                    @endif
                    @endforeach
                </div>
                <div class="card-box">
                    <form action="{{route('currencySave')}}" method="post">
                      @csrf
                        <div class="row col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Currecny <span class="text-danger">*</span></label>
                                    <select class="form-control" id="currency" name="currency">
                                    <option value=""> -- Select the field -- </option>
                                    <option value="rupee_price"> Rupee (₹)</option>
                                    <option value="dollar_price"> Dollar ($)</option>
                                    <option value="euro_price"> Euro (€)</option>
                                </select>
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Currency Code <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="currency_code" required placeholder="Currency Code">
                                </div>
                            </div>

                            <div class="col-sm-8">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>&nbsp;
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!-- Essential javascripts for application to work-->



@stop
