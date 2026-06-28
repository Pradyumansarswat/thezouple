@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-eye"></i> Review List</h1>
        </div>

        <ul class="app-breadcrumb breadcrumb">

            <a class="btn btn-primary icon-btn" href="{{route('addReviewInformation')}}"><i class="fa fa-plus"></i> Add Review Information</a>

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
                <div class="table-rep-plugin">
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="example" class="table  table-striped table-bordered" cellspacing="0" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>User Image</th>
                                    <th>Product Image</th>
                                    <th>Date</th>
                                    <th> Name </th>
                                    <th> Product Name </th>
                                    <th> Star </th>
                                    <th>Description</th>
                                    <th> IS Active </th>


                                    <th colspan="1">
                                        <center>Action</center>
                                    </th>
                                </tr>
                            </thead>
                            @php $k = 1 @endphp
                            @foreach($review_data as $review)
                            <tr>
                                <td>{{$k}}.</td>
                                <td>
                                    
                                    @if($review->user_profile == "")
                                    <img src="{{URL::asset('public/front/images/user.jpg')}}" width="50px">
                                    @else
                                    <img src="{{ z_media_url($review->user_profile, 'review') }}" width="50px">
                                    @endif
                                </td>

                                <td>
                                    <?php
                                    
                                    $imgs = json_decode($review->review_product_image);
                                    ?>
                                    @if($review->review_product_image == "")
                                    <h6> No Image Found </h6>
                                    @else
                                    @foreach($imgs as $val)
                                    <img src="{{ z_media_url($val, 'review') }}" width="50px">
                                    @endforeach
                                    @endif
                                </td>
                                <td>
                                    <?php

                                    $date = $review->date; 

                                    $date=date_create($date);
                                    echo date_format($date,"M, d Y");

                                    ?>
                                </td>
                                <td>{{$review->name}}</td>
                                <td>{{$review->product_title}}</td>
                                <td>

                                    <?php 

                                            $str = $review->star;
                                            $strs = substr($str, 5, 6);

                                            
                                            ?>
                                    @for($i=1; $i<=$strs; $i++) <i class="fa fa-star text-warning"></i>
                                        @endfor


                                </td>
                                <td><?php echo $review->description; ?></td>
                                <td class="text-center">
                                    <b>{{$review->is_active}}</b> <br>
                                    <form class="mt-4" action="{{route('review_status_update')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="review_id" value="{{$review->review_id}}">
                                        <select class="form-control" required name="is_active">
                                            <option value="">--Select--</option>
                                            <option value="ACTIVE">Active</option>
                                            <option value="INACTIVE">Inactive</option>
                                        </select>
                                        <input type="submit" value="Update" class="form-control mt-2 btn-info">
                                    </form>

                                </td>


                                <td class="text-center">
                                    <a href="{{route('reviewInformationUpdate',$review->review_id)}}"><span class="basic_table_icon" style="font-size: 20px;color: green;"><i class="fa fa-pencil" aria-hidden="true"></i></span></a>

                                    <a href="{{route('reviewDelete',$review->review_id)}}" onClick="return confirm('Are you sure? This item will move to Recycle Bin.');"><span class="basic_table_icon" style="font-size: 20px;color: red;margin-left: 20px;"><i class="fa fa-trash-o" aria-hidden="true"></i></span></a>
                                </td>
                            </tr>
                            @php $k++ @endphp
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
