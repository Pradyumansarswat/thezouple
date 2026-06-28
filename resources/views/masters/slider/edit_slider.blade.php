@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-image"></i>  
                    Edit Slider
                </h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('slider_mange')}}"><i class="fa fa-eye"></i> Slider List</a>
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
                    <form action="{{route('slider_update_save')}}" method="post" enctype="multipart/form-data">
                      @csrf
                        @foreach($slide_data as $data)
                        <input type="hidden" name="slider_id" value="{{$data->slider_id}}">
                        <div class="row col-sm-12">
                            
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Heading<span class="text-danger"> <b> * </b> </span></label>
                                    <input class="form-control" type="text" name="name" value="{{$data->name}}">
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Slug<span class="text-danger"> <b> * </b></span></label>
                                    <input class="form-control" type="text" name="slug" value="{{$data->slug}}">
                                </div>
                            </div> 

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Slider Active <span class="text-danger"> <b> * </b> </span></label>
                                    <select type="text" class="form-control" required name="is_active">
                                        <option value="">-- Please Select --</option>
                                        <option value="ACTIVE" {{$data->is_active == "ACTIVE"? "selected" : " "}}>Active</option>
                                        <option value="INACTIVE" {{$data->is_active == "INACTIVE"? "selected" : " "}}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            

                            <!--<div class="col-sm-6 pb-4">
                                <img src="{{ z_media_url($data->image, 'slider') }}" width="300px">  
                            </div>
                            <div class="col-sm-6">
                                <input type="hidden" name="slider_id" value="{{$data->slider_id}}">
                                <div class="form-group">
                                    <label class="control-label"> Slider Image <span class="text-danger">(Image Dimensions - 1920*500 Pixel *)</span></label>
                                    <input class="form-control" type="file" name="image" accept="image/x-png,image/gif,image/jpeg">
                                </div>
                            </div>-->
                            
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Description<span class="text-danger"> <b> * </b> </span></label>
                                    <textarea class="form-control" id="summary-ckeditor"   name="heading" required placeholder="Description"><?php echo $data->heading;?></textarea>
                                </div>
                            </div>
                            
                            

                            <div class="col-sm-12">
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
