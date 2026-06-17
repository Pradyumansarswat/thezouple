@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-sitemap"></i> Cateogries List</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('add_category')}}"><i class="fa fa-plus"></i> Add Category</a>
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
                                    <th>Title</th>
                                    
                                    <th> Attributes List</th>
                                    <th> Image </th>
                                    <th> Meta Title </th>
                                    <th> Meta Keyword </th>
                                    <th> Meta Description </th>
                                    <th>Status </th>
                                    <th>Show at HomePage </th>


                                    <th colspan="1">
                                        <center>Action</center>
                                    </th>
                                    <th>Sub Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1 @endphp
                                @foreach($category_data as $data)
                                <tr>
                                    <td>{{$i}}.</td>
                                    
                                    <td>{{$data->title}}</td>
                                    <td>
                                       
                                        <?php
                                        if($data->attributesvalue != "null")
                                        {
                                            $at = json_decode($data->attributesvalue);
                                            foreach($at as $dt)
                                            {
                                                echo $attributeName[$dt].", <br>";
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><img src="{{URL::asset('public/upload/category/'.$data->image)}}" width="200px"></td>
                                    <td>{{$data->meta_title}}</td>
                                    <td>{{$data->meta_keyword}}</td>
                                    <td>{{$data->description}}</td>

                                    <td class="text-center py-2">
                                        <b>{{$data->is_active}}</b> <br>
                                        <form class="mt-4" action="{{route('category_status_update')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="category_id" value="{{$data->category_id}}">
                                            <select class="form-control" required name="is_active">
                                                <option value="">--Select--</option>
                                                <option value="ACTIVE">Active</option>
                                                <option value="INACTIVE">Inactive</option>
                                            </select>
                                            <input type="submit" value="Update" class="form-control mt-2 btn-info">
                                        </form>

                                    </td>
                                    <td class="text-center py-2">
                                        <b>{{$data->is_show}}</b> <br>
                                        <form class="mt-4" action="{{route('category_show_update')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="category_id" value="{{$data->category_id}}">
                                            <select type="text" class="form-control" required name="is_show">
                                                <option value="">-- Please Select --</option>
                                                <option value="SHOW">Show</option>
                                                <option value="HIDE">Hide</option>
                                            </select>
                                            <input type="submit" value="Update" class="form-control mt-2 btn-info">
                                        </form>

                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('category_edit',$data->category_id)}}"><span class="basic_table_icon" style="font-size: 20px;color: green;"><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                                        <a href="{{route('categoryDelete',$data->category_id)}}" onClick="return confirm('Are you sure?');"><span class="basic_table_icon" style="font-size: 20px;color: red;margin-left: 20px;"><i class="fa fa-trash-o" aria-hidden="true"></i></span></a>
                                    </td>
                                    <td>
                                        <a href="{{route('sub_category',$data->category_id)}}"><span class="basic_table_icon" style="font-size: 20px;color: blue;margin-left: 20px;"><i class="fa fa-list-alt" aria-hidden="true"></i></span></a>
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
