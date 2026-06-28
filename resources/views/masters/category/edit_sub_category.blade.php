@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-sitemap"></i> Edit Sub Category</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('sub_category',$id)}}"><i class="fa fa-eye"></i> Sub Category List</a>
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
                <form action="{{route('sub_category_update_save')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @foreach($category_data as $data)
                    <div class="row col-sm-12">
                         <div class="col-sm-6">
                            <input type="hidden" name="category_id" value="{{$data->category_id}}">
                            <div class="form-group">
                                <label class="control-label"> Category Title <span class="text-danger"> * </span></label>
                                <input class="form-control" type="text" name="title" autofocus required value="{{$data->title}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Category Image <span class="text-danger">(Image Dimensions - 200*182 Pixel *)</span></label>
                                <input class="form-control" type="file" name="image" accept="image/x-png,image/gif,image/jpeg">
                                <input type="hidden" name="existing_image" value="{{$data->image}}" />
                                <input type="hidden" name="parent_id" value="{{$data->parent_id}}" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Attributes Name <span class="text-danger">*</span></label><br>
                                @foreach($attribute_data as $attribut)
                                <input type="checkbox" id="{{$attribut->attribute_id}}" name="attributesvalue[]" value="{{$attribut->attribute_id}}"><label for="{{$attribut->attribute_id}}"> &nbsp;{{$attribut->name}}</label> &nbsp;,&nbsp;
                                @endforeach
                            </div>
                        </div>
                        <div class="col-sm-6 pb-4">
                            <img src="{{ z_media_url($data->image, 'category') }}" width="300px">
                        </div>


                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Sub Category Active <span class="text-danger"> <b> * </b> </span></label>
                                <select type="text" class="form-control" required name="is_active">
                                    <option value="">-- Please Select --</option>
                                    <option value="ACTIVE" {{$data->is_active == "ACTIVE"? "selected" : " "}}>Active</option>
                                    <option value="INACTIVE" {{$data->is_active == "INACTIVE"? "selected" : " "}}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Category Show (Home Page) <span class="text-danger"> <b> * </b> </span></label>
                                <select type="text" class="form-control" required name="is_show">
                                    <option value="">-- Please Select --</option>
                                    <option value="SHOW" {{$data->is_show == "SHOW"? "selected" : " "}}>Show</option>
                                    <option value="HIDE" {{$data->is_show == "HIDE"? "selected" : " "}}>Hide</option>
                                </select>
                            </div>
                        </div>

                       
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Sub Meta Title <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="meta_title" required placeholder=" Sub Meta Title" value="{{$data->meta_title}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Sub Meta Keyword <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="meta_keyword" required placeholder=" Sub Meta Keyword " value="{{$data->meta_keyword}}">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label"> Sub Meta Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" required placeholder="Sub Meta Description Title">{{$data->description}}</textarea>
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
                        </div>
                    </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>
</main>
<!-- Essential javascripts for application to work-->



@stop
