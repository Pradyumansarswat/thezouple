@extends('masters.layout.default_layout')
@section('content')

<script>
    function maxLengthCheck(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }

    function isNumeric(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }

</script>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-map-marker"></i>  Edit Pincode</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('pincode')}}"><i class="fa fa-eye"></i> Pincode List</a>
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
                    <form action="{{route('pincode_update_save')}}" method="post">
                      @csrf
                        <div class="row col-sm-6">
                            
                            @foreach($pin_data as $pin)
                           
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Pincode</label>
                                    <input type="number" name="pincode" value="{{$pin->pincode}}" id="pincode" class="form-control" required oninput="maxLengthCheck(this)" type="number" maxlength="6" min="000000" max="999999">
                                    <input type="hidden" name="pincode_id" value="{{$pin->pincode_id}}">
                                </div>
                            </div>
                            @endforeach
                            <div class="col-sm-12">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!-- Essential javascripts for application to work-->
    

@stop
