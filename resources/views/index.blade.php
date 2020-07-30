@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('content')
    {{--    @dump($statistic)--}}
    {{--    {{ csrf_field() }}--}}
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-12 col-sm-12 col-mb-12 col-lg-3 col-xl-3">
                <div class="p-3 dashboard-border-success rounded-left dashboard-boxshadow">
                    <div class="font-weight-bold text-success">Balance(current)</div>
                    <div class="mt-3">
                        <span class="h1">${{ $statistic['balance'] }}</span>
                        <i class="fa fa-money float-right fa-4x text-secondary opacity-image" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-mb-12 col-lg-3 col-xl-3">
                <div class="p-3 dashboard-border-primary rounded-left dashboard-boxshadow">
                    <div class="font-weight-bold text-primary">Orders</div>
                    <div class="mt-3">
                        <span class="h1">{{ $statistic['orders'] }}</span>
                        <i class="fa fa-shopping-basket float-right fa-4x text-secondary opacity-image"
                           aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-mb-12 col-lg-3 col-xl-3">
                <div class="p-3 dashboard-border-danger rounded-left dashboard-boxshadow">
                    <div class="font-weight-bold text-danger">Shipments</div>
                    <div class="mt-3">
                        <span class="h1">{{ $statistic['shipments'] }}</span>
                        <i class="fa fa-truck float-right fa-4x text-secondary opacity-image" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-mb-12 col-lg-3 col-xl-3">
                <div class="p-3 dashboard-border-info rounded-left dashboard-boxshadow">
                    <div class="font-weight-bold text-info">Turnover</div>
                    <div class="mt-3">
                        <span class="h1">${{ $statistic['turnover'] }}</span>
                        <i class="fa fa-refresh float-right fa-4x text-secondary opacity-image" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5 ml-1 filter-display">
            <input type="text" class="form-control date-range" id="date-range" placeholder="Select date">
            <button type="button" class="btn btn-dark ml-2" id="button-filter">Filter</button>
        </div>

        <div class="container-fluid">
            <div class="row chart-width">
                <div class="col-md-6 col-md-offset-2">
                    <div id="chart"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div id="curve_chart" style="width: 100%; height: 500px"></div>
            </div>

            <div class="col-12 col-md-6">
                <div id="curve_chart_balance" style="width: 100%; height: 500px"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="{{ route('inbound-shipments') }}" class="badge badge-dark text-full-size"><i class="fa fa-list-alt" aria-hidden="true"></i> Inbound shipments</a>
                <a href="{{ route('products') }}" class="badge badge-dark text-full-size"><i class="fa fa-cube" aria-hidden="true"></i> Products</a>
                <a href="{{ route('orders') }}" class="badge badge-dark text-full-size"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Orders</a>
                @if(\Illuminate\Support\Facades\Auth::user())
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                        <a href="{{ route('users') }}" class="badge badge-dark text-full-size"><i class="fa fa-users" aria-hidden="true"></i> Users</a>
                    @endif
                @endif
            </div>
        </div>

        @endsection

        @section('scripts')
            <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('js/daterangepicker.js') }}"></script>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script src="{{ asset('js/index.js') }}" defer></script>

        @endsection

        @section('style')
            <link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker-bs3.css') }}"/>
@endsection
