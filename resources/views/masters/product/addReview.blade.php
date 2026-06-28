@extends('masters.layout.default_layout')
@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-eye"></i> Add Review </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('review_information')}}"><i class="fa fa-eye"></i> Review List</a>
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
                <form action="{{route('reviewInformationSave')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row col-sm-12">


                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Product Vendors <span class="text-danger"> <b> * </b> </span><span class="text-danger"><b></b></span></label>
                                <select class="form-control" required name="product_id">
                                    <option value=""> -- Select Field -- </option>
                                    @foreach($productsData as $product)
                                    <option value="{{$product->product_id}}">{{$product->product_title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Name <span class="text-danger"> <b> * </b> </span></label>
                                <input class="form-control" type="text" name="name" required placeholder="Name">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> User Image<span class="text-danger"><b> (720*900 Pixel *)</b></span></label>
                                <input type="file" name="user_profile" id="pincode" class="form-control" required accept="image/x-png,image/gif,image/jpeg">
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Product Image<span class="text-danger"><b> (720*900 Pixel *)</b></span></label>
                                <input type="file" name="review_product_image[]" class="form-control" required accept="image/x-png,image/gif,image/jpeg" multiple>
                            </div>
                        </div>


                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Active<span class="text-danger"> <b> * </b> </span></label>
                                <select type="text" class="form-control" required name="is_active">
                                    <option value="">-- Please Select --</option>
                                    <option value="ACTIVE">Active</option>
                                    <option value="INACTIVE">Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Date <span class="text-danger"> <b> * </b> </span></label>
                                <input class="form-control" type="date" name="date" required >
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Rating<span class="text-danger"> <b> * </b> </span></label>
                                <select type="text" class="form-control" required name="star">
                                    <option value="">-- Please Select --</option>
                                    <option value="star-1">1</option>
                                    <option value="star-2">2</option>
                                    <option value="star-3">3</option>
                                    <option value="star-4">4</option>
                                    <option value="star-5">5</option>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label"> Description<span class="text-danger"> <b> * </b> </span></label>
                                <textarea class="form-control" id="summary-ckeditor" name="description" required placeholder="Description"></textarea>
                            </div>
                        </div>


                        <div class="col-sm-12">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>&nbsp;
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<!-- Essential javascripts for application to work-->









@stop
