@extends('layouts.smartcity')

@section('title', 'Beranda - SmartCity.id')

@section('hero-background')
    @include('pages.smartcity.sections.hero')
@endsection

@section('content')
    @include('pages.smartcity.sections.smart')
    @include('pages.smartcity.sections.edukasi')
    @include('pages.smartcity.sections.faq')
@endsection