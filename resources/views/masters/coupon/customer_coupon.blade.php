@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-gift"></i> Customer Coupon List</h1>
        </div>
        <script>
            function statusFunction(str) {
              $('#statusType').val(str);
            }
        </script>
        
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('addCustomerCoupon')}}"><i class="fa fa-plus"></i> Add Customer Coupon</a>
            
             <form action="{{route('customerCouponStatusAllUpdate')}}" method="POST">
                @csrf
                <input type="hidden" name="is_active" id="statusType">
                &nbsp;&nbsp;
                <input type="submit" onclick="statusFunction('ACTIVE')" value="ACTIVE" class="btn btn-primary icon-btn">
                
                &nbsp;&nbsp;
                <input type="submit" onclick="statusFunction('INACTIVE')" value="INACTIVE" class="btn btn-primary icon-btn">
            </form>
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
                <div class="table-rep-plugin">
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="example" class="table  table-striped table-bordered" cellspacing="0" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th> Name</th>
                                    <th> Code</th>
                                    <th> Discount(%) </th>
                                    <th> Vaild (Number of Times) </th>
                                    <th> Vaild (Days) </th>
                                    <th> Status </th>
                                    <th> Description </th>


                                    <th colspan="1">
                                        <center>Action</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1 @endphp
                                @foreach($customer_coupon_data as $data)
                                <tr>
                                    <td>{{$i}}.</td>
                                    <td>
                                        <?php
                                         $user = json_decode($data->id);   
                                        foreach($user as $users)
                                        {
                                            echo $userTitle[$users].",<br>";
                                        }
                                        ?>
                                    </td>
                                    <td>{{$data->coupon_code}}</td>
                                    <td>
                                        <i class="fa fa-inr" aria-hidden="true"></i> : {{$data->rupee_discount}}<br>
                                        <i class="fa fa-usd" aria-hidden="true"></i> : {{$data->doller_discount}}<br>
                                        <i class="fa fa-eur" aria-hidden="true"></i> : {{$data->euro_discount}}
                                    </td>
                                    <td>{{$data->coupon_valid}}</td>
                                    <td>{{$data->coupon_valid_days}}</td>
                                    <td class="text-center py-2">
                                        <b>{{$data->is_active}}</b> <br>
                                        <form class="mt-4" action="{{route('customerCouponStatusUpdate')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="customer_coupon_id" value="{{$data->customer_coupon_id}}">
                                            <select class="form-control" required name="is_active">
                                                <option value="">--Select--</option>
                                                <option value="ACTIVE">Active</option>
                                                <option value="INACTIVE">Inactive</option>
                                            </select>
                                            <input type="submit" value="Update" class="form-control mt-2 btn-info">
                                        </form>

                                    </td>
                                    <td><?php echo $data->description; ?></td>




                                    <td class="text-center">
                                        <a href="{{route('customerCouponUpdate',$data->customer_coupon_id)}}"><span class="basic_table_icon" style="font-size: 20px;color: green;"><i class="fa fa-pencil" aria-hidden="true"></i></span></a>


                                        <a href="{{route('customerCouponDelete',$data->customer_coupon_id)}}" onClick="return confirm('Are you sure?');"><span class="basic_table_icon" style="font-size: 20px;color: red;margin-left: 20px;"><i class="fa fa-trash-o" aria-hidden="true"></i></span></a>
                                    </td>
                                </tr>
                                @php $i++ @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@stop
