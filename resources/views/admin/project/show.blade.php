@extends('admin.layouts.app')
@section('admin_title', 'Admin Dashboard')
@section('adminPage_heading','Preoject Detail')
@section('admin_css')
    <link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/css/sumo-select.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/plugins/sweetalert/sweetalert.css')}}"/>
    
@endsection
@section('admin_content')

    <div class="section-body mt-4">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <img class="card-img-top" src="{{asset('public/uploads/project_thumbnails/'.$project->image)}}" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">{{$project->user->name}}'s Project</h5>
                            <p class="card-text text-center text-info">{{$project->title}}</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Start Date:</strong> {{\Carbon\Carbon::parse($project->dateFrom)->toFormattedDateString()}}</li>
                            <li class="list-group-item"><strong>End Date:</strong> {{\Carbon\Carbon::parse($project->dateTo)->toFormattedDateString()}}</li>
                        </ul>
                        <div class="card-body">
                            <p class="card-link">{{$project->priority}} Priority Project</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Members</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fa fa-chevron-up"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fa fa-x"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="right_chat list-unstyled mb-0">
                                @foreach ($project->members as $member)
                                    <li class="online" id="members">
                                        <a href="javascript:void(0);">
                                            <div class="media">
                                                <img class="media-object " src="{{asset('public/assets/images/xs/avatar4.jpg')}}" alt="">
                                                <div class="media-body">
                                                    <span class="name">{{$member->user->name}}</span>
                                                    <span class="message">PCS00{{$member->user->id}}</span>
                                                    <span class="badge badge-outline status"></span>
                                                </div>
                                                @if (auth()->user()->id === $project->user_id && auth()->user()->id !== $member->user_id)
                                                <span id="removeMember" data-url="{{route('removeMember',$member->id)}}" class="text-right"><i class="fa fa-ban red" data-toggle="tooltip" title="" data-original-title="Remove"></i></span>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-sm-6">
                            <div class="card">
                                <div class="card-body widgets1">
                                    <div class="icon">
                                        <i class="fa fa-comments text-warning font-30"></i>
                                    </div>
                                    <div class="details">
                                        <h6 class="mb-0 font600">Total Comments</h6>
                                        @php
                                        $howMany = 0;
                                         foreach ($project->projectPosts as $key) {
                                             foreach ($key->comments as $data) {
                                                $howMany +=1;
                                             }
                                         }   
                                        @endphp
                                        <span class="mb-0">{{$howMany}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-sm-6">
                            <div class="card">
                                <div class="card-body widgets1">
                                    <div class="details ml-15">
                                        <h6 class="mb-0 font600">Project Joining Code</h6>
                                        <pre class="mb-0">{{$project->code}}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-sm-12">
                            <div class="card">
                                <div class="card-body widgets1">
                                    <div class="icon">
                                        <i class="fa fa-check text-danger font-30"></i>
                                    </div>
                                    <div class="details">
                                        <h6 class="mb-0 font600">This Project's Posts</h6>
                                        <span class="mb-0">{{count($project->projectPosts)}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-blog-tab" data-toggle="pill" href="#pills-blog" role="tab" aria-controls="pills-blog" aria-selected="true">My Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-timeline-tab" data-toggle="pill" href="#pills-timeline" role="tab" aria-controls="pills-timeline" aria-selected="false">Timeline Posts</a>
                        </li>
                        @if (auth()->user()->id === $project->user_id)
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Edit Project</a>
                        </li>                            
                        @endif
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <mypost-component :project_id="{{$project->id}}"></mypost-component>

                        <timelinepost-component :project_id="{{$project->id}}" :user_id={{auth()->user()->id}}></timelinepost-component>

                        @if (auth()->user()->id === $project->user_id)
                        <projectedit-component :project="{{$project}}"/>
                        @endif

                    </div>
                </div>
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

            // When user clicks on remove member
            $('#members #removeMember').click(function(){
                var url = $(this).data('url');
                swal({
                    title: "Warning!",
                    text: 'Are You sure you want to remove member ?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, remove!',
                    closeOnConfirm: false
                }, function () {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success:function(response){
                        swal({
                            title:"Success!",
                            text: "Memebr Removed successfully.",
                            type: "success",
                        }, function(){
                            location.reload();
                        });
                        }
                    });
                });
                
            });


        });
    </script>

@endsection