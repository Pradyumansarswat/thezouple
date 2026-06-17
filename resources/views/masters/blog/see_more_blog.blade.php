@extends('masters.layout.default_layout')
@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-rss"></i>  Blog More Detail</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <a class="btn btn-primary icon-btn" href="{{route('blog_page')}}"><i class="fa fa-eye"></i> Blog List</a>
        </ul>
    </div>
    <div class="row bg-white py-3">
        <div class="col-md-12">
            <div class="card-box">
                @foreach($blog_datass as $data)
                    <?php echo $data->description; ?>
                @endforeach
            </div>
        </div>
    </div>
</main>
<!-- Essential javascripts for application to work-->



@stop
