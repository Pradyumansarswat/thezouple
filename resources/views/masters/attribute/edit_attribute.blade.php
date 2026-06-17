@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-sitemap"></i> Edit Attribute</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('attribute')}}"><i class="fa fa-plus"></i> Attribute List </a>
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
                @foreach($attributes_datas as $attribute)
                <form action="{{route('attributeEditSave')}}" method="post">
                    @csrf
                    <input type="hidden" name="attribute_id" value="{{$attribute->attribute_id}}">
                    <div class="row col-sm-12">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Name<span class="text-danger"> <b> * </b></span></label>
                                <input class="form-control" type="text" name="name" required placeholder="Name" value="{{$attribute->name}}">
                            </div>
                        </div>


                        <div class="col-sm-12">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>&nbsp;
                        </div>
                    </div>
                </form>
                @endforeach
            </div>
        </div>
    </div>
</main>

@stop
