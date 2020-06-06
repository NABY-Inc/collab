@extends('admin.layouts.app')
@section('admin_title', 'Admin Dashboard')
@section('adminPage_heading','System Users')
@section('admin_css')
    <link rel="stylesheet" href="{{asset('public/assets/plugins/sweetalert/sweetalert.css')}}"/>
    <link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}">
@endsection
@section('admin_content')
    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-md-flex justify-content-between">
                                <ul class="nav nav-tabs b-none">
                                    <li class="nav-item"><a class="nav-link active" id="list-tab" data-toggle="tab" href="#list"><i class="fa fa-list-ul"></i> List</a></li>
                                    <li class="nav-item"><a class="nav-link" id="grid-tab" data-toggle="tab" href="#grid"><i class="fa fa-th"></i> Grid</a></li>
                                    <li class="nav-item"><a class="nav-link" id="addnew-tab" data-toggle="tab" href="#addnew"><i class="fa fa-plus"></i> Add New</a></li>
                                </ul>
                                <div class="d-flex align-items-center sort_stat">
                                    <div class="d-flex">
                                        <div class="ml-2">
                                            <p class="mb-0 font-11">Total Users</p>
                                            <h5 class="font-16 mb-0 text-center">{{count($systemUsers)}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mt-2">
                                <input type="text" class="form-control search" placeholder="Search...">
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
                <div class="tab-pane fade show active" id="list" role="tabpanel">
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="table-responsive" id="users">
                                <table class="table table-hover table-vcenter text-nowrap table_custom border-style list">
                                    <tbody>
                                        @if(count($systemUsers) > 0)
                                            @foreach($systemUsers as $user)
                                                <tr class="">
                                                    <td class="width35 hidden-xs">
                                                        <a href="javascript:void(0);" class="@if($user->active == 0) mail-star @endif"><i class="fa fa-star"></i></a>
                                                    </td>
                                                    <td class="text-center width40">
                                                        <div class="avatar d-block">
                                                            <img class="avatar" src="{{asset('public/assets/images/xs/avatar4.jpg')}}" alt="avatar">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div><a href="javascript:void(0);">{{$user->name}}</a></div>
                                                        <div class="text-muted">PCS00{{$user->id}}</div>
                                                    </td>
                                                    <td class="hidden-xs">
                                                        <div class="text-muted">{{$user->email}}</div>
                                                    </td>
                                                    <td class="hidden-sm">
                                                        <div class="text-muted">{{$user->contact}}</div>
                                                    </td>
                                                    <td class="text-right actions">
                                                        <a class="btn btn-sm btn-link edit" href="javascript:void(0)" data-toggle="tooltip" title="" data-original-title="Edit" data-url="{{route('systemUsers.edit',$user->id)}}" data-id="{{$user->id}}"><i class="fa fa-edit"></i></a>
                                                        <a class="btn btn-sm btn-link hidden-xs deactivate" data-type="confirm" href="javascript:void(0)" data-toggle="tooltip" data-activate-url = "{{route('toggleActive',$user->id)}}" data-active-data="{{$user->active}}" data-original-title="@if($user->active == 1) Deactivate @else Activate @endif"><i class="fa fa-ban"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                {!! $systemUsers->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="grid" role="tabpanel">
                    <div class="row row-deck">
                        @if (count($systemUsers) > 0)
                            @foreach($systemUsers as $user)
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="card ">
                                        <div class="card-body">
                                            <div class="card-status bg-blue"></div>
                                            <div class="mb-3"> <img src="{{asset('public/assets/images/sm/avatar1.jpg')}}" class="rounded-circle w100" alt=""> </div>
                                            <div class="mb-2">
                                                <h5 class="mb-0">{{$user->name}}</h5>
                                                <p class="text-muted">{{$user->email}}</p>
                                                <span>Has been a member since {{$user->created_at->toFormattedDateString()}}</span>
                                            </div>
                                            <span class="font-12 text-muted">USER ID</span>
                                            <ul class="list-unstyled team-info margin-0 pt-2">
                                                <li>PCS00{{$user->id}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    {!! $systemUsers->links() !!}
                </div>
                <div class="tab-pane fade" id="addnew" role="tabpanel">
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{route('systemUsers.store')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row clearfix">
                                            <div class="col-lg-6 col-md-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Enter Name" name="name" required autofocus autocomplete>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" placeholder="Enter Number" name="contact" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" placeholder="Enter Email" name="email" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <div class="form-group">
                                                    <select class="form-control" name="role" required>
                                                        <option value="2">User</option>
                                                        <option value="1">Administrator</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="file" class="dropify" name="image">
                                            </div>
                                            <div class="col-lg-12 mt-3">
                                                <button type="submit" class="btn btn-primary">Add</button>
                                                <button type="submit" class="btn btn-default">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MOdal for editing --}}
    <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="title" id="defaultModalLabel">Update user profile</h6>
                </div>
                <form id="updateUser" method="GET">
                    @csrf
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="text" id="username" class="form-control" placeholder="username" name="name" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="number" id="contact" class="form-control" placeholder="Contact" name="contact" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="email" id="email" class="form-control" placeholder="Email" name="email" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <select class="form-control show-tick" name="role" id="user_role" required>
                                        <option value="2">User</option>
                                        <option value="1">Administrator</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Reset Password</label>
                                    <select class="form-control show-tick" name="resetPass" id="user_role" required>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label>Image</label>
                                <input type="file" class="dropify" name="image">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" id="userID">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('admin_js')

    <script src="{{asset('public/assets/bundles/sweetalert.bundle.js')}}"></script>
    <script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset('public/assets/js/page/sweetalert.js')}}"></script>
    <script>
        $(function() {
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

            $('.actions .deactivate').click(function(){
                var url = $(this).data('activate-url');
                var active = $(this).data('active-data');
                if (active == 1) {
                    var text = "User is active. Are you sure you want to deactivate ?";
                    var btnText = "Yes, deactivate!";
                    var btnColor = "#dc3545";
                }else{
                    var text = "User is deactive. Are you sure you want to activate ?";
                    var btnText = "Yes, activate!";
                    var btnColor = "#21ba45";
                }
                swal({
                    title: "Warning!",
                    text: text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: btnColor,
                    confirmButtonText: btnText,
                    closeOnConfirm: false
                }, function () {
                    $.ajax({
                    url: url,
                    method: 'GET',
                    success:function(response){
                    swal({
                        title:"Success!",
                        text: "User Active mode changed.",
                        type: "success",
                    }, function(){
                        location.reload();
                    });
                    }
                });
                });
            });

            $('.actions .edit').click(function(){
                var id = $(this).data('id');
                var url = $(this).data('url');
                $.ajax({
                    url: url,
                    method: 'GET',
                    success:function(response){
                        $("#username").val(response.name);
                        $("#contact").val(response.contact);
                        $("#email").val(response.email);
                        $("#userID").val(response.id);
                        $("#user_role").val(response.role).change();
                        $('#updateUser').attr("action", "{{route('systemUser.updateNew')}}");
                        $('#editUser').modal('show');
                    }
                });
            });

            @if($success == 1)
                swal("Success!", "User created Successfully!", "success");
            @elseif($success == 2)
                swal("Success!", "User updated Successfully!", "success");
            @elseif($error)
                swal("Error!", "Something went Wrong!", "error");
            @endif
        });
    </script>

@endsection