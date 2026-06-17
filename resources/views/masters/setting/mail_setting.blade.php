@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-envelope"></i>  
                    Mail Setting Update
                </h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
               <a class="btn btn-primary icon-btn" href="{{route('mail_page')}}"><i class="fa fa-eye"></i> Mail List</a>
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
                    <form action="{{route('mail_setting_update')}}" method="post" enctype="multipart/form-data">
                      @csrf
                        @foreach($mail_data as $data)
                        <div class="row col-sm-6">
                            <div class="col-sm-12">
                                <input type="hidden" name="id" value="{{$data->id}}">
                                <div class="form-group">
                                    <label class="control-label"> Mail Driver <span class="text-danger"></span></label>
                                    <input class="form-control" type="text" name="driver" autofocus required value="{{$data->driver}}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Mail Host <span class="text-danger"></span></label>
                                    <input class="form-control" type="text" name="host" required value="{{$data->host}}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Mail Port <span class="text-danger"></span></label>
                                    <input class="form-control" type="number" name="port" required value="{{$data->port}}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Mail From Address <span class="text-danger"></span></label>
                                    <input class="form-control" type="text" name="from_address" required value="{{$data->from_address}}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Mail Encryption <span class="text-danger"></span></label>
                                    <input class="form-control" type="text" name="encryption" required value="{{$data->encryption}}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Username <span class="text-danger"></span></label>
                                    <input class="form-control" type="text" name="username" required value="{{$data->username}}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Password <span class="text-danger"></span></label>
                                    <input class="form-control password-field" type="password" name="password" required value="{{$data->password}}">
                                </div>
                            </div>
    
                            
                            <div class="col-sm-6">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!-- Essential javascripts for application to work-->



@stop
