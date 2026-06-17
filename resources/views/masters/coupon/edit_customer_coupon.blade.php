@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-gift"></i> Edit Customer Coupon</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('customerCoupon')}}"><i class="fa fa-eye"></i> Customer Coupon List</a>
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

                @foreach($customer_coupon_datas as $customer_data)

                <form action="{{route('customerCouponEditSave')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$customer_data->customer_coupon_id}}" name="customer_coupon_id">
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label"> Customer Name <span class="text-danger"> <b> * </b> </span></label>
                            <div class="form-group py-3 px-3" style="border:2px solid #CED4DA; height:530px; overflow:auto;border-radius:5px;">


                                <?php 
                                $user_information = json_decode($customer_data->id);
                                ?>


                                <table class="table  table-striped table-bordered">
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Total Amount</th>
                                        <th>Last Date(Purchase)</th>
                                    </tr>
                                    @foreach($user_data as $user)
                                    <?php
                                        $user_id = $user->id;
                                        if(in_array($user_id,$user_information))
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
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" value="{{$user->id}}" name="id[]" multiple {{$check1}}>{{$cust_name[$user->id]}}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            {{$amount[$user->id]}}
                                        </td>
                                        <td>
                                            {{$last_date[$user->id]}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>


                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Coupon Code <span class="text-danger"> <b> * </b> </span></label>
                                <input type="text" name="coupon_code" id="coupon_code" class="form-control" value="{{$customer_data->coupon_code}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> INR Discount in (%) <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="rupee_discount" class="form-control" required placeholder="Rupee Discount" value="{{$customer_data->rupee_discount}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Dollar Discount in (%)  <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="doller_discount" class="form-control" required placeholder="Doller Discount" value="{{$customer_data->doller_discount}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Euro Discount in (%) <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" step="0.01" name="euro_discount" class="form-control" required placeholder="Euro Discount " value="{{$customer_data->euro_discount}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Coupon Vaild (Number of Times) <span class="text-danger"> <b> </b> </span></label>
                                <input type="number" name="coupon_valid" class="form-control" value="{{$customer_data->coupon_valid}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Coupon Vaild (Days) <span class="text-danger"> <b> </b> </span></label>
                                <input type="number" name="coupon_valid_days" class="form-control" required value="{{$customer_data->coupon_valid_days}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label"> Active <span class="text-danger"> <b> * </b> </span></label>
                                <select type="text" class="form-control" required name="is_active">
                                    <option value="">-- Please Select --</option>
                                    <option value="ACTIVE" {{$customer_data->is_active == "ACTIVE"? "selected" : " "}}>Active</option>
                                    <option value="INACTIVE" {{$customer_data->is_active == "INACTIVE"? "selected" : " "}}>Inactive</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label"> Coupon Description (Self Only)  <span class="text-danger"> <b> * </b> </span></label>
                                <textarea id="summary-ckeditor" name="description" class="form-control" required><?php echo $customer_data->description; ?></textarea>
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
