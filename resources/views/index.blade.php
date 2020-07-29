@extends('layouts.app')

@section('content')
    @dump($statistic)
    {{ csrf_field() }}
    <div class="container-fluid">
        <div class="row">
            <div class="col-3">
                <div>Balance(current)</div>
                <div><span class="font-weight-bold">$</span></div>
            </div>
            <div class="col-3">Orders</div>
            <div class="col-3">Shipments</div>
            <div class="col-3">Turnover</div>
        </div>
    </div>
@endsection
