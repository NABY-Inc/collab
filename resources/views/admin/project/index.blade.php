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
                                <button type="button" style="background-color: red;color:white" class="btn btn-default" data-toggle="modal" data-target="#addtask">Join Project</button>
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
                                                <div class="col-7 py-1"><strong>{{$project->user_id == auth()->user()->id ? $project->code : 'Ask project owner'}}</strong></div>
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
                            <div class="card">
                                <p class="card-body mt-20 mb-20 ml-5 text-danger">You are currently not partaking a project.</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="Project-UpComing" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-vcenter mb-0 text-nowrap">
                                            @if (count($upcoming_projects) > 0)
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
                                                    @foreach ($upcoming_projects as $project)
                                                        <tr>
                                                            <td><img src="{{asset('public/uploads/users/'.$project->user->img)}}" alt="Avatar" class="w30 rounded-circle mr-2"> <span>{{$project->user->name}}</span></td>
                                                            <td>{{$project->title}}</td>
                                                            <td>{{$project->user_id == auth()->user()->id ? $project->code : 'Ask project owner'}}</td>
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

                <project-component :user_role="{{auth()->user()->role}}"/>

                
            </div>
        </div>
    </div>

    <div class="modal fade" id="addtask" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <form action="{{route('joinProject')}}" method="POST" class="joinProject">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="title" id="defaultModalLabel">Join Project</h6>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="text" id="code" class="form-control" placeholder="Enter Project ID" required name="code">
                                    <small style="color: blue">Project ID's are case sensitive. So type them as giving.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="token" value="{{csrf_token()}}">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Join</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
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


            $('.joinProject').submit(function(e){
                e.preventDefault();
                var  code = $('#code').val();
                var  token = $('#token').val();
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {'code':code,'_token':token},
                    success:function(response){
                        if (response == 2) {
                            swal("Error!", "Invalid Code! Please check and retry.", "error");
                        }else if(response == 0){
                            swal("Oops!", "You are already part of this project.", "error");
                        }else if(response == 1){
                            swal({
                                title:"Success!",
                                text: "You are part of the project now.",
                                type: "success",
                            }, function(){
                                location.reload();
                           });
                        }
                    }
                });
                    
            })

        });
    </script>

@endsection
