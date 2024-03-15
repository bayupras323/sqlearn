<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} </title>
    <!-- CSS Libraries -->
    @stack('customStyle')

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Template CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css">

    <!-- parsosns library -->
    <link href="{{ asset('lib/parsons-problem/parsons.css')}}" rel="stylesheet" />
    <link href="{{ asset('lib/parsons-problem/lib/prettify.css')}}" rel="stylesheet" />
    <script src="{{ asset('lib/parsons-problem/lib/prettify.js')}}"></script>

</head>

<body class="sidebar-mini">
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar" style="left: 1rem !important;">
                <a href="#" class="navbar-brand sidebar-gone-hide" style="width: 8%">
                    <img src="{{ asset('assets/img/logo.png') }}" width="100%" />
                </a>
                <div class="nav-collapse">
                    <a class="sidebar-gone-show nav-collapse-toggle nav-link" href="#">
                        <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <ul class="navbar-nav">
                        <li class="nav-item {{ Request::segment(1) === 'home' ? 'active' : '' }}"><a href="{{ route('dashboard') }}" class="nav-link">Beranda</a></li>
                    </ul>
                </div>
                <form class="form-inline ml-auto">
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user" data-id="nav-greetings">
                            <img alt="image" src="/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block" data-id="greetings">Hi, {{ auth()->user()->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item has-icon" data-id="navbar_profile_item">
                                <i class="far fa-user"></i> Profil
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="dropdown-item has-icon text-danger" data-id="logout">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <div class="main-content" style="padding-left: 30px !important;">
                @yield('content')
            </div>
            <footer class="main-footer" style="padding-left: 30px !important;">
                @include('dashboard.user.layouts.footer')
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
    <script src="/assets/js/stisla.js"></script>
    <!-- parsosn problem lib --> 
   
<!-- jQuery UI -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- jQuery UI Touch Punch -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script src="{{ asset('lib/parsons-problem/lib/underscore-min.js')}}"></script>
    <script src="{{ asset('lib/parsons-problem/lib/lis.js')}}"></script>
    <script src="{{ asset('lib/parsons-problem/parsons.js')}}"></script>
    <script src="{{ asset('lib/parsons-problem/lib/skulpt.js')}}"></script>
    <script src="{{ asset('lib/parsons-problem/lib/skulpt-stdlib.js')}}"></script>

    <!-- Template JS File -->
    <script src="/assets/js/scripts.js"></script>
    <script src="/assets/js/custom.js"></script>

</body>

@livewireScripts
@stack('customScript')

</html>
