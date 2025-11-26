@extends('layouts.app')

@section('title', __('app.why_us.title') . ' | ' . __('app.meta.title'))
@section('body_class', 'page why-us-page')

@section('content')
    @include('sections.why_us')
@endsection
