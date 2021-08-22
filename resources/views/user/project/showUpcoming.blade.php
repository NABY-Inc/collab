@extends('user.layouts.app')
@section('user_title', 'User Dashboard')
@section('userPage_heading','Upcoming Project Preview')
@section('admin_css')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}"/>
    
@endsection
@section('user_content')

<div class="section-body mt-3">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="float: right !important">
                            <h4 class="pull-right font-6000">PCS Project #{{$project->id}}</h4>
                        </div>
                        <div class="d-md-flex justify-content-between mb-2">
                            <ul class="nav nav-tabs b-none">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#about"><i class="fa fa-book"></i> About Project</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#team"><i class="fa fa-users"></i> Project Team</a></li>                                        
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="section-body">
    <div class="container-fluid">
        <div class="tab-content">
            <!-- About Project -->
            <div class="tab-pane fade active show" id="about" role="tabpanel">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <img class="card-img-top" src="{{asset('/uploads/project_thumbnails/'.$project->flyer)}}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">{{$project->user->name}}'s Project</h5>
                                <p class="card-text text-center text-info font600">{{$project->title}}</p>
                            </div>
                            <div class="text-center"><small class="text-muted">Project Begins {{\Carbon\Carbon::parse($project->dateFrom)->diffForHumans()}}</small></div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-blue" role="progressbar" style="width: 100%" aria-valuenow="36" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-sm-6">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <div class="details">
                                                        <h6 class="mb-2 font600">Project Start Date</h6>
                                                        {{\Carbon\Carbon::parse($project->dateFrom)->toFormattedDateString()}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-sm-6">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <div class="details ml-15">
                                                        <h6 class="mb-2 font600">Project Estimated End Date</h6>
                                                        {{\Carbon\Carbon::parse($project->dateTo)->toFormattedDateString()}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <div class="details">
                                                        <h6 class="mb-2 font600">Category</h6>
                                                        <span class="p-1 text-white" style="background-color: {{$project->color}} !important">{{$project->category}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="card-body text-center">
                                <p class="card-link"> {{$project->description}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Team -->
            <div class="tab-pane fade" id="team" role="tabpanel">
                <div class="row clearfix">
                    @foreach ($project->members as $member)
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="card">
                                <div class="card-body text-center ribbon">
                                    <div class="ribbon-box @if($project->user_id == $member->user->id) green @else orange @endif">
                                        {{$project->user_id == $member->user->id ? 'Owner' : 'Member'}}</div>
                                    <img class="rounded-circle img-thumbnail w100" src="{{asset('/uploads/users/'.$member->user->profile)}}" alt="">
                                    <h6 class="mt-3 mb-0">{{$member->user->name}}</h6>
                                    <span>{{$member->user->email}}</span>
                                    <hr>
                                    <a class="btn btn-default btn-sm">PCS00{{$member->user->id}}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach   
                </div>
            </div>
            
        </div>
    </div>
</div>

@endsection

@section('user_js')
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/bundles/sweetalert.bundle.js')}}"></script>
    <script src="{{asset('assets/js/page/sweetalert.js')}}"></script>
@endsection
