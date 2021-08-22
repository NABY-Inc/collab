@extends('admin.layouts.app')
@section('admin_title', 'Admin Dashboard - Projects')
@section('adminPage_heading','Project List')
@section('admin_css')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}"/>
    
@endsection
@section('admin_content')

    <show-project-component :active_project="{{$project}}" :user_id="{{auth()->user()->id}}" :user_role="{{auth()->user()->role}}" :domain="'{{asset('')}}'" />

@endsection

@section('admin_js')

    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/bundles/sweetalert.bundle.js')}}"></script>
    <script src="{{asset('assets/js/page/sweetalert.js')}}"></script>
@endsection
