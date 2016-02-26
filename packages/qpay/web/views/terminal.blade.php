<?php
$title = 'QPay Payment Terminal';
?>

@extends('web::layout')
@section('content')
    @include('web::transaction-form')
    @include('web::transaction-list')
@endsection