@extends('masters.layout.default_layout')
@section('content')
<script type="text/javascript" src="{{URL::asset('public/js/213jquery.min.js')}}"></script>

<script src="{{URL::asset('public/js/jquery.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $("form").submit(function(e) {
            var messageLength = CKEDITOR.instances['summary-ckeditor'].getData().replace(/<[^>]*>/gi, '').length;
            if (!messageLength) {
                alert('Please Fill Message');
                e.preventDefault();
            }
        });
    })
</script>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-info-circle"></i> Add About </h1>
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
            	<form action="{{route('aboutSave')}}" method="post" enctype="multipart/form-data">
                      @csrf
                        <div class="row col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Title <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="title" autofocus required placeholder="Title">
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">  Image <span class="text-danger">(Image Dimensions - 565*407 Pixel)</span></label>
                                    <input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required>
                                </div>
                            </div>


                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Description <span class="text-danger">*</span></label>
                                    <textarea id="summary-ckeditor" name="description" class="form-control" required></textarea>
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
@stop
