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
                <h1><i class="fa fa-image"></i> Edit  Home Customize Shirt</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('customerShirt')}}"><i class="fa fa-eye"></i> Home Customize Shirt</a>
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

                    @foreach($customer_shirt_datass as $data)
                    <form action="{{route('customerShirtEditSave')}}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="customer_shirt_id" value="{{$data->customer_shirt_id}}">
                      @csrf
                        <div class="row col-sm-12">

                           <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Heading <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="heading" value="{{$data->heading}}">
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Image <span class="text-danger">(Image Dimensions - 565*407 Pixel)</span></label>
                                    <input class="form-control" type="file" name="image" accept="image/x-png,image/gif,image/jpeg">
                                </div>
                                <img src="{{ z_media_url($data->image, 'customershirt') }}" width="130px">
                            </div>
                            
                            
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">  Description <span class="text-danger"> <b> * </b> </span></label>
                                    <textarea id="summary-ckeditor" name="description" class="form-control" required><?php echo $data->description;?></textarea>
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
