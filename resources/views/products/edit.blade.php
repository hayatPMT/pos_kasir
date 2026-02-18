@extends('layouts.app')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product')

@section('content')
@include('products.form', ['product' => $product])
@endsection