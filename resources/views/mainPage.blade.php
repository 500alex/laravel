@extends('layouts.mainLayout')

@section('title')
    {{ $title  }}
@endsection

@section('content')
    <div class="header">@include('parts.header')</div>

    <div class="main-content__body">
        <div class="container">
            <div>Hello world!</div>
        </div>
    </div>

    <div class="main-footer">@include('parts.footer')</div>

@endsection


