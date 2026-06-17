@extends('masters.layout.default_layout')
@section('content')
<style>
   a:hover {
  text-decoration: none;
}
    
</style>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
            </ul>
        </div>
        <div class="row">
            
             <a href="{{route('userlist')}}">
            <div class="col-md-6 col-lg-3">
               
                <div class="widget-small primary coloured-icon">
                    
                    <i class="icon fa fa-users fa-3x"></i></a>
                    <div class="info">
                       <h4>Users</h4>
                       
                        <p><b>{{$dashboard_users}}</b></p>
                        
                    </div>
                    
                </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="{{route('product_list')}}">
                <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                    <div class="info">
                        <h4>Product</h4>
                        <p><b>{{$dashboard_product}}</b></p>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="{{route('order_information')}}">
                <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
                    <div class="info">
                        <h4>Order</h4>
                        <p><b>{{$dashboard_order}}</b></p>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3">
                <a href="{{route('review_information')}}">
                <div class="widget-small danger coloured-icon"><i class="icon fa fa-star fa-3x"></i>
                    <div class="info">
                        <h4>Stars</h4>
                        <p><b>{{$dashboard_review}}</b></p>
                    </div>
                </div>
                </a>
            </div>
        </div>
         <!--<div class="row">
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">Monthly Sales</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">Support Requests</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <canvas class="embed-responsive-item" id="pieChartDemo"></canvas>
                    </div>
                </div>
            </div>
        </div> -->
        
    </main>
@stop