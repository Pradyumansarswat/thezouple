@extends('masters.layout.default_layout')
@section('content')
<script type="text/javascript" src="{{URL::asset('public/js/213jquery.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $("form").submit(function(e) {
            var messageLength = CKEDITOR.instances['summary-ckeditor'].getData().replace(/<[^>]*>/gi, '').length;
            if (!messageLength) {
                alert('Please Fill Page Description');
                e.preventDefault();
            }
        });
    })

</script>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-image-o"></i>  
                    Edit CMS Page
                </h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
               <a class="btn btn-primary icon-btn" href="{{route('cms_page')}}"><i class="fa fa-eye"></i>CMS Page List</a>
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
                    <form action="{{route('cms_update_save')}}" method="post" enctype="multipart/form-data">
                      @csrf
                      @foreach($cms_data as $data)
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="hidden" name="cms_id" value="{{$data->cms_id}}">
                                    <label class="control-label"> Page Title <span class="text-danger"> * </span></label>
                                    <input class="form-control" type="text" name="title" autofocus required value="{{$data->title}}">
                                </div>
                            </div>
                            <!--<div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Page Slug <span class="text-danger"></span></label>
                                    <input class="form-control" type="text" name="slug" required value="{{$data->slug}}">
                                </div>
                            </div>-->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Meta Title <span class="text-danger"> *</span></label>
                                    <input class="form-control" type="text" name="meta_title" required value="{{$data->meta_title}}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Meta Keyword <span class="text-danger"> * </span></label>
                                    <input class="form-control" type="text" name="meta_keywords" required value="{{$data->meta_keywords}}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Meta Description <span class="text-danger"> * </span></label>
                                    <input class="form-control" type="text" name="meta_description" required value="{{$data->meta_description}}">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Page Description <span class="text-danger"> * </span></label>
                                    <textarea class="form-control" name="description" id="summary-ckeditor" placeholder="Page Description" required>{{$data->description}}</textarea>
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
