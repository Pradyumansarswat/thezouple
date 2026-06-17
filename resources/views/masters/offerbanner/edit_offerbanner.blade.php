@extends('masters.layout.default_layout')
@section('content')
<script type="text/javascript" src="{{URL::asset('public/js/213jquery.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $("form").submit(function(e) {
            var messageLength = CKEDITOR.instances['summary-ckeditor'].getData().replace(/<[^>]*>/gi, '').length;
            if (!messageLength) {
                alert('Please Fill Description');
                e.preventDefault();
            }
        });
    })

</script>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-bullhorn"></i> Edit Advertisement Bannner</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('offer')}}"><i class="fa fa-eye"></i> Advertisement Banner List</a>
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

                    <form action="{{route('offerbanner_update_save')}}" method="post" enctype="multipart/form-data">
                      @csrf
                      @foreach($offerbanner_data as $offerbanner)
                        <div class="row col-sm-12">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Image <span class="text-danger">(Image Dimensions - 632*314 Pixel)</span></label>
                                    <input class="form-control" type="file" name="image" accept="image/x-png,image/gif,image/jpeg" required>
                                    <input type="hidden" name="offerbanners_id" value="{{$offerbanner->offerbanners_id}}">
                                </div>
                            </div>
                            <div class="col-sm-12 pb-4">
                                <img src="{{URL::asset('public/upload/offerbanner/'.$offerbanner->image)}}" width="300px">
                            </div>
                           <!-- <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Description</label>
                                    <textarea class="form-control" name="description" id="summary-ckeditor" placeholder="Page Description" required>{{$offerbanner->description}}</textarea>
                                </div>
                            </div>-->
                            <div class="col-sm-8">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>&nbsp;&nbsp;&nbsp;
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
