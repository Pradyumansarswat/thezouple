@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-cart-plus"></i> Order List</h1>
            </div>
            
            
        
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
                    
                    
                    
                    
                    
                    <div class="row">
                <div class="col-sm-6">
                    <form action="{{route('show_order_status')}}" method="post"> 
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <select class="form-control" required name="order_status">
                                    <option value="">--Select--</option>
                                    <option value="Accepted" @if(isset($order_status)) {{$order_status == "Accepted"? "selected" : " "}} @endif>Accepted</option>
                                    <option value="Rejected" @if(isset($order_status)) {{$order_status == "Rejected"? "selected" : " "}} @endif>Rejected</option>
                                    <option value="Dispatch" @if(isset($order_status)) {{$order_status == "Dispatch"? "selected" : " "}} @endif>Dispatched</option>
                                    <option value="Delivered" @if(isset($order_status)) {{$order_status == "Delivered"? "selected" : " "}} @endif>Delivered</option>
                                </select>
                                
                            </div>
                            
                            <div class="col-sm-6">
                                <input type="submit" value="Show" class=" ml-3 btn btn-primary form-control" name="btn_submit">
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="col-sm-6">
                    <form action="{{route('orderStatusExport')}}" method="post"> 
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="submit" value="Export" class=" ml-3 btn btn-primary form-control" name="btn_submit">
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>
            
            <br>
                    <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table id="example" class="table  table-striped table-bordered" cellspacing="0" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th width="80px">S.No</th>
                                        <!--<th>User Name </th>-->
                                        <th width="80px">Order Number </th>
                                        <!--<th>Order type </th>-->
                                        <!--<th>Product Details </th>-->
                                        <th width="100px">Amount(Rs.)</th>
                                        <!--<th>Payment Status </th>-->
                                        
                                        <th width="100px">Order Date <br> Payment Status</th>
                                        <th class="text-center" width="350px">Order Status </th>
                                        <th colspan="1" width="100px">
                                            <center>Action</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1 @endphp
                                    @foreach($order_data as $row)
                                    <tr>
                                        <td>{{$i}}.</td>
                                         
                                           <?php 
                                            $status_name=$row->status;
                                            if($status_name == 0)
                                            {
                                              $status="text-success font-weight-bold";
                                            }
                                            else
                                            {
                                              $status="text-dark ";
                                            }
                                            ?>
                                        <!--<td class = "<?php echo $status; ?>"> 
                                            {{$row->name}}
                                        </td>-->
                                        <td class="<?php echo $status; ?>">
                                          
                                            <a href="{{route('orderShow',$row->order_number)}}">{{$row->order_number}}</a>
                                        
                                        </td>
                                       <!-- <td>{{$row->order_type}}</td>-->
                                       <!-- <td>
                                            @if($row->order_type !="DESIGN-SHIRT")
                                             <table class="table table-bordered">
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Product Details</th>
                                                    <th>Product Qty</th>

                                                </tr>
                                                <?php
                                                $product_details = json_decode($row->product_details);
                                                ?>
                                                @foreach($product_details as $key => $pros)
                                                <?php 
                                                $pro_dets = explode('-',$pros);
                                                $sub_total = $pro_dets[1] * $pro_dets[2];
                                                ?>
                                                <tr>
                                                    <td>{{$proTitle[$key]}}</td>
                                                    <td class="text-center">{{$pro_dets[0]}}</td>
                                                    <td class="text-center">{{$pro_dets[1]}}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            @else
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Elements Type</th>
                                                    <th>Elements Value</th>
                                                   

                                                </tr>
                                                <?php

                                            $product_details = json_decode($row->product_details);

                                            ?>
                                                @foreach($product_details as $key => $dt)
                                                <tr>
                                                    <td>
                                                        {{$key}}
                                                    </td>
                                                    <td>
                                                        @if($key == "febric" || $key == "FEBRIC" || $key == "Febric")
                                                        {{$febricName[$dt]}}
                                                        @else
                                                        {{$elementValueName[$dt]}}
                                                        @endif
                                                    </td>
                                                </tr>
                                                @break
                                                @endforeach
                                            </table>
                                            @endif
                                        </td> -->
                                        <td>
                                            <b>{{$row->total_amount}} /-</b>
                                        </td>
                                        <!--<td class="text-center" ><b>{{$row->payment_status}}</b>-->
                                            <!--<form class="mt-4" action="{{route('paymet_status_update')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{$row->order_id}}">
                                                <select class="form-control" required name="payment_status">
                                                    <option value="">--Select--</option>
                                                    <option value="PENDING">Pending</option>
                                                    <option value="TXN_SUCCESS">Success</option>
                                                </select>
                                                <input type="submit" value="Update" class="form-control mt-2 btn-info">
                                            </form> -->
                                        </td>
                                        
                                        <td>
                                            {{date('d/M/Y - h:i A ', strtotime($row->order_date))}} <br> {{$row->payment_status}}
                                           
                                        
                                        </td>
                                        
                                        <td class="text-center"><b>Order Status - </b>{{$row->order_status}}
                                            <br>
                                            @if($row->order_status != "Accepted" && $row->order_status != "Rejected" && $row->order_status != "Delivered" && $row->order_status != "Pending")
                                            <b>Tracking Number - </b>{{$row->tracking_number}}<br>
                                            
                                            <b>Tracking URL - </b> {{$row->tracking_url}}<br>
                                            @endif
                                            <b>Update Date - </b>{{$row->order_update_date}}
                                            
                                            <form class="mt-4" action="{{route('order_status_update')}}" method="post">
                                                @csrf 
                                                <input type="hidden" name="order_id" value="{{$row->order_id}}">
                                                
                                                <input type="hidden" name="status" value="1">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <select class="form-control" required name="order_status">
                                                            <option value="">--Select--</option>
                                                            <option value="Accepted">Accepted</option>
                                                            <option value="Rejected">Rejected</option>
                                                            <option value="Dispatch">Dispatched</option>
                                                            <option value="Delivered">Delivered</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="text" name="tracking_number" placeholder="Tracking Number" class="form-control">
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <input type="date" name="order_update_date"  class="form-control mt-3" required> 
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="text" name="tracking_url" placeholder="Tracking Url" class="form-control mt-3">
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="submit" value="Update" class="form-control mt-2 btn-info">
                                                    </div>
                                                </div>
                                                
                                                
                                            </form> 
                                        </td>
                                        
                                        <td class="text-center">
                                           <a href="{{route('orderDelete',$row->order_id)}}" onClick="return confirm('Are you sure?');"><span class="basic_table_icon" style="font-size: 20px;color: red;margin-left: 20px;"><i class="fa fa-trash-o" aria-hidden="true"></i></span></a> 
                                           <a href="{{route('orderOldMessage',$row->order_id)}}" class="text-white"><button class="btn btn-primary form-control"> older msg</a>
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