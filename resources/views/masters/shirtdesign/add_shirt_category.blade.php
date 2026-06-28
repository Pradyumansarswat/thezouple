@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-shirtsinbulk"></i> Add Febric</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('shirtCategory')}}"><i class="fa fa-eye"></i> Febric List</a>
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
                    <form action="{{route('shirtCategorySave')}}" method="post" enctype="multipart/form-data">
                      @csrf
                        <div class="row col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" autofocus required placeholder="Name">
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">  Image <span class="text-danger">(Image Dimensions - 565*407 Pixel)</span></label>
                                    <input class="form-control" type="file" name="image" accept="image/x-png,image/gif,image/jpeg" required >
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">  INR <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="rupee_dark_price" required placeholder="INR">
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Discount INR <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="rupee_price" required placeholder="Discount INR">
                                </div>
                            </div>
                            
                            

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">  Dollar <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="dollar_dark_price" required placeholder="Dollar">
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Discount Dollar <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="dollar_price" required placeholder="Discount Dollar">
                                </div>
                            </div>
                            
                            

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Euro <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="euro_dark_price" required placeholder="Euro">
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Discount Euro <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="euro_price" required placeholder="Discount Euro">
                                </div>
                            </div>


                            <div class="col-sm-8">
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
