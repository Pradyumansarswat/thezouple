@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-gift"></i> Add Customer Coupon</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('customerCoupon')}}"><i class="fa fa-eye"></i> Customer Coupon List</a>
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
                <form action="{{route('customerCouponSave')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label"> Customer Name <span class="text-danger"> <b> * </b> </span></label>
                            <div class="form-group py-3 px-3" style="border:2px solid #CED4DA; height:530px; overflow:auto;border-radius:5px;">



                                <table class="table  table-striped table-bordered">
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Total Amount </th>
                                        <th>Last Date(Purchase)</th>
                                    </tr>
                                    @foreach($user_data as $user)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" value="{{$user->id}}" name="id[]" multiple>{{ $cust_name[$user->id] ?? ($user->name ?: ('Customer #' . $user->id)) }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $amount[$user->id] ?? 0 }}
                                        </td>
                                        <td>
                                            {{ $last_date[$user->id] ?? 'No purchase' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>


                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Coupon Code <span class="text-danger"> <b> * </b> </span></label>
                                <input type="text" name="coupon_code" id="coupon_code" class="form-control" required placeholder="Coupon Code">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> INR Discount in (%) <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="rupee_discount" class="form-control" required placeholder="Rupee Discount">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Dollar Discount in (%) <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="doller_discount" class="form-control" required placeholder="Doller Discount">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Euro Discount in (%) <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="euro_discount" class="form-control" required placeholder="Euro Discount ">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Coupon Vaild (Number of Times) <span class="text-danger"> <b> </b> </span></label>
                                <input type="number" name="coupon_valid" class="form-control" required placeholder="Coupon Vaild (Number of Times)">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Coupon Vaild (Days) <span class="text-danger"> <b> </b> </span></label>
                                <input type="number" name="coupon_valid_days" class="form-control" required placeholder="Coupon Valid (Days)">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Active <span class="text-danger"> <b> * </b> </span></label>
                                <select type="text" class="form-control" required name="is_active">
                                    <option value="">-- Please Select --</option>
                                    <option value="ACTIVE">Active</option>
                                    <option value="INACTIVE">Inactive</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label"> Coupon Description (Self Only)  <span class="text-danger"> <b> * </b> </span></label>
                                <textarea id="summary-ckeditor" name="description" class="form-control" required></textarea>
                            </div>
                        </div>



                        <div class="col-sm-12">
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
