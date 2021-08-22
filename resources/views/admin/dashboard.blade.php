@extends('admin.layouts.app')
@section('admin_title', 'Admin Dashboard')
@section('adminPage_heading','Dashboard')
@section('admin_content')

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
                        <h3 class="card-title">System Users</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="number mb-0 font-32 counter">{{count($systemUsers)}}</h5>
                        <span class="font-12">Members of PCS</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">OnGoing Projects</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="number mb-0 font-32 counter">{{$ongoing}}</h5>
                        <span class="font-12">Total Ongoing Projects on PCS</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">UpComing Projects</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="number mb-0 font-32 counter">{{$upcoming}}</h5>
                        <span class="font-12">Total Upcoming Projects on PCS</span>
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
                        <h3 class="card-title">Recent Registered Users</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped text-nowrap table-vcenter mb-0">
                                <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($systemUsers) > 0)
                                    @foreach ($systemUsers as $user)
                                        <tr>
                                            <td>PCS00{{$user->id}}</td>
                                            <td>
                                                <ul class="list-unstyled team-info sm margin-0 w150">
                                                    <li><img src="{{asset('uploads/users/'.$user->profile)}}" alt="Avatar"></li>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <li>{{$user->name}}</li>
                                                </ul>
                                            </td>
                                            <td>{{$user->role == 1 ? 'Administrator' : 'Normal User'}}</td>
                                            <td><span class="tag @if($user->active == 1) tag-danger @else tag-success @endif">{{$user->active == 1 ? 'Active' : 'Inactive'}}</span></td>
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