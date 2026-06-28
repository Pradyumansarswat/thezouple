@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-info-circle"></i> Edit About </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('about')}}"><i class="fa fa-eye"></i> About List</a>
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
                @foreach($abouts_data as $data)
            	<form action="{{route('aboutEditSave')}}" method="post" enctype="multipart/form-data">
                      @csrf
                      <input type="hidden" name="about_id" value="{{$data->about_id}}">
                        <div class="row col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Title <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="title" autofocus value="{{$data->title}}">
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">  Image <span class="text-danger">(Image Dimensions - 565*407 Pixel)</span></label>
                                    @if(z_media_exists($data->image, 'about'))
                                        <div class="mb-2">
                                            <img src="{{ z_media_url($data->image, 'about', 'img/dark-logo.png') }}" width="130" alt="{{ $data->title }}" onerror="this.onerror=null;this.src='{{ asset('img/dark-logo.png') }}';">
                                        </div>
                                    @else
                                        <p class="text-danger mb-2">No saved image found. Please upload an image before saving.</p>
                                    @endif
                                    <input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" {{ z_media_exists($data->image, 'about') ? '' : 'required' }}>
                                </div>
                            </div>


                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Description <span class="text-danger">*</span></label>
                                    <textarea id="summary-ckeditor" name="description" class="form-control"><?php echo $data->description; ?></textarea>
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
@stop
