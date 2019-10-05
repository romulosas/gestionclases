@extends('layouts.app')

@section('section-content-totals')
    @include('home.partials.dashboard.admin.content-totals')
@endsection

@section('section-content-chart')
    @include('home.partials.dashboard.admin.content-chart')
@endsection

@section('section-content-info-user-datatable')
    @include('home.partials.dashboard.admin.content-info-user-datatable')
@endsection

@section('section-content-info-user-workprogress')
    @include('home.partials.dashboard.admin.content-info-user-workprogress')
@endsection