@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-gift"></i> Edit Price Coupon</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('pricesCoupon')}}"><i class="fa fa-eye"></i> Price Coupon List</a>
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
                @foreach($product_coupon_datas as $data)
                <form action="{{route('pricesCouponEditSave')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="price_coupon_id" value="{{$data->price_coupon_id}}">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> INR Min Amount <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" name="rupee_min" id="price_amount" class="form-control" required placeholder="Rupee Min Amount" value="{{$data->rupee_min}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> INR Max Amount <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" name="rupee_max" id="price_amount" class="form-control" required placeholder=" Rupee Max Amount" value="{{$data->rupee_max}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Dollar Min Amount <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" name="doller_min" id="price_amount" class="form-control" required placeholder="Doller Min Amount" value="{{$data->doller_min}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Dollar Max Amount <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" name="doller_max" id="price_amount" class="form-control" required placeholder="oller Max Amount" value="{{$data->doller_max}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Euro Min Amount <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" name="euro_min" id="price_amount" class="form-control" required placeholder="Euro Min Amount" value="{{$data->euro_min}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Euro Max Amount <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" name="euro_max" id="price_amount" class="form-control" required placeholder="Euro Max Amount" value="{{$data->euro_max}}">
                            </div>
                        </div>


                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Coupon Code <span class="text-danger"> <b> * </b> </span></label>
                                <input type="text" name="coupen_code" class="form-control" required placeholder="Coupen Code" value="{{$data->coupen_code}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> INR Discount(%) <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="rupee_discount" class="form-control" required placeholder="Rupee Discount" value="{{$data->rupee_discount}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Dollar Discount(%) <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="doller_discount" class="form-control" required placeholder=" Doller Discount" value="{{$data->doller_discount}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Euro Discount(%) <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="euro_discount" class="form-control" required placeholder="Euro Discount" value="{{$data->euro_discount}}">
                            </div>
                        </div>

                        <!--<div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Coupon Valid <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" name="coupen_valid" class="form-control" required placeholder="Coupen Valid" value="{{$data->coupen_valid}}">
                            </div>
                        </div>-->

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Active <span class="text-danger"> <b> * </b> </span></label>
                                <select type="text" class="form-control" required name="is_active">
                                    <option value="">-- Please Select --</option>
                                    <option value="ACTIVE" {{$data->is_active == "ACTIVE"? "selected" : " "}}>Active</option>
                                    <option value="INACTIVE" {{$data->is_active == "INACTIVE"? "selected" : " "}}>Inactive</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label"> Coupon Description <span class="text-danger"> <b> * </b> </span></label>
                                <textarea id="summary-ckeditor" name="description" class="form-control" required><?php echo $data->description; ?></textarea>
                            </div>
                        </div>



                        <div class="col-sm-12">
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
