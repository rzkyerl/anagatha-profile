@extends('layouts.app')

@section('title', __('app.contact.title') . ' | ' . __('app.meta.title'))
@section('body_class', 'page contact-page')

@section('content')
    @include('sections.contact')
@endsection
