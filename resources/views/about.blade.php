@extends('layouts.app')

@section('title', __('app.about.title') . ' | ' . __('app.meta.title'))
@section('body_class', 'page about-page')

@section('content')
    @include('sections.about')
@endsection