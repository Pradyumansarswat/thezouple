@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-tree"></i> Import Product</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('product_list')}}"><i class="fa fa-eye"></i> Product List</a>
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

                @csrf
                <div class="row col-sm-12">
                    <div class="col-sm-6">
                        <form action="{{route('productimages_save')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="control-label"> Product All Image - Select Multiple<span class="text-danger"><b> (Should Be Iamge - 400*400 Pixel)</b></span></label>
                                <input type="file" name="product_images[]" class="form-control" required accept="image/x-png,image/gif,image/jpeg" multiple>
                            </div>
                            <div>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Upload Image</button>&nbsp;
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-6">
                        <form action="{{route('productexcel_save')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="control-label"> Excel Upload<span class="text-danger"><b> (Select Only CSV File)</b></span></label>
                                <input type="file" name="product_excel" class="form-control" required >
                            </div>
                            <div>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Upload CSV</button>&nbsp;
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</main>
<!-- Essential javascripts for application to work-->
<script type="text/javascript">
    function change_sub_category(id) {
        if (id) {
            $.ajax({
                url: '../sub_cate_pro/' + id,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#sub_cate').empty();
                    $('#sub_cate').append('<option value="0">-- Please Select --</option>');
                    $.each(data, function(key, value) {
                        $('#sub_cate').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        } else {
            $('#sub_cate').empty();
        }
    }

    function change_sub_sub_category(id) {
        if (id) {
            $.ajax({
                url: '../sub_sub_cate_pro/' + id,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#sub_sub_cate').empty();
                    $('#sub_sub_cate').append('<option value="0">-- Please Select --</option>');
                    $.each(data, function(key, value) {
                        $('#sub_sub_cate').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        } else {
            $('#sub_sub_cate').empty();
        }
    }

    function change_sub_sub_sub_category(id) {
        if (id) {
            $.ajax({
                url: '../sub_sub_sub_cate_pro/' + id,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#sub_sub_sub_cate').empty();
                    $('#sub_sub_sub_cate').append('<option value="0">-- Please Select --</option>');
                    $.each(data, function(key, value) {
                        $('#sub_sub_sub_cate').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        } else {
            $('#sub_sub_sub_cate').empty();
        }
    }

</script>



@stop
