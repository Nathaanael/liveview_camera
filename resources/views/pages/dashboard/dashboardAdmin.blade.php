@extends('layouts.app')

@section('content')
    <x-main.dashboard.greeting-card :name="auth()->user()->name" />
@endsection
