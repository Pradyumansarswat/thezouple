@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div><h1><i class="fa fa-video-camera"></i> Sub Video</h1></div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('subVideoUpdate')}}"><i class="fa fa-pencil"></i> Update Sub Video</a>
        </ul>
    </div>
    <div class="row bg-white py-3">
        <div class="col-md-12">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                @endif
            @endforeach
            @foreach($subvideo_data as $data)
                @if(!empty($data->video))
                    <video width="420" controls>
                        <source src="{{ z_media_url($data->video, 'video') }}" type="video/mp4">
                    </video>
                    <h5 class="mt-3">{{ $data->title }}</h5>
                    <div>{!! $data->description !!}</div>
                    <div class="mt-3">
                        <a href="{{ route('videoDelete', $data->video_id) }}" class="btn btn-danger" onclick="return confirm('Are you sure? This item will move to Recycle Bin.')">Move to Recycle Bin</a>
                    </div>
                @else
                    <div class="alert alert-info">No sub video uploaded. The frontend video section is hidden.</div>
                @endif
            @endforeach
        </div>
    </div>
</main>
@stop
