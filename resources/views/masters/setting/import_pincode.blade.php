@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-map-marker"></i>  Import Pincode</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('pincode')}}"><i class="fa fa-eye"></i> Pincode List</a>
            </ul>
        </div>
        <div class="row bg-white py-3">
            <div class="col-md-12">
                @if (isset($errors) && count($errors) > 0)
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
                    <form action="{{route('import_pincode_store')}}" method="post" enctype="multipart/form-data">
                      @csrf
                        <div class="row col-sm-6">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Pincode - Choose CSV / Excel File</label>
                                    <input type="file" name="file" class="form-control" accept=".csv,.txt,.xlsx,.xls,text/csv" required>
                                    <small class="form-text text-muted">Columns: pincode, city, state. <a href="data:text/csv;charset=utf-8,pincode,city,state%0A331022,Churu,Rajasthan" download="sample-pincode.csv">Download sample CSV</a></small>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>&nbsp;
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@stop
