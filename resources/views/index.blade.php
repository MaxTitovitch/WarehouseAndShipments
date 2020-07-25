@extends('layouts.app')

@section('content')
    @dump($statistic)
    {{ csrf_field() }}
@endsection
