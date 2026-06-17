@extends('masters.layout.default_layout')
@section('content')

<script type="text/javascript" src="{{URL::asset('public/js/213jquery.min.js')}}"></script>

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-tree"></i> Update Quantity & Price</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('product_list')}}"><i class="fa fa-eye"></i> Product Quantity List</a>
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
                <div class="row">
                    @foreach($products as $data)
                    <div class="col-sm-6">
                       <b>Product Name :</b>  {{$data->product_title}}
                    </div>
                    <div class="col-sm-6">
                        <b>Product SKU :</b> {{$data->product_sku}}
                    </div>
                    
                    @endforeach
                </div>
                <hr>
                @foreach($products as $prod)
                 <form action="{{route('updateProductGSTSave')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{$prod->product_id}}">
                    <div class="row mt-3">
                        <div class="col-sm-2">
                            <div class="form-group clearfix">
                                <label class="control-label " for="userName1">Product GST <span class="text-danger"><b>*</b></span></label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control required" id="product_gst" name="product_gst" type="number" value="{{$prod->product_gst}}">
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>&nbsp;
                        </div>
                    </div>
                </form>
                @endforeach
                <hr>
                <form action="{{route('updateProductQuantity')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="table-repsonsive">
                                <span id="error"></span>
                                <table class="table table-bordered" id="item_table" width="100%">
                                    <tr class="bg-light">
                                        <th>Attributs Details</th>
                                        <th>Quantity</th>
                                        <th>Price (Without GST RS)</th>
                                        <th>Dollar (Without GST $)</th>
                                        <th>Euro (Without GST €)</th>
                                        <th>Discount(%)</th>
                                        <th>Weight(K.G.)</th> 
                                    </tr>
                                    @foreach($pro_attributs as $data)
                                    <tr>
                                        <td>
                                            <?php $dt = json_decode($data->attributes_value);
                                            $value = implode(',',$dt);
                                            ?>
                                            <input type="text" name="attributes_value[]" value="{{$value}}" readonly>
                                            <input type="hidden" name="product_quantity_id[]" value="{{$data->product_quantity_id}}">
                                        </td>
                                        <td><input type="text" name="qty[]" value="{{$data->product_quantity}}"></td>
                                        <td><input type="text" name="price[]" value="{{$data->rupee_price}}"></td>
                                        <td><input type="text" name="doller[]" value="{{$data->dollar_price}}"></td>
                                        <td><input type="text" name="euro[]" value="{{$data->euro_price}}"></td>
                                        <td><input type="text" name="discount[]" value="{{$data->product_discount}}"></td>
                                        <td><input type="text" name="weight[]" value="{{$data->product_weight}}"></td>
                                        
                                    </tr>
                                    @endforeach
                                </table>
                            </div>


                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>&nbsp;
                </form>
            </div>
        </div>
    </div>
</main>


<script>
    var usdRate = {{ config('currency.usd_rate', 30) }};
    var eurRate = {{ config('currency.eur_rate', 32) }};

    document.querySelectorAll('input[name="price[]"]').forEach(function (rupeeInput, index) {
        rupeeInput.addEventListener('input', function () {
            var rupee = parseFloat(this.value) || 0;
            var dollarInputs = document.querySelectorAll('input[name="doller[]"]');
            var euroInputs = document.querySelectorAll('input[name="euro[]"]');

            if (dollarInputs[index]) {
                dollarInputs[index].value = rupee > 0 ? Math.round(rupee / usdRate) : 0;
            }

            if (euroInputs[index]) {
                euroInputs[index].value = rupee > 0 ? Math.round(rupee / eurRate) : 0;
            }
        });
    });
</script>

@stop
