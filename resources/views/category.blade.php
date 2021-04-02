@extends('layouts.mainLayout')

@section('title')
  {{ $title  }}
@endsection

@section('content')
  <div class="header">@include('parts.header')</div>

  <div class="main-content__body">
    <div class="container">
      <div>Категория {{$slug}}</div>
      <div class="product-list">
        @foreach ($products as $product)
         <div>
           <h3> {{ $product->name }}</h3>
         </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="main-footer">@include('parts.footer')</div>

@endsection
