@extends('layout.default')
@section('title', 'Contacto')
@section('css', '/css/acadManu_contacto.css')
@section('content')
<!-- include contacto -->
@include('layout.contacto') 
<!-- fin include contacto -->
@stop
@section('js', 'http://maps.googleapis.com/maps/api/js')
@section('js1', '/js/home.js')