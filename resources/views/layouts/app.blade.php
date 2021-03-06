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
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">

</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-light shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand show-menu" href="#">
            <i class="fa fa-align-justify" aria-hidden="true"></i>
        </a>
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
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
                                <i class="fa fa-user-o" aria-hidden="true"></i> {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item main-drop dropdown ">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fa fa-user-o" aria-hidden="true"></i>
                            {{ Auth::user()->name }}
                            <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu bg-dark dropdown-menu-right " aria-labelledby="navbarDropdown">
                            <span class="dropdown-item text-light user-suite disabled">
                               ID: {{ Auth::user()->suite }}
                            </span>
                            <a id="" data-toggle="modal" data-target="#change-user-data"
                               class="dropdown-item text-light" href="#">
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
                    <li class="nav-item dropdown for-mobile"><a class="nav-link"
                                                                href="{{ route('inbound-shipments') }}">Inbound
                            shipments</a></li>
                    <li class="nav-item dropdown for-mobile"><a class="nav-link"
                                                                href="{{ route('products') }}">Products</a></li>
                    <li class="nav-item dropdown for-mobile"><a class="nav-link" href="{{ route('orders') }}">Orders</a>
                    </li>
                    @if(\Illuminate\Support\Facades\Auth::user())
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                            <li class="nav-item dropdown for-mobile"><a class="nav-link" href="{{ route('users') }}">Users</a>
                            </li>
                        @endif
                    @endif
                @endguest
            </ul>
        </div>
    </div>
</nav>
<div class="layout-flex">
    @section('sidebar')
        <div class="sidenav sidenav-mobile bg-dark section-big">
            <a class="dashboard text-white" href="{{ route('home') }}"><i class="fa fa-tachometer mr-1" aria-hidden="true"></i><span class="full-text"> Dashboard</span></a>
            <a href="{{ route('inbound-shipments') }}" class="text-white"><i class="fa fa-list-alt" aria-hidden="true"></i> <span class="full-text">Inbound
                    shipments</span></a>
            <a href="{{ route('products') }}" class="text-white"><i class="fa fa-cube" aria-hidden="true"></i> <span class="full-text">Products</span></a>
            <a href="{{ route('orders') }}" class="text-white"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span class="full-text">Orders</span></a>
            @if(\Illuminate\Support\Facades\Auth::user())
                @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                    <a href="{{ route('users') }}" class="text-white"><i class="fa fa-users" aria-hidden="true"></i> <span class="full-text">Users</span></a>
                @endif
            @endif
        </div>
        <div class="sidenav sidenav-mobile bg-dark section-small">
            <a class="dashboard text-white text-center" href="{{ route('home') }}"><i class="fa fa-tachometer mr-1" aria-hidden="true"></i></a>
            <a href="{{ route('inbound-shipments') }}" class="text-white text-center"><i class="fa fa-list-alt" aria-hidden="true"></i></a>
            <a href="{{ route('products') }}" class="text-white text-center"><i class="fa fa-cube" aria-hidden="true"></i></a>
            <a href="{{ route('orders') }}" class="text-white text-center"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
            @if(\Illuminate\Support\Facades\Auth::user())
                @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                    <a href="{{ route('users') }}" class="text-white text-center"><i class="fa fa-users" aria-hidden="true"></i></a>
                @endif
            @endif
        </div>
    @show
    <div>

    </div>
    <div class="dashboard-table-div">
        @yield('content')
    </div>
</div>

@if(\Illuminate\Support\Facades\Auth::user() != null)
    <div class="modal fade" data-backdrop="static" id="change-user-data" tabindex="-1" role="dialog"
         aria-labelledby="modalAddLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabelProduct">Change user data</h5>
                    <button type="button" class="close close-modal-personal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="personal-id" class="display-none">{{ \Illuminate\Support\Facades\Auth::id() }}</span>
                    <form class="form-submit" id="updateUserData">
                        @csrf
                        <div class="form-group">
                            <label for="personal-name" class="font-weight-bold">Name</label>
                            <input type="text" class="form-control" required id="personal-name" maxlength="255"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Name" value="{{ \Illuminate\Support\Facades\Auth::user()->name }}">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="personal-email" class="font-weight-bold">Email</label>
                            <input type="text" class="form-control" required id="personal-email" maxlength="255"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Email" value="{{ \Illuminate\Support\Facades\Auth::user()->email }}">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <button type="submit" class="btn btn-dark btn-block">Change</button>
                    </form>

                    <form class="form-submit mt-5" id="changePassword">
                        @csrf
                        <div class="form-group">
                            <label for="personal-last_password" class="font-weight-bold">Previous password</label>
                            <input type="password" class="form-control" required id="personal-last_password"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Previous password" minlength="8" maxlength="255">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="personal-password" class="font-weight-bold">Password</label>
                            <input type="password" class="form-control" required id="personal-password"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Password" minlength="8" maxlength="255">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="personal-password_confirmation" class="font-weight-bold">Confirm password</label>
                            <input type="password" class="form-control" required id="personal-password_confirmation"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Confirm password" minlength="8"  maxlength="255">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <button type="submit" class="btn btn-dark btn-block">Change password</button>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary close-modal-personal" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

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
