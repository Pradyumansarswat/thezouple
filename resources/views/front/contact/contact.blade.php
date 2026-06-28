@extends('front.layout.default_layout')
@section('content')

<!-- Banner Code Start -->

@include('front.layout.banner')

<!-- Banner Code End -->
<!--======================   breadcumbs =======================-->
<div class="container-fluid" style="border-bottom:6px solid black;border-top:6px solid black; background-color:gray;">
    <div class="row px-5 maxWidhtContainer">
        <div class=" col-12 align-self-center col-sm-12 col-md-12  h6 m-0  text-white" >
            <nav aria-label="breadcrumb m-0 ">
                <ol class="breadcrumb m-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{url('/')}}" class=" h6 m-0 text-white">Home</a></li>
                    <li class="breadcrumb-item text-white">Contact</li>
                </ol>
            </nav>
        </div>
    </div>


</div>


<div class="contactBack">

    <div class="container">
        <div class="row py-5">
            <div class="col-md-6 py-5 text-white">
                <div>
                    <h2>Get in Touch</h2>
                </div>
                
                <div class="p text-justify px-4">
                    <!--<div class="pl-2">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                    </div>-->
                </div>
                <div class="h5 py-3 m-0"><i class="fa fa-map-marker pr-2 "></i> Find us</div>
                
                @foreach($site_data as $site)
                <div class="p px-4">
                    <ul class="list-unstyled pl-2">
                        <li>
                            <?php echo $site->address;?>
                        </li>
                    </ul>
                </div>
                @endforeach
                @foreach($site_data as $site)
                @if($site->phone_number != "")
                <div class="h5 py-3 m-0"><i class="fa fa-phone pr-2"></i> Give us a ring</div>
                <div class="p px-4">
                    <ul class="list-unstyled pl-2">
                        <li>
                           <?php echo $site->phone_number; ?>
                        </li>

                    </ul>
                </div>
                @endif
                @endforeach

                <div class="h5 py-3 m-0"><i class="fa fa-envelope-o pr-2"></i> Email</div>
                @foreach($site_data as $site)
                <div class="p px-4">
                    <ul class="list-unstyled pl-2">
                        <li>
                            contact@zouple.in(For General Enquiry)
                            <br>
                            order@zouple.in(For order related)
                        </li>

                    </ul>
                </div>
                @endforeach

            </div>

            <div class="col-md-6 col-lg-5 py-5 offset-lg-1">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <div class="px-2">
                            <div class="text-center contBgGra py-3 rounded shadow">
                                <h5 class="m-0">Contact us</h5>
                            </div>
                        </div>

                        <form method="post" action="{{url('contactSave')}}">
                            @csrf
                            <div class="form-group">
                                <label for="inp" class="inp">
                                    <input type="text" required id="inp" placeholder="&nbsp;" name="name">
                                    <span class="label">Name:</span>
                                    <span class="borBot"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="mail" class="inp">
                                    <input type="email" required id="mail" placeholder="&nbsp;" name="email">
                                    <span class="label">Email:</span>
                                    <span class="borBot"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="inp">
                                    <input type="number" required id="phone" placeholder="&nbsp;" name="phone">
                                    <span class="label">Phone:</span>
                                    <span class="borBot"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="sub" class="inp">
                                    <input type="text" required id="sub" placeholder="&nbsp;" name="subject">
                                    <span class="label">Subject:</span>
                                    <span class="borBot"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="msg" class="inp">
                                    <textarea rows="5" required cols="10" id="msg" placeholder="&nbsp;" style="resize: none;" name="message"></textarea>

                                    <span class="label">Message:</span>
                                    <span class="borBot2"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="cta border-0">
                                    <span>Submit</span>
                                    <svg width="13px" height="10px" viewBox="0 0 13 10">
                                        <path d="M1,5 L11,5"></path>
                                        <polyline points="8 1 12 5 8 9"></polyline>
                                    </svg>
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




</section>

@stop
