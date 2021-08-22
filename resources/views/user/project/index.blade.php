@extends('user.layouts.app')
@section('user_title', 'User Dashboard')
@section('userPage_heading','Project List')
@section('user_css')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}"/>
@endsection
@section('user_content')

    <project-component 
        :ongoing_projects = "{{$ongoing_projects}}" 
        :upcoming_projects = "{{$upcoming_projects}}" 
        :user_id = "{{auth()->user()->id}}" :domain="'{{asset('')}}'"
        :user_role="{{auth()->user()->role}}" />

@endsection

@section('user_js')
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/bundles/sweetalert.bundle.js')}}"></script>
    <script src="{{asset('assets/js/page/sweetalert.js')}}"></script>
@endsection
