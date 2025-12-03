@extends('layouts.app')

@section('title', __('app.nav.about') . ' | Anagata Executive')
@section('body_class', 'page about-page')

@section('content')
    @include('sections.about')
@endsection