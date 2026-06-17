
@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-futbol-o"></i> Site Information List</h1>
            </div>
            
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
                    <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table id="example" class="table  table-striped table-bordered" cellspacing="0" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th> Site Profile </th>
                                        <th>Phone Number </th>
                                        <th>Address </th>
                                        <th>Meta Email </th>
                                        <th>Meta Title </th>
                                        <th>Meta Description</th>
                                        <th>Meta Keyword</th>
                                        <th>Email Signature </th>
                                        <th>Facebook Url</th>
                                        <th>Pinterest Url</th>
                                        <th>youtube Url</th>
                                        <th>Twitter Url</th>
                                        <th>Linkedin</th>
                                        <th>Instagram</th>


                                        <th colspan="1">
                                            <center>Action</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1 @endphp
                                    @foreach($site_infor_data as $site)
                                    <tr>
                                        <td>{{$i}}.</td>
                                        <td>{{$site->site_profile}}</td>
                                        <td>{{$site->phone_number}}</td>
                                        <td>{{$site->address}}</td>
                                        <td>{{$site->meta_email}}</td>
                                        <td>{{$site->meta_title}}</td>
                                        <td>{{$site->meta_keyword}}</td>
                                        <td><?php echo $site->meta_description; ?></td>
                                        <td>{{$site->email_signature}}</td>
                                        <td>{{$site->facebook_url}}</td>
                                        <td>{{$site->twitter_url}}</td>
                                        <td>{{$site->pinterest}}</td>
                                        <td>{{$site->youtube}}</td>
                                        <td>{{$site->linkedin_url}}</td>
                                        <td>{{$site->instagram_url}}</td>

                                       
                                        <td class="text-center">
                                            <a href="{{route('siteinformationUpdate',$site->siteinfo_id)}}"><span class="basic_table_icon" style="font-size: 20px;color: green;"><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                                            
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
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