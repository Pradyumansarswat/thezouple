@extends('masters.layout.default_layout')
@section('content')

<script type="text/javascript" src="{{URL::asset('public/js/213jquery.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $("form").submit(function(e) {
            var messageLength = CKEDITOR.instances['summary-ckeditor'].getData().replace(/<[^>]*>/gi, '').length;
            if (!messageLength) {
                alert('Please Fill Coupen Description');
                e.preventDefault();
            }
        });
    })

</script>
<main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-gift"></i> Add Coupon</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('offerlocation')}}"><i class="fa fa-eye"></i> Coupon List</a>
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
                    <form action="{{route('location_offer_save')}}" method="post" enctype="multipart/form-data">
                      @csrf
                        <div class="row col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Coupen Code <span class="text-danger"> <b> * </b> </span></label>
                                    <input class="form-control" type="text" name="coupen_code" id="coupen_code" required placeholder="Coupen Code">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Coupen Location <span class="text-danger"> <b> * </b> </span></label>
                                    
                                    <textarea class="form-control" name="coupen_location" required></textarea>
                                    <!--<select class="form-control" id="demoSelect"  multiple="" name="coupen_location" required>
                                    	@foreach($pin_list_data as $pin)
                                        <option value="{{$pin->pincode}}">{{$pin->pincode}}</option>
                                        @endforeach
                                  </select>-->
                                </div>
                            </div>  
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Coupen Discount (<span class="text-danger"> <b> * </b> </span> %)</label>
                                    <input class="form-control" type="number" name="coupen_discount" id="coupen_discount" required placeholder="Coupen Discount">
                                </div>
                            </div>

                            

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Code Vaild (<span class="text-danger"> <b> * </b> </span> )</label>
                                    <input class="form-control" type="number" name="code_valide" id="code_valide" required placeholder="Code Vaild">
                                </div>
                            </div>
                             
        
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Coupen Description <span class="text-danger"> <b> * </b> </span></label>
                                    <textarea class="form-control" name="coupen_discription" id="summary-ckeditor" placeholder="Page Description" required></textarea>
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
    <!-- Essential javascripts for application to work-->



@stop
