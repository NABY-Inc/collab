@extends('admin.layouts.app')
@section('admin_title', 'Admin Dashboard')
@section('adminPage_heading','System Users')
@section('admin_css')
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}"/>
@endsection
@section('admin_content')
    <div>
        {{-- Bringing in the users component --}}
        <users-component :allUsers="{{$users}}" :domain="'{{asset('')}}'" />
    </div>

@endsection

@section('admin_js')

<script src="{{asset('assets/bundles/sweetalert.bundle.js')}}"></script>
<script src="{{asset('assets/js/page/sweetalert.js')}}"></script>

@endsection