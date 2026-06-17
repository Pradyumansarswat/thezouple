@extends('masters.layout.default_layout')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-envelope"></i>  Update Email Templates	</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <a class="btn btn-primary icon-btn" href="{{route('email_template')}}"><i class="fa fa-eye"></i> Template List</a>
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
                    <form action="{{route('emailtemplate_update_save')}}" method="post">
                      @csrf
                      @foreach($emailtemplate_edit_data as $emailtemplate)
                        <div class="row col-sm-12">
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Name</label>
                                    <input type="text" value="{{$emailtemplate->name}}" name="name" id="name" class="form-control" placeholder="Name" required>
                                    <input type="hidden" name="id" value="{{$emailtemplate->id}}">
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Maile Type</label>
                                    <input type="text" name="email_type" id="email_type" class="form-control" value="{{$emailtemplate->email_type}}" placeholder="Maile Type" required>
                                </div>
                            </div> -->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Subject</label>
                                    <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject" value="{{$emailtemplate->subject}}" required>
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"> Template Constants</label>
                                    <input type="text" name="template_constants" id="template_constants" class="form-control" value="{{$emailtemplate->template_constants}}" placeholder="Template Constants" required>
                                </div>
                            </div> -->

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label"> Description</label>
                                    <textarea  name="body" id="summary-ckeditor" class="form-control"  required>{{$emailtemplate->body}}</textarea>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>&nbsp;
                            </div>
                        </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!-- Essential javascripts for application to work-->
    


@stop
