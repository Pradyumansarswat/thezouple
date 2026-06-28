@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-repeat"></i> &nbsp;Change Password List</h1>
            </div>
            <!-- <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('category_list')}}"><i class="fa fa-eye"></i> Category List</a>
            </ul> -->
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
	                              <th>S.No</th>
	                              <th>Name</th>
	                              <th>Email</th>
	                              <th>Password</th>
	                              <th>Action</th>
	                            </tr>
	                          </thead>
	                          <tbody>
	                            @php $i = 1 @endphp
                                    @foreach($change_password_data as $data)
                                    <tr>
                                        <td>{{$i}}.</td>
                                        <td>{{$data->name}}</td>
                                        <td>{{$data->email}}</td>
                                        <td>{{$data->password}}</td>
                                        <td class="text-center">
                                            <a href="{{route('passwordUpdate',$data->id)}}"><span class="basic_table_icon" style="font-size: 20px;color: green;"><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
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
