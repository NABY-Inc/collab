@extends('admin.layouts.app')
@section('admin_title', 'Admin Dashboard')
@section('adminPage_heading','Project List')
@section('admin_css')
    <link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/css/sumo-select.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/plugins/sweetalert/sweetalert.css')}}"/>
    
@endsection
@section('admin_content')

    <div class="section-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <ul class="nav nav-tabs page-header-tab">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Project-OnGoing">OnGoing</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Project-UpComing">UpComing</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Project-add">Create New Project                                                                                                                       </a></li>
                        </ul>
                        <div class="header-action d-md-flex">
                            <div class="input-group mr-2">
                                {{-- <input type="text" class="form-control" placeholder="Search..."> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="Project-OnGoing" role="tabpanel">
                    <div class="row">
                        @if (count($ongoing_projects) > 0)
                            @foreach ($ongoing_projects as $project)
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title"><a href="{{route('project.show', $project->id)}}">{{$project->title}}</a></h3>
                                            <div class="card-options">
                                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fa fa-chevron-up"></i></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <a><span class="tag tag-danger mb-3">{{$project->category}}</span></a>
                                            <p>{{$project->description}}</p>
                                            <div class="row">
                                                <div class="col-5 py-1"><strong>Creator:</strong></div>
                                                <div class="col-7 py-1">{{$project->user->name}}</div>
                                                <div class="col-5 py-1"><strong>Began on:</strong></div>
                                                <div class="col-7 py-1">{{\Carbon\carbon::parse($project->dateFrom)->toFormattedDateString()}}</div>
                                                <div class="col-5 py-1"><strong>Estimated End Date:</strong></div>
                                                <div class="col-7 py-1">{{\Carbon\carbon::parse($project->dateTo)->toFormattedDateString()}}</div>
                                                <div class="col-5 py-1"><strong>Code:</strong></div>
                                                <div class="col-7 py-1"><strong>{{$project->code}}</strong></div>
                                                <div class="col-5 py-1"><strong>Team:</strong></div>
                                                <div class="col-7 py-1"><span class="">{{count($project->members)}} MEMBER(S)</span></div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="clearfix">
                                                <div class="float-left"><strong>{{$project->progress}}%</strong></div>
                                                <div class="float-right"><small class="text-muted">Progress</small></div>
                                            </div>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar bg-red" role="progressbar" style="width: {{$project->progress}}%" aria-valuenow="36" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                {!! $ongoing_projects->links() !!}
                                @else
                                <p>You are currently not partaking a project.</p>
                                @endif
                        {{-- <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Job Portal Web App</h3>
                                    <div class="card-options">
                                        <label class="custom-switch m-0">
                                            <input type="checkbox" value="1" class="custom-switch-input" checked>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fa fa-chevron-up"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="{{route('project.show', 1)}}"><span class="tag tag-pink mb-3">Angular</span></a>
                                    <p>Aperiam deleniti fugit incidunt, iste, itaque minima neque pariatur perferendis temporibus!</p>
                                    <div class="row">
                                        <div class="col-5 py-1"><strong>Created:</strong></div>
                                        <div class="col-7 py-1">09 Jun 2019 11:32AM</div>
                                        <div class="col-5 py-1"><strong>Creator:</strong></div>
                                        <div class="col-7 py-1">Nathan Guerrero</div>
                                        <div class="col-5 py-1"><strong>Question:</strong></div>
                                        <div class="col-7 py-1"><strong>55</strong></div>
                                        <div class="col-5 py-1"><strong>Comments:</strong></div>
                                        <div class="col-7 py-1"><strong>43</strong></div>
                                        <div class="col-5 py-1"><strong>Bug:</strong></div>
                                        <div class="col-7 py-1"><strong>5</strong></div>
                                        <div class="col-5 py-1"><strong>Team:</strong></div>
                                        <div class="col-7 py-1">
                                            <div class="avatar-list avatar-list-stacked">
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar6.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar7.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar8.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar1.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar2.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <span class="avatar avatar-sm">+8</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-left"><strong>75%</strong></div>
                                        <div class="float-right"><small class="text-muted">Progress</small></div>
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-green" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">App design and Development</h3>
                                    <div class="card-options">
                                        <label class="custom-switch m-0">
                                            <input type="checkbox" value="1" class="custom-switch-input" checked>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fa fa-chevron-up"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="{{route('project.show', 1)}}"><span class="tag tag-green mb-3">Android</span></a>
                                    <p>Aperiam deleniti fugit incidunt, iste, itaque minima neque pariatur perferendis temporibus!</p>
                                    <div class="row">
                                        <div class="col-5 py-1"><strong>Created:</strong></div>
                                        <div class="col-7 py-1">09 Jun 2019 11:32AM</div>
                                        <div class="col-5 py-1"><strong>Creator:</strong></div>
                                        <div class="col-7 py-1">Nathan Guerrero</div>
                                        <div class="col-5 py-1"><strong>Question:</strong></div>
                                        <div class="col-7 py-1"><strong>12</strong></div>
                                        <div class="col-5 py-1"><strong>Comments:</strong></div>
                                        <div class="col-7 py-1"><strong>96</strong></div>
                                        <div class="col-5 py-1"><strong>Bug:</strong></div>
                                        <div class="col-7 py-1"><strong>7</strong></div>
                                        <div class="col-5 py-1"><strong>Team:</strong></div>
                                        <div class="col-7 py-1">
                                            <div class="avatar-list avatar-list-stacked">
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar1.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar2.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar5.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <span class="avatar avatar-sm">+8</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-left"><strong>47%</strong></div>
                                        <div class="float-right"><small class="text-muted">Progress</small></div>
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-blue" role="progressbar" style="width: 47%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Job Portal Web App</h3>
                                    <div class="card-options">
                                        <label class="custom-switch m-0">
                                            <input type="checkbox" value="1" class="custom-switch-input" checked>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fa fa-chevron-up"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="{{route('project.show', 1)}}"><span class="tag tag-pink mb-3">Angular</span></a>
                                    <p>Aperiam deleniti fugit incidunt, iste, itaque minima neque pariatur perferendis temporibus!</p>
                                    <div class="row">
                                        <div class="col-5 py-1"><strong>Created:</strong></div>
                                        <div class="col-7 py-1">09 Jun 2019 11:32AM</div>
                                        <div class="col-5 py-1"><strong>Creator:</strong></div>
                                        <div class="col-7 py-1">Nathan Guerrero</div>
                                        <div class="col-5 py-1"><strong>Question:</strong></div>
                                        <div class="col-7 py-1"><strong>55</strong></div>
                                        <div class="col-5 py-1"><strong>Comments:</strong></div>
                                        <div class="col-7 py-1"><strong>43</strong></div>
                                        <div class="col-5 py-1"><strong>Bug:</strong></div>
                                        <div class="col-7 py-1"><strong>5</strong></div>
                                        <div class="col-5 py-1"><strong>Team:</strong></div>
                                        <div class="col-7 py-1">
                                            <div class="avatar-list avatar-list-stacked">
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar6.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar7.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar8.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar1.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar2.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <span class="avatar avatar-sm">+8</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-left"><strong>75%</strong></div>
                                        <div class="float-right"><small class="text-muted">Progress</small></div>
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-green" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">One Page landing</h3>
                                    <div class="card-options">
                                        <label class="custom-switch m-0">
                                            <input type="checkbox" value="1" class="custom-switch-input" checked>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fa fa-chevron-up"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="{{route('project.show', 1)}}"><span class="tag tag-blue mb-3">Wordpress</span></a>
                                    <p>Aperiam deleniti fugit incidunt, iste, itaque minima neque pariatur perferendis temporibus!</p>
                                    <div class="row">
                                        <div class="col-5 py-1"><strong>Created:</strong></div>
                                        <div class="col-7 py-1">09 Jun 2019 11:32AM</div>
                                        <div class="col-5 py-1"><strong>Creator:</strong></div>
                                        <div class="col-7 py-1">Nathan Guerrero</div>
                                        <div class="col-5 py-1"><strong>Question:</strong></div>
                                        <div class="col-7 py-1"><strong>23</strong></div>
                                        <div class="col-5 py-1"><strong>Comments:</strong></div>
                                        <div class="col-7 py-1"><strong>32</strong></div>
                                        <div class="col-5 py-1"><strong>Bug:</strong></div>
                                        <div class="col-7 py-1"><strong>5</strong></div>
                                        <div class="col-5 py-1"><strong>Team:</strong></div>
                                        <div class="col-7 py-1">
                                            <div class="avatar-list avatar-list-stacked">
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar1.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar2.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar3.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar4.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar5.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <span class="avatar avatar-sm">+8</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-left"><strong>17%</strong></div>
                                        <div class="float-right"><small class="text-muted">Progress</small></div>
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-red" role="progressbar" style="width: 17%" aria-valuenow="36" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Job Portal Web App</h3>
                                    <div class="card-options">
                                        <label class="custom-switch m-0">
                                            <input type="checkbox" value="1" class="custom-switch-input" checked>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fa fa-chevron-up"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="{{route('project.show', 1)}}"><span class="tag tag-gray mb-3">iOS App</span></a>
                                    <p>Aperiam deleniti fugit incidunt, iste, itaque minima neque pariatur perferendis temporibus!</p>
                                    <div class="row">
                                        <div class="col-5 py-1"><strong>Created:</strong></div>
                                        <div class="col-7 py-1">09 Jun 2019 11:32AM</div>
                                        <div class="col-5 py-1"><strong>Creator:</strong></div>
                                        <div class="col-7 py-1">Nathan Guerrero</div>
                                        <div class="col-5 py-1"><strong>Question:</strong></div>
                                        <div class="col-7 py-1"><strong>55</strong></div>
                                        <div class="col-5 py-1"><strong>Comments:</strong></div>
                                        <div class="col-7 py-1"><strong>43</strong></div>
                                        <div class="col-5 py-1"><strong>Bug:</strong></div>
                                        <div class="col-7 py-1"><strong>5</strong></div>
                                        <div class="col-5 py-1"><strong>Team:</strong></div>
                                        <div class="col-7 py-1">
                                            <div class="avatar-list avatar-list-stacked">
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar6.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar7.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar8.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar1.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <img class="avatar avatar-sm" src="{{asset('public/assets/images/xs/avatar2.jpg')}}" data-toggle="tooltip" title="" data-original-title="Avatar Name"/>
                                                <span class="avatar avatar-sm">+8</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="clearfix">
                                        <div class="float-left"><strong>81%</strong></div>
                                        <div class="float-right"><small class="text-muted">Progress</small></div>
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-green" role="progressbar" style="width: 81%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="tab-pane fade" id="Project-UpComing" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-vcenter mb-0 text-nowrap">
                                            <thead>
                                            <tr>
                                                <th>Owner</th>
                                                <th>Title</th>
                                                <th class="w100">Code</th>
                                                <th class="w100">Date</th>
                                                <th>Priority</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($upcoming_projects) > 0)
                                                    @foreach ($upcoming_projects as $project)
                                                        <tr>
                                                            <td><img src="{{asset('public/assets/images/xs/avatar1.jpg')}}" alt="Avatar" class="w30 rounded-circle mr-2"> <span>{{$project->user->name}}</span></td>
                                                            <td>{{$project->title}}</td>
                                                            <td>{{$project->code}}</td>
                                                            <td><span>{{\Carbon\carbon::parse($project->dateFrom)->toFormattedDateString()}}</span></td>
                                                            <td><span class="text-warning">{{$project->priority}}</span></td>
                                                        </tr>
                                                    @endforeach
                                                    @else
                                                    <tr><p class="text-danger">You are currently not added to any up-coming projects.</p></tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <div class="pull-right ml-3">
                                    {!! $upcoming_projects->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <project-component />

                
            </div>
        </div>
    </div>
@endsection

@section('admin_js')

    <script src="{{asset('public/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset('public/assets/js/sumo-select.js')}}"></script>
    <script src="{{asset('public/assets/bundles/sweetalert.bundle.js')}}"></script>
    <script src="{{asset('public/assets/js/page/sweetalert.js')}}"></script>
    <script>
        // $(function(){
        $(document).ready(function(){
            setTimeout(() => {
                $('.search_test').SumoSelect({search: true, placeholder:'Search here.'});
            }, 2000);
            // Dropify
            "use strict";

            $('.dropify').dropify();

            var drEvent = $('#dropify-event').dropify();
            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function(event, element) {
                alert('File deleted');
            });

            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });


        });
    </script>

@endsection
