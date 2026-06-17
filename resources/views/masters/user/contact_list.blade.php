@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-phone"></i> &nbsp;Enquiry List</h1>
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
	                              <th>Date</th>
	                              <th>Name</th>
	                              <th>Email</th>
	                              <th>Contact</th>
                                  <th>Message
                                  <th>Replay</th>
	                              <th>Action</th>
	                            </tr>
	                          </thead>
	                          <tbody>
	                            @php $i = 1 @endphp
                                    @foreach($contact_data as $data)
                                    <tr>
                                        <td>{{$i}}.</td>
										<td>
											<?php
												$date=date_create($data->date);
												echo date_format($date,"M, d Y");
											?>
										</td>
                                        <td>{{$data->name}}</td>
                                        <td>{{$data->email}}</td>
                                        <td>{{$data->phone}}</td>
                                        <td>
                                            <?php
                                                $des = $data->message;
                                                echo $description = Str::words($des, '6');
                                            ?>
                                            <a href="{{route('contactSeeMore',$data->contact_id)}}">see more .... </a>

                                        </td>
                                        <td class="text-center">
                                            <a href="{{route('contactReplay',$data->contact_id)}}"><span class="basic_table_icon" style="font-size: 20px;color: green;"><i class="fa fa-reply" aria-hidden="true"></i></span></a>
                                        </td>
                                        
                                        <td class="text-center">

                                            <a href="{{route('contactDelete',$data->contact_id)}}" onClick="return confirm('Are you sure?');"><span class="basic_table_icon" style="font-size: 20px;color: red;margin-left: 20px;"><i class="fa fa-trash-o" aria-hidden="true"></i></span></a>

                                            
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
    <!-- Essential javascripts for application to work-->



@stop