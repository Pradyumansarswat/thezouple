@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-video-camera"></i>
                Edit Sub Video
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('subVideo')}}"><i class="fa fa-eye"></i> Sub Video List</a>
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
                <form action="{{route('subvideoUpdateSave')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @foreach($videos_data as $data)
                    <div class="row col-sm-12">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <video width="320" height="240" controls>
                                    <source src="{{URL::asset('public/upload/video/'.$data->video)}}" type="video/mp4">
                                </video>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Main Video </label>
                                <input class="form-control" type="file" name="video" >
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Title <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="title" value="{{$data->title}}">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label"> Description <span class="text-danger">*</span></label>
                                <textarea id="summary-ckeditor" name="description" class="form-control"><?php echo $data->description; ?></textarea>
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
