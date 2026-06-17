@extends('masters.layout.default_layout')
@section('content')
<style>
    .flash_hide {
        display: none;
    }

    .flash_show {
        display: flex;
    }

</style>
<script type="text/javascript" src="{{URL::asset('public/js/213jquery.min.js')}}"></script>

<script src="{{URL::asset('public/js/jquery.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $("form").submit(function(e) {
            var messageLength = CKEDITOR.instances['summary-ckeditor'].getData().replace(/<[^>]*>/gi, '').length;
            if (!messageLength) {
                alert('Please Fill Product Specification');
                e.preventDefault();
            }
        });
    })
    $(document).ready(function() {
        $("form").submit(function(e) {
            var messageLength = CKEDITOR.instances['summary-ckeditor1'].getData().replace(/<[^>]*>/gi, '').length;
            if (!messageLength) {
                alert('Please Fill Product Description');
                e.preventDefault();
            }
        });
    })
    $(document).ready(function() {
        $("form").submit(function(e) {
            var messageLength = CKEDITOR.instances['summary-ckeditor2'].getData().replace(/<[^>]*>/gi, '').length;
            if (!messageLength) {
                alert('Please Fill Product Addition Information');
                e.preventDefault();
            }
        });
    })

</script>

<script>
    function maxLengthCheck(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }

    function isNumeric(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }

    function changeFlashSale(str) {
        if (str == "YES") {
            $('#flash_sale').addClass('flash_show');
            $('#flash_sale').removeClass('flash_hide');
        } else {
            $('#flash_sale').addClass('flash_hide');
            $('#flash_sale').removeClass('flash_show');
        }

    }

</script>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-tree"></i> Add Product</h1>
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
                <form action="{{route('filterProductSave')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label"> Choose Categories <span class="text-danger"> <b> * </b> </span></label>
                            <div class="form-group py-3 px-3" style="border:2px solid #CED4DA; height:400px; overflow:auto;border-radius:5px;">
                                @foreach($categories as $category)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" value="{{$category->category_id}}" name="category[]">{{$category->title}}
                                    </label>
                                </div>
                                <?php $childs = $category->childs; ?>
                                @if(count($category->childs))
                                <ul type="none">
                                    @foreach($childs as $child)
                                    @if($child->is_active == "ACTIVE")
                                    <li>
                                        <div class="form-check pt-1">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" value="{{$child->category_id}}" name="category[]">{{$child->title}}
                                            </label>
                                        </div>
                                        <?php $childs = $child->childs; ?>
                                        <ul type="none">
                                            @if(count($child->childs))
                                            @foreach($childs as $child)
                                            @if($child->is_active == "ACTIVE")
                                            <li>
                                                <div class="form-check pt-1">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="checkbox" value="{{$child->category_id}}" name="category[]">{{$child->title}}
                                                    </label>
                                                </div>

                                            </li>
                                            @endif
                                            @endforeach
                                            @endif
                                        </ul>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                                @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Show Product</button>&nbsp;
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@stop