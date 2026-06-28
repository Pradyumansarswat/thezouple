@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-gift"></i> Edit Product Coupon</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('productCoupon')}}"><i class="fa fa-eye"></i> Product Coupon List</a>
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
                @foreach($product_coupon_datas as $coupon)
                <form action="{{route('productCouponEditSave')}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="product_coupon_id" value="{{$coupon->product_coupon_id}}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table id="example" class="table  table-striped table-bordered" cellspacing="0" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Product Title (Code)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($product_data as $product)
                                                    
                                                    <?php
                                                        
                                                        $productId = json_decode($coupon->product_id, true);
                                                        $productId = is_array($productId) ? $productId : array();
                                                        
                                                        $proId = $product->product_id;
                                                        if(in_array($proId,$productId))
                                                        {
                                                            $check1 = "checked";   
                                                        }
                                                        else
                                                        {
                                                            $check1 = "";   
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input class="form-check-input" type="checkbox" value="{{$product->product_id}}" id="{{$product->product_id}}" name="product_id[]" {{$check1}}  multiple><label for="{{$product->product_id}}">{{$product->product_title}}</label>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!--<label class="control-label"> Product Title (Code) <span class="text-danger"> <b> * </b> </span></label>
                            <div class="form-group py-3 px-3" style="border:2px solid #CED4DA; height:450px; overflow:auto;border-radius:5px;">
                                @foreach($product_data as $product)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" value="{{$product->product_id}}" name="product_id[]" multiple>{{$product->product_title}}
                                    </label>
                                </div>
                                @endforeach
                            </div>-->
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Coupon Code <span class="text-danger"> <b> * </b> </span></label>
                                <input type="text" name="coupon_code" id="coupon_code" class="form-control" value="{{$coupon->coupon_code}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> INR Discount in (%) <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="rupee_discount" class="form-control" required placeholder="Rupee Discount" value="{{$coupon->rupee_discount}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Dollar Discount in (%) <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="doller_discount" class="form-control" required placeholder="Doller Discount" value="{{$coupon->doller_discount}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Euro Discount in (%) <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="euro_discount" class="form-control" required placeholder="Euro Discount " value="{{$coupon->euro_discount}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Coupon Valid (Days) <span class="text-danger"> <b> </b> </span></label>
                                <input type="number" name="coupon_valid" class="form-control" value="{{$coupon->coupon_valid}}">
                            </div>

                            <div class="form-group">
                                <label class="control-label"> Active <span class="text-danger"> <b> * </b> </span></label>
                                <select type="text" class="form-control" required name="is_active">
                                    <option value="">-- Please Select --</option>
                                    <option value="ACTIVE" {{$coupon->is_active == "ACTIVE"? "selected" : " "}}>Active</option>
                                    <option value="INACTIVE" {{$coupon->is_active == "INACTIVE"? "selected" : " "}}>Inactive</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label"> Coupon Description (Self Only) <span class="text-danger"> <b> * </b> </span></label>
                                <textarea id="summary-ckeditor" name="description" class="form-control" required><?php echo $coupon->description;?></textarea>
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
