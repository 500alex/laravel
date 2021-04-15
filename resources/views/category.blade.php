@extends('layouts.mainLayout')

@section('title')
  {{ $category[0]->name  }}
@endsection

@section('content')
  <div class="header">@include('parts.header')</div>

  <div class="main-content-inner container">
    <div class="body-content">
      <div class="body-content__left">Блок фильтров</div>
      <div class="body-content__right">
        <div class="body-head">
          <div class="breadcrumbs">
            Главная - Мелкая кухонная техника - Мясорубки
          </div>
          <h1>{{$category[0]->name}}</h1>
        </div>
        <component is="product-list" category="{{ $category[0]->id }}"/>
      </div>
    </div>
  </div>

  <div class="main-footer">@include('parts.footer')</div>

@endsection
