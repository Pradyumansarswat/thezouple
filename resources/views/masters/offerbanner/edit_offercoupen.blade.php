@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-newspaper-o"></i> Update Offer Coupen</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('offercoupen')}}"><i class="fa fa-eye"></i> Offer Coupen List</a>
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
                    <form action="{{route('offercoupen_update_save')}}" method="post" enctype="multipart/form-data">
                      @csrf
                      @foreach($offercoupenData as $offercoupen)
                        <div class="row col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Coupen Code </label>
                                    <input class="form-control" type="text" name="coupen_code" id="coupen_code" value="{{$offercoupen->coupen_code}}" required>

                                    <input type="hidden" name="coupen_id" value="{{$offercoupen->coupen_id}}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Coupen Discount </label>
                                    <input class="form-control" type="number" name="coupen_discount" value="{{$offercoupen->coupen_discount}}" id="coupen_discount" required>
                                </div>
                            </div>
                            <!-- <div class="col-sm-5">
                                <div class="form-group">
                                    <label class="control-label"> Coupen Location </label>
                                    <select class="form-control"  multiple="" name="coupen_location[]">
                                    
                                    	@foreach($pin_list_data as $pin)
                                        <option value="{{$pin->pincode_id}}">{{$pin->pincode}}</option>
                                        @endforeach

                                       
                                  
                                  </select>
                                </div>
                            </div> --> 
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Coupen Product </label>
                                    <select class="form-control" multiple="" name="coupen_product[]">
                                    
                                    	@foreach($product_list_data as $product)
                                        <option value="{{$product->product_id}}">{{$product->product_title}}</option>
                                        
                                        @endforeach
                                       
                                   
                                  </select>
                                </div>
                            </div>
                            <div class="col-sm-6 py-4">
                                <div class="form-group">
                                    <label class="control-label"> With Product Discount</label>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="animated-radio-button">
                                            <label>
                                                <input type="radio" name="with_product_discount"  value="YES"><span class="label-text">Yes</span>
                                              </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="animated-radio-button">
                                            <label>
                                                <input type="radio" name="with_product_discount" checked value="NO"><span class="label-text">No</span>
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Coupen Description</label>
                                    <textarea class="form-control" name="coupen_discription" id="summary-ckeditor" placeholder="Page Description" required>{{$offercoupen->coupen_discription}}</textarea>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
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
