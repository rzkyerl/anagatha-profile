@extends('layouts.app')

@section('title', __('app.services.title') . ' | ' . __('app.meta.title'))
@section('body_class', 'page service-page')

@section('content')
    @include('sections.service')
@endsection
