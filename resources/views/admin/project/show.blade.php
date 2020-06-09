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
                        <img class="card-img-top" src="{{asset('public/assets/images/gallery/6.jpg')}}" alt="Card image cap">
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
                                        <i class="fa fa-comment text-warning font-30"></i>
                                    </div>
                                    <div class="details">
                                        <h6 class="mb-0 font600">Total Comments</h6>
                                        <span class="mb-0">6,270</span>
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
                                        <h6 class="mb-0 font600">Total Resources</h6>
                                        <span class="mb-0">720 Delivered</span>
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
                        <div class="tab-pane fade" id="pills-timeline" role="tabpanel" aria-labelledby="pills-timeline-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Activity</h3>
                                    <div class="card-options">
                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fa fa-chevron-up"></i></a>
                                        <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fa fa-maximize"></i></a>
                                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                                        <div class="item-action dropdown ml-2">
                                            <a href="javascript:void(0)" data-toggle="dropdown"><i class="fa fa-more-vertical"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-eye"></i> View Details </a>
                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-share-alt"></i> Share </a>
                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-cloud-download"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-copy"></i> Copy to</a>
                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-folder"></i> Move to</a>
                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-edit"></i> Rename</a>
                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-trash"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="timeline_item ">
                                        <img class="tl_avatar" src="{{asset('public/assets/images/xs/avatar1.jpg')}}" alt="">
                                        <span><a href="javascript:void(0);">Elisse Joson</a> San Francisco, CA <small class="float-right text-right">20-April-2019 - Today</small></span>
                                        <h6 class="font600">Hello, 'Im a single div responsive timeline without media Queries!</h6>
                                        <div class="msg">
                                            <p>I'm speaking with myself, number one, because I have a very good brain and I've said a lot of things. I write the best placeholder text, and I'm the biggest developer on the web card she has is the Lorem card.</p>
                                            <a href="javascript:void(0);" class="mr-20 text-muted"><i class="fa fa-heart text-pink"></i> 12 Love</a>
                                            <a class="text-muted" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-comments"></i> 1 Comment</a>
                                            <div class="collapse p-4 section-gray" id="collapseExample">
                                                <form class="well">
                                                    <div class="form-group">
                                                        <textarea rows="2" class="form-control no-resize" placeholder="Enter here for tweet..."></textarea>
                                                    </div>
                                                    <button class="btn btn-primary">Submit</button>
                                                </form>
                                                <ul class="recent_comments list-unstyled mt-4 mb-0">
                                                    <li>
                                                        <div class="avatar_img">
                                                            <img class="rounded img-fluid" src="{{asset('public/assets/images/xs/avatar4.jpg')}}" alt="">
                                                        </div>
                                                        <div class="comment_body">
                                                            <h6>Donald Gardner <small class="float-right font-14">Just now</small></h6>
                                                            <p>Lorem ipsum Veniam aliquip culpa laboris minim tempor</p>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="timeline_item ">
                                        <img class="tl_avatar" src="{{asset('public/assets/images/xs/avatar4.jpg')}}" alt="">
                                        <span><a href="javascript:void(0);" title="">Dessie Parks</a> Oakland, CA <small class="float-right text-right">19-April-2019 - Yesterday</small></span>
                                        <h6 class="font600">Oeehhh, that's awesome.. Me too!</h6>
                                        <div class="msg">
                                            <p>I'm speaking with myself, number one, because I have a very good brain and I've said a lot of things. on the web by far... While that's mock-ups and this is politics, are they really so different? I think the only card she has is the Lorem card.</p>
                                            <div class="timeline_img mb-20">
                                                <img class="width100" src="{{asset('public/assets/images/gallery/1.jpg')}}" alt="Awesome Image">
                                                <img class="width100" src="{{asset('public/assets/images/gallery/2.jpg')}}" alt="Awesome Image">
                                            </div>
                                            <a href="javascript:void(0);" class="mr-20 text-muted"><i class="fa fa-heart text-pink"></i> 23 Love</a>
                                            <a class="text-muted" role="button" data-toggle="collapse" href="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1"><i class="fa fa-comments"></i> 2 Comment</a>
                                            <div class="collapse p-4 section-gray" id="collapseExample1">
                                                <form class="well">
                                                    <div class="form-group">
                                                        <textarea rows="2" class="form-control no-resize" placeholder="Enter here for tweet..."></textarea>
                                                    </div>
                                                    <button class="btn btn-primary">Submit</button>
                                                </form>
                                                <ul class="recent_comments list-unstyled mt-4 mb-0">
                                                    <li>
                                                        <div class="avatar_img">
                                                            <img class="rounded img-fluid" src="{{asset('public/assets/images/xs/avatar4.jpg')}}" alt="">
                                                        </div>
                                                        <div class="comment_body">
                                                            <h6>Donald Gardner <small class="float-right font-14">Just now</small></h6>
                                                            <p>Lorem ipsum Veniam aliquip culpa laboris minim tempor</p>
                                                            <div class="timeline_img mb-20">
                                                                <img class="width150" src="{{asset('public/assets/images/gallery/7.jpg')}}" alt="Awesome Image">
                                                                <img class="width150" src="{{asset('public/assets/images/gallery/8.jpg')}}" alt="Awesome Image">
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="avatar_img">
                                                            <img class="rounded img-fluid" src="{{asset('public/assets/images/xs/avatar3.jpg')}}" alt="">
                                                        </div>
                                                        <div class="comment_body">
                                                            <h6>Dessie Parks <small class="float-right font-14">1min ago</small></h6>
                                                            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking</p>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="timeline_item ">
                                        <img class="tl_avatar" src="{{asset('public/assets/images/xs/avatar7.jpg')}}" alt="">
                                        <span><a href="javascript:void(0);" title="">Rochelle Barton</a> San Francisco, CA <small class="float-right text-right">12-April-2019</small></span>
                                        <h6 class="font600">An Engineer Explains Why You Should Always Order the Larger Pizza</h6>
                                        <div class="msg">
                                            <p>I'm speaking with myself, number one, because I have a very good brain and I've said a lot of things. I write the best placeholder text, and I'm the biggest developer on the web by far... While that's mock-ups and this is politics, is the Lorem card.</p>
                                            <a href="javascript:void(0);" class="mr-20 text-muted"><i class="fa fa-heart text-pink"></i> 7 Love</a>
                                            <a class="text-muted" role="button" data-toggle="collapse" href="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2"><i class="fa fa-comments"></i> 1 Comment</a>
                                            <div class="collapse p-4 section-gray" id="collapseExample2">
                                                <form class="well">
                                                    <div class="form-group">
                                                        <textarea rows="2" class="form-control no-resize" placeholder="Enter here for tweet..."></textarea>
                                                    </div>
                                                    <button class="btn btn-primary">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade active show" id="pills-blog" role="tabpanel" aria-labelledby="pills-blog-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="new_post">
                                        <div class="form-group">
                                            <textarea rows="4" class="form-control no-resize" placeholder="Please type what you want..."></textarea>
                                        </div>
                                        <div class="mt-4 text-right">
                                            <button class="btn btn-warning"><i class="fa fa-link"></i></button>
                                            <button class="btn btn-warning"><i class="fa fa-camera"></i></button>
                                            <button class="btn btn-primary">Post</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card blog_single_post">
                                <div class="img-post">
                                    <img class="d-block img-fluid" src="{{asset('public/assets/images/gallery/6.jpg')}}" alt="First slide">
                                </div>
                                <div class="card-body">
                                    <h4><a href="#">All photographs are accurate</a></h4>
                                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal</p>
                                </div>
                                <div class="footer">
                                    <div class="actions">
                                        <a href="javascript:void(0);" class="btn btn-outline-secondary">Continue Reading</a>
                                    </div>
                                    <ul class="stats list-unstyled">
                                        <li><a href="javascript:void(0);">General</a></li>
                                        <li><a href="javascript:void(0);" class="fa fa-heart"> 28</a></li>
                                        <li><a href="javascript:void(0);" class="fa fa-comment"> 128</a></li>
                                    </ul>
                                </div>
                                <ul class="list-group card-list-group">
                                    <li class="list-group-item py-5">
                                        <div class="media">
                                            <img class="media-object avatar avatar-md mr-4" src="{{asset('public/assets/images/xs/avatar3.jpg')}}" alt="">
                                            <div class="media-body">
                                                <div class="media-heading">
                                                    <small class="float-right text-muted">4 min</small>
                                                    <h5>Peter Richards</h5>
                                                </div>
                                                <div>
                                                    Aenean lacinia bibendum nulla sed consectetur. Vestibulum id ligula porta felis euismod semper. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras
                                                    justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Cum sociis natoque penatibus et magnis dis parturient montes,
                                                    nascetur ridiculus mus.
                                                </div>
                                                <ul class="media-list">
                                                    <li class="media mt-4">
                                                        <img class="media-object avatar mr-4" src="{{asset('public/assets/images/xs/avatar1.jpg')}}" alt="">
                                                        <div class="media-body">
                                                            <strong>Debra Beck: </strong>
                                                            Donec id elit non mi porta gravida at eget metus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec ullamcorper nulla non metus
                                                            auctor fringilla. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Sed posuere consectetur est at lobortis.
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card blog_single_post">
                                <div class="img-post">
                                    <img class="d-block img-fluid" src="{{asset('public/assets/images/gallery/4.jpg' )}}" alt="First slide">
                                </div>
                                <div class="card-body">
                                    <h4><a href="#">All photographs are accurate</a></h4>
                                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal</p>
                                </div>
                                <div class="footer">
                                    <div class="actions">
                                        <a href="javascript:void(0);" class="btn btn-outline-secondary">Continue Reading</a>
                                    </div>
                                    <ul class="stats list-unstyled">
                                        <li><a href="javascript:void(0);">General</a></li>
                                        <li><a href="javascript:void(0);" class="fa fa-heart"> 28</a></li>
                                        <li><a href="javascript:void(0);" class="fa fa-comment"> 128</a></li>
                                    </ul>
                                </div>
                                <ul class="list-group card-list-group">
                                    <li class="list-group-item py-5">
                                        <div class="media">
                                            <img class="media-object avatar avatar-md mr-4" src="{{asset('public/assets/images/xs/avatar7.jpg')}}" alt="">
                                            <div class="media-body">
                                                <div class="media-heading">
                                                    <small class="float-right text-muted">12 min</small>
                                                    <h5>Peter Richards</h5>
                                                </div>
                                                <div>
                                                    Donec id elit non mi porta gravida at eget metus. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Cum sociis natoque penatibus et magnis dis
                                                    parturient montes, nascetur ridiculus mus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item py-5">
                                        <div class="media">
                                            <img class="media-object avatar avatar-md mr-4" src="{{asset('public/assets/images/xs/avatar6.jpg')}}" alt="">
                                            <div class="media-body">
                                                <div class="media-heading">
                                                    <small class="float-right text-muted">34 min</small>
                                                    <h5>Peter Richards</h5>
                                                </div>
                                                <div>
                                                    Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Aenean eu leo quam. Pellentesque ornare sem lacinia quam
                                                    venenatis vestibulum. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.
                                                </div>
                                                <ul class="media-list">
                                                    <li class="media mt-4">
                                                        <img class="media-object avatar mr-4" src="{{asset('public/assets/images/xs/avatar5.jpg')}}" alt="">
                                                        <div class="media-body">
                                                            <strong>Wayne Holland: </strong>
                                                            Donec id elit non mi porta gravida at eget metus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec ullamcorper nulla non metus
                                                            auctor fringilla. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Sed posuere consectetur est at lobortis.
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

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