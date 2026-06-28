@extends('masters.layout.default_layout')
@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-tree"></i> Update Product Sales</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('product_list')}}"><i class="fa fa-eye"></i> Product List</a>
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
                @foreach($flash_data as $flash)
                <form action="{{route('flashProductSave')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        @foreach($productss as $data)
                        <input type="hidden" name="product_id" value="{{$data->product_id}}">
                        @endforeach
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Start Date <span class="text-danger"> <b> * </b> </span></label>
                                <input type="date" name="start_date" class="form-control" required value="{{$flash->start_date}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> End Date <span class="text-danger"> <b> * </b> </span></label>
                                <input type="date" name="end_date" value="{{$flash->end_date}}" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Start Time <span class="text-danger"> <b> * </b> </span></label>
                                <input type="time" name="start_time" class="form-control" required value="{{$flash->start_time}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> End Time <span class="text-danger"> <b> * </b> </span></label>
                                <input type="time" name="end_time" class="form-control" required value="{{$flash->end_time}}">
                            </div>
                        </div>

                        <!--<div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Product Prize <span class="text-danger"> <b> * </b> </span></label>
                                <input type="number" name="product_prize" class="form-control" required value="{{$flash->product_prize}}">
                            </div>
                        </div>-->

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"> Active<span class="text-danger"> <b> * </b> </span></label>
                                <select type="text" class="form-control" required name="flash_active">
                                    <option value="">-- Please Select --</option>
                                    <option value="ACTIVE" {{$flash->flash_active == "ACTIVE"? "selected" : " "}}>Active</option>
                                    <option value="INACTIVE" {{$flash->flash_active == "INACTIVE"? "selected" : " "}}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="table-repsonsive">
                                <span id="error"></span>
                                <table class="table table-bordered" id="item_table">
                                    <tr class="bg-light">
                                        <th>Attributs Details</th>
                                        <th>Rupee Price (₹)</th>
                                        <th>Dollar Price ($)</th>
                                        <th>Euro Price (€)</th>
                                    </tr>
                                    @foreach($pro_attributss as $data)
                                    <tr>
                                        <td>
                                            <?php $dt = json_decode($data->attributes_value);
                                            $value = implode(',',$dt);
                                            ?>
                                            <input type="text" name="attributes_value[]" value="{{$value}}" readonly>
                                            <input type="hidden" name="product_quantity_id[]" value="{{$data->product_quantity_id}}">
                                        </td>
                                        <td><input type="text" name="price[]" value="{{$data->rupee_price}}"></td>
                                        <td><input type="text" name="dollar[]" value="{{$data->dollar_price}}"></td>
                                        <td><input type="text" name="euro[]" value="{{$data->euro_price}}"></td>
                                        
                                    </tr>
                                    @endforeach
                                </table>
                            </div>


                        </div>
                    </div>
                    <div class="row">
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
<!-- Essential javascripts for application to work-->







@stop
