@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-shirtsinbulk"></i> Edit Febric</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('shirtCategory')}}"><i class="fa fa-eye"></i> Febric List</a>
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
                    @foreach($shirt_categorys_datas as $data)
                    <form action="{{route('shirtCategoryEditSave')}}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="febric_id" value="{{$data->febric_id}}">
                      @csrf
                        <div class="row col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" autofocus value="{{$data->name}}">
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Image <span class="text-danger">(Image Dimensions - 565*407 Pixel)</span></label>
                                    <input class="form-control" type="file" name="image" accept="image/x-png,image/gif,image/jpeg">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">  INR <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="rupee_dark_price" value="{{$data->rupee_dark_price}}">
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Discount INR <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="rupee_price" value="{{$data->rupee_price}}">
                                </div>
                            </div>
                            
                            

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">  Dollar <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="dollar_dark_price" value="{{$data->dollar_dark_price}}">
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Discount Dollar <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="dollar_price" value="{{$data->dollar_price}}">
                                </div>
                            </div>
                            
                            

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Euro <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="euro_dark_price" value="{{$data->euro_dark_price}}">
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Discount Euro <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="euro_price" value="{{$data->euro_price}}">
                                </div>
                            </div>


                            <div class="col-sm-8">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>&nbsp;
                            </div>
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
    <!-- Essential javascripts for application to work-->



@stop
