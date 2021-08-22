@extends('user.layouts.app')
@section('user_title', 'User Dashboard')
@section('userPage_heading','Dashboard')
@section('user_css')
    <link rel="stylesheet" href="{{asset('public/assets/plugins/charts-c3/c3.min.css')}}" />
@endsection
@section('user_content')

<div class="section-body mt-3">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="mb-4">
                    <h4>Welcome {{auth()->user()->name}}</h4>
                </div>
            </div>
        </div>
        <div class="row clearfix row-deck">
            <div class="col-xl-4 col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">OnGoing Projects</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="number mb-0 font-32 counter">{{count($ongoing)}}</h5>
                        <span class="font-12">Projects that you are involved</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">UpComing Projects</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="number mb-0 font-32 counter">{{count($upcoming)}}</h5>
                        <span class="font-12">Projects that you are involved</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Created Projects</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="number mb-0 font-32 counter">{{count($userProjects)}}</h5>
                        <span class="font-12">Projects you have created</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="section-body">
    <div class="container-fluid">
        <div class="row clearfix row-deck">
        </div>
        <div class="row clearfix">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Projects Involved</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped text-nowrap table-vcenter mb-0">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>State</th>
                                    <th>Start Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($projectsInvolved) > 0)
                                    @foreach ($projectsInvolved as $project)
                                        <tr>
                                            <td><a href="{{route('project.show',$project->id)}}"><u>{{$project->title}}</u></a></td>
                                            @if ($project->dateFrom <= \Carbon\Carbon::now())
                                            <td><span class="tag tag-primary">On-going</span></td>
                                            @else
                                            <td><span class="tag tag-warning">Upcoming</span></td>
                                            @endif
                                            <td>{{\Carbon\Carbon::parse($project->dateFrom)->toFormattedDateString()}}</td>
                                            <td>{{\Carbon\Carbon::parse($project->dateTo)->toFormattedDateString()}}</td>
                                            <td><span class="tag @if($project->freeze == 1) tag-success @else tag-danger @endif">{{$project->freeze == 1 ? 'Locked' : 'Active'}}</span></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('user_js')

    <script src="{{asset('public/assets/bundles/apexcharts.bundle.js')}}"></script>
    <script src="{{asset('public/assets/bundles/counterup.bundle.js')}}"></script>
    <script src="{{asset('public/assets/bundles/knobjs.bundle.js')}}"></script>
    <script src="{{asset('public/assets/bundles/c3.bundle.js')}}"></script>
    <script src="{{asset('public/assets/js/page/project-index.js')}}"></script>

@endsection