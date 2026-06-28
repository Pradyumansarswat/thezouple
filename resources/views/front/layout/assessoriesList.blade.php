@foreach($products as $data)
<?php
$pro_ass = json_decode($data->product_option_access);
foreach($pro_ass as $dt)
{
?>
<div class="col-12 col-sm-6">
    <div class="form-group d-flex">
        <input type="radio" class="align-self-center radioPrdDeat" id="{{$dt}}" value="{{$dt}}" name="optionalAcc" required>
        <label for="{{$dt}}" class="d-flex align-self-center">
            <img src="{{ z_media_url($accessImage[$dt], 'accessories') }}" width="100%" style="max-width: 100px; max-height:100px;" class="border mx-2">
            <div class="h6 m-0 align-self-center">{{$accessName[$dt]}}</div>
            
        </label>
        
    </div>
    <div style="font-size:14px">{{$accessabout[$dt]}}</div>
</div>
<?php 
}
?>
@endforeach