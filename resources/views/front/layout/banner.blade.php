<section>
    <div class="container-fluid  sliderWrapper">
        <div class="row">
            @foreach($banner as $bannerlist)
            <div class="col-12 p-0 position-relative bannerHover">
                <img src="{{ z_media_url($bannerlist->image, 'banner') }}" class="bannerImage" >
            </div>
            @endforeach
        </div>

    </div>
