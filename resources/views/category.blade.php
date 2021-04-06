@extends('layouts.mainLayout')

@section('title')
  {{ $category[0]->name  }}
@endsection

@section('content')
  <div class="header">@include('parts.header')</div>

  <div class="main-content__body">
    <div class="container">
      <h1>{{$category[0]->name}}</h1>
      <component is="product-list" category="{{ $category[0]->id }}"/>
      {{--<div class="product-list">--}}
        {{--@foreach ($products as $product)--}}
         {{--<div>--}}
           {{--<h3> {{ $product->name }}</h3>--}}
         {{--</div>--}}
        {{--@endforeach--}}
      {{--</div>--}}
    </div>
  </div>

  <div class="main-footer">@include('parts.footer')</div>

@endsection
