@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-cart-plus"></i> Order Report</h1>
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
                    <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table id="example" class="table  table-striped table-bordered" cellspacing="0" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>User Name </th>
                                        <th>Order Number </th>
                                        <th>Order Date </th>
                                        <th>Order Status </th>
                                        <th>User Report </th>
                                        <th>User Description </th>
                                        <th>Amount Refund Remark</th>
                                        <th colspan="1">
                                            <center>Action</center>
                                        </th>
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1 @endphp
                                    @foreach($order_data as $row)
                                    <tr>
                                        <td>{{$i}}.</td>
                                        <td>{{$row->name}}</td>
                                        <td>
                                          
                                            <a href="{{route('orderShow',$row->order_number)}}">{{$row->order_number}}</a>
                                        
                                        </td>
                                        <td>
                                            {{date('d/M/Y - h:i A ', strtotime($row->order_date))}}
                                           
                                        
                                        </td>
                                        <td class="text-center"><b>Order Status - </b>{{$row->order_status}}
                                            
                                        </td>
                                        <td>
                                            <b>{{$row->user_report}} </b> 
                                        </td>
                                        <td>
                                            <b><?php echo $row->user_description;?> </b>
                                        </td>

                                        <td class="text-center">
                                            
                                            <form method="post" action="{{route('remarkSave')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        {{$row->remark}}<br>
                                                        <input type="hidden" name="order_number" value="{{$row->order_number}}">
                                                        <textarea type name="remark" placeholder="For Zouple"></textarea>
                                                    </div>
                                                </div>
                                                <hr>
                                                
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        {{$row->mail_subject}}<br>
                                                        <textarea type name="mail_subject" placeholder="Mail Sub (Customer)"></textarea>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        {{$row->refund_status}}<br>
                                                        <textarea type name="refund_status" placeholder="Message (Customer)"></textarea>
                                                    </div>
                                                </div>
                                                <hr>
                                                
                                                
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <input type="submit" class="mt-2 form-control text-white form-control mt-2 btn-info" value="Update">
                                                    </div>
                                                </div>
                                                
                                            </form>
                                        </td>

                                        <td class="text-center">
                                           <a href="{{route('ordersDeletes',$row->order_id)}}" onClick="return confirm('Are you sure?');"><span class="basic_table_icon" style="font-size: 20px;color: red;margin-left: 20px;"><i class="fa fa-trash-o" aria-hidden="true"></i></span></a> 
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