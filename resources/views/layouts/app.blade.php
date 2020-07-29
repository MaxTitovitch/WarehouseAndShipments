<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title')
    </title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    <!-- Bootstrap 4 & jQuery -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
    @yield('style')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-light shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fa fa-sign-in" aria-hidden="true"></i> {{ __('Login') }}
                            </a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fa fa-user-o" aria-hidden="true"></i>  {{ __('Register') }}
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fa fa-user-o" aria-hidden="true"></i> {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu bg-dark dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a id="change-user-data" class="dropdown-item text-light" href="#">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Personal information
                                </a>
                                <a class="dropdown-item text-light" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out" aria-hidden="true"></i> {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>

                        </li>
                        <li class="nav-item dropdown for-mobile"><a class="nav-link" href="{{ route('inbound-shipments') }}">Inbound shipments</a></li>
                        <li class="nav-item dropdown for-mobile"><a class="nav-link" href="{{ route('products') }}">Products</a></li>
                        <li class="nav-item dropdown for-mobile"><a class="nav-link" href="{{ route('orders') }}">Orders</a></li>
                        @if(\Illuminate\Support\Facades\Auth::user())
                            @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                                <li class="nav-item dropdown for-mobile"><a class="nav-link" href="{{ route('users') }}">Users</a></li>
                            @endif
                        @endif
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="layout-flex">
        @section('sidebar')
            <div class="sidenav sidenav-mobile">
                <a class="dashboard ml-1" href="{{ route('home') }}">DASHBOARD</a>
                <hr>
                <a href="{{ route('inbound-shipments') }}"><i class="fa fa-list-alt" aria-hidden="true"></i> Inbound shipments</a>
                <a href="{{ route('products') }}"><i class="fa fa-cube" aria-hidden="true"></i> Products</a>
                <a href="{{ route('orders') }}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Orders</a>
                @if(\Illuminate\Support\Facades\Auth::user())
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                        <a href="{{ route('users') }}"><i class="fa fa-users" aria-hidden="true"></i> Users</a>
                    @endif
                @endif
            </div>
        @show
        <div class="dashboard-table-div">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>


    @yield('modal')

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>

    <script src="https://use.fontawesome.com/9d1af331c5.js"></script>

    <script src="{{ asset('js/datatables.min.js') }}"></script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('scripts')

</body>
</html>
