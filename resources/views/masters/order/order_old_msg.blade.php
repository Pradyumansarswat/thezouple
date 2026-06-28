@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-cart-plus"></i> Order Pld Message List</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('order_information')}}"><i class="fa fa-eye"></i> Order List</a>
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
                   <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table id="example" class="table  table-striped table-bordered" cellspacing="0" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th width="80px">S.No</th>
                                        <th width="80px">Order Status </th>
                                        <th>Tracking Number </th>
                                        <th>Order Date </th>
                                        <th>Tracking Url </th>
                                        <th colspan="1" width="100px">
                                            <center>Action</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1 @endphp
                                    @foreach($orderOldMsgData as $row)
                                    <tr>
                                        <td>{{$i}}.</td>
                                        <td>{{$row->order_status}}</td>
                                        <td>{{$row->tracking_number}}</td>
                                        <td>
                                            <?php
                                                $date = $row->order_update_date;

                                                $date=date_create($date);
                                                echo date_format($date,"d-M-Y");
                                            ?>

                                        </td>
                                        <td>{{$row->tracking_url}}</td>
                                        <td class="text-center">
                                           <a href="{{route('orderOldMsgDelete',$row->order_old_msg_id)}}" onClick="return confirm('Are you sure? This item will move to Recycle Bin.');"><span class="basic_table_icon" style="font-size: 20px;color: red;margin-left: 20px;"><i class="fa fa-trash-o" aria-hidden="true"></i></span></a>
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
