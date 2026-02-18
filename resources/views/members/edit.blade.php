@extends('layouts.app')

@section('title', 'Edit Member')
@section('page_title', 'Edit Member Details')

@section('content')
@include('members.form', ['member' => $member])
@endsection