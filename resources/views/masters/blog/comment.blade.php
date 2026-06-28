@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-comments-o"></i> Comment List</h1>
            </div>
            
        
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
                                        <th> Date </th>
                                        <th>Name</th>
                                        <th>Comment</th>
                                        


                                        <th colspan="1">
                                            <center>Active</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1 @endphp
                                    @foreach($comment_datas as $data)
                                    <tr>
                                        <td>{{$i}}.</td>
                                        <td><?php

                                            $date = $data->date; 

                                            $date=date_create($date);
                                            echo date_format($date,"d/m/Y");

                                            ?>  
                                        </td>
                                        <td>{{$data->name}}</td>
                                        <td>{{$data->message}}</td>
                                        <td class="text-center">
                                        <b>{{$data->is_active}}</b> <br>
                                        <form class="mt-4" action="{{route('comment_status_update')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="comment_id" value="{{$data->comment_id}}">
                                            <select class="form-control" required name="is_active">
                                                <option value="">--Select--</option>
                                                <option value="ACTIVE">Active</option>
                                                <option value="INACTIVE">Inactive</option>
                                            </select>
                                            <input type="submit" value="Update" class="form-control mt-2 btn-info">
                                        </form>

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
