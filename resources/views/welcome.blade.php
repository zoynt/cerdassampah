@extends('layouts.app')

@section('title', 'Beranda | CerdasSampah.id')

@section('content')
    @include('landing.hero')
    @include('landing.peta')
    {{-- @include('scan.scan') --}}
    @include('landing.game')
    @include('landing.edukasi')
    @include('landing.faq')
@endsection
