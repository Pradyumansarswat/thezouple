@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text"></i> CMS Page</h1>
            </div>
            <!-- <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('add_cms')}}"><i class="fa fa-plus"></i> Add Page</a>
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
                                        <th>Title</th>
                                        <th> Slug </th>
                                        <th>Description </th>
                                        <th>Meta Title </th>
                                        <th>Meta Keyword </th>
                                        <th>Meta Description </th>


                                        <th colspan="1">
                                            <center>Action</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1 @endphp
                                    @foreach($cms_data as $data)
                                    <tr>
                                        <td>{{$i}}.</td>
                                        <td>{{$data->title}}</td>
                                        <td>{{$data->slug}}</td>
                                        <td>
                                            <?php
                                                $des = $data->description;
                                                echo $description = Str::words($des, '20');
                                            ?>... <a href="{{route('cmsSeeMore',$data->cms_id)}}">see more</a>
                            
                                        </td>
                                        <td>{{$data->meta_title}}</td>
                                        <td>{{$data->meta_keywords}}</td>
                                        <td>{{$data->meta_description}}</td>

                                        
                                        <td class="text-center">
                                            <a href="{{route('cms_edit',$data->cms_id)}}"><span class="basic_table_icon" style="font-size: 20px;color: green;"><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                                            <!-- <a href="{{route('cmsDelete',$data->cms_id)}}" onClick="return confirm('Are you sure? This item will move to Recycle Bin.');"><span class="basic_table_icon" style="font-size: 20px;color: red;margin-left: 20px;"><i class="fa fa-trash-o" aria-hidden="true"></i></span></a> -->
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
