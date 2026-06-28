@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-tree"></i>  Product Show Detail</h1>
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
                		@foreach($product_show_data as $show)
                        <div class="row col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <b><label class="control-label"> Category : </label></b>
                                   <?php
                                     $catArray = json_decode($show->category);   
                                    foreach($catArray as $kj)
                                    {
                                        echo $cateName[$kj]." , ";
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <b><label class="control-label"> Product SKU : </label></b>
                                    {{$show->product_sku}}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <b><label class="control-label"> Product Title : </label></b>
                                    {{$show->product_title}}
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <b><label class="control-label"> Product Vendor <span class="text-danger"><b> : </b></span></label></b>{{$vendorname}}
                                    
                                </div>
                            </div>
                            
                            <hr>
                           
                             
                            <div class="col-sm-6 mt-2">
                                <div class="form-group">
                                    <b><label class="control-label"> Product Header Image<span class="text-danger"><b> (400*400 Pixel) : </b></span></label></b><br>
                                    @php
                                        $productHeaderImage = trim((string) $show->product_header_image);
                                        $hasProductHeaderImage = z_media_exists($productHeaderImage, 'product');
                                    @endphp
                                    @if($hasProductHeaderImage)
                                        <img src="{{ z_media_url($productHeaderImage, 'product') }}" class="admin-product-thumb" alt="{{ $show->product_title }}">
                                    @else
                                        <div class="admin-product-placeholder">{{ strtoupper(substr(trim($show->product_title ?: 'P'), 0, 1)) }}</div>
                                    @endif
                                    </td>
                                    
                                </div>
                            </div>  
                            <div class="col-sm-6 mt-2">
                                <div class="form-group">
                                    <b><label class="control-label"> Product Images <span class="text-danger"><b> (Multiple Image - 400*400 Pixel) : </b></span></label></b><br>
                                    
                                    <?php
                                    
                                    $imgs = $product_gallery_images[$show->product_id] ?? json_decode($show->product_images, true);
                                    $imgs = is_array($imgs) ? $imgs : [];
                                    ?>
                                    @forelse($imgs as $val)
                                        @php
                                            $galleryImage = trim((string) $val);
                                            $hasGalleryImage = z_media_exists($galleryImage, 'product');
                                        @endphp
                                        @if($hasGalleryImage)
                                            <img src="{{ z_media_url($galleryImage, 'product') }}" class="admin-product-thumb" alt="{{ $show->product_title }}">
                                        @else
                                            <div class="admin-product-placeholder">{{ strtoupper(substr(trim($show->product_title ?: 'P'), 0, 1)) }}</div>
                                        @endif
                                    @empty
                                        <div class="admin-product-placeholder">{{ strtoupper(substr(trim($show->product_title ?: 'P'), 0, 1)) }}</div>
                                    @endforelse
                                </div>
                            </div> 
                            
                            <div class="col-sm-6 mt-3">
                                <div class="form-group">
                                    <b><label class="control-label"> Is New Product : </label></b>
                                    {{$show->new_arrivals}}
                                   
                                </div>
                            </div> 
                            <div class="col-sm-6 mt-3">
                                <div class="form-group">
                                    <b><label class="control-label"> Is Featured Product : </label></b>
                                    {{$show->featured_product}}
                                   
                                </div>
                            </div> 
                            <div class="col-sm-6 mt-1">
                                <div class="form-group">
                                    <b><label class="control-label"> Is Active : </label></b>
                                    {{$show->is_active}}
                                    
                                </div>
                            </div> 
                            <div class="col-sm-6 mt-1">
                                <div class="form-group">
                                    <b><label class="control-label"> In Stock : </label></b>
                                    {{$show->in_stock}}
                                    
                                </div>
                            </div> 
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <b><label class="control-label"> Meta Title  :  </label></b>
                                    {{$show->meta_title}}
                                    
                                </div>
                            </div> 
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <b><label class="control-label"> Meta Keyword : </label></b>
                                    <?php echo $show->meta_keyword; ?>
                                    
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <b><label class="control-label"> Meta Description : </label></b>
                                    {{$show->meta_description}}
                                    
                                </div>
                            </div> 
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <b><label class="control-label"> Product Description : </label></b>
                                    <?php echo $show->product_description; ?>
                                    
                                </div>
                            </div> 
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <b><label class="control-label"> Product Specification : </label></b>
                                    <?php echo $show->product_specification; ?>
                                    
                                </div>
                            </div> 
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <b><label class="control-label"> Product Addition Information : </label></b>
                                    <?php echo $show->product_addition_information; ?>
                                    
                                </div>
                            </div> 
                            
                    
                            
                           
                        </div>
                        @endforeach
                    
                </div>
            </div>
        </div>
    </main>
    <!-- Essential javascripts for application to work-->
    
 

@stop
