@extends('layouts.landing')

@section('title', 'Beranda | CerdasSampah.id')

@section('content')
  @include('pages.landing.sections.hero')
  @include('pages.landing.sections.peta')
  @include('pages.landing.sections.scan')
  @include('pages.landing.sections.game')
  @include('pages.landing.sections.edukasi')
  @include('pages.landing.sections.faq')
@endsection
