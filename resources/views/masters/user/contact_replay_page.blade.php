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
                <h1><i class="fa fa-reply"></i> Replay Candidate</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('contact_information')}}"><i class="fa fa-eye"></i> Contact  List</a>
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
                	@foreach($contact_datas as $data)
                    <form action="{{route('reloay_save')}}" method="post" enctype="multipart/form-data">
                      @csrf

                      
                        <div class="row col-sm-12">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Email <span class="text-danger"></span></label>
                                    <input class="form-control" type="email" name="email" value="{{$data->email}}" readonly> 
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Description <span class="text-danger">*</span></label>
                                    <textarea id="summary-ckeditor" name="message" class="form-control" required></textarea>
                                </div>
                            </div>

                            <div class="col-sm-8">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Send</button>&nbsp;
                            </div>
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
@stop
