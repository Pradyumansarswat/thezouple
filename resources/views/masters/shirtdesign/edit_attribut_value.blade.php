@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-shirtsinbulk"></i> Edit Elements Value</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('attributValue')}}"><i class="fa fa-eye"></i> Elements Value List</a>
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
                    @foreach($attribut_value_datas as $data)
                    <form action="{{route('attributValueEditSave')}}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="element_value_id" value="{{$data->element_value_id}}">
                      @csrf
                        <div class="row col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Elements Name <span class="text-danger">*</span></label>
                                    <select class="form-control" name="element_id" required>
                                        <option value=""> -- Select Field -- </option>
                                        @foreach($shirt_attribut_data as $category)
                                        <option value="{{$category->element_id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Name  <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="attribut_name" value="{{$data->attribut_name}}">
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Image <span class="text-danger">(Image Dimensions - 565*407 Pixel)</span></label>
                                    <input class="form-control" type="file" name="image" accept="image/x-png,image/gif,image/jpeg">
                                </div>
                            </div>

                            


                            <div class="col-sm-8">
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
