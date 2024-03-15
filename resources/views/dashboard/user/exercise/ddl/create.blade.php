@extends('dashboard.user.layouts.app')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Latihan Soal</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ url()->previous() }}">Latihan</a>
            </div>
            <div class="breadcrumb-item">Latihan Soal</div>
        </div>
    </div>
    <div class="section-body">
        @if($exercise->ddl_type=='create table')
            @livewire('d-d-l.create-table', ['exercise' => $exercise] )
        @elseif($exercise->ddl_type=='drop table')
            @livewire('d-d-l.drop-table', ['exercise' => $exercise] )
        @elseif($exercise->ddl_type=='alter add column')
            @livewire('d-d-l.alter-add-column', ['exercise' => $exercise] )
        @elseif($exercise->ddl_type=='alter rename column')
            @livewire('d-d-l.alter-rename-column', ['exercise' => $exercise] )
        @elseif($exercise->ddl_type=='alter modify column')
            @livewire('d-d-l.alter-modify-data-type-column', ['exercise' => $exercise] )
        @elseif($exercise->ddl_type=='alter drop column')
            @livewire('d-d-l.alter-drop-column', ['exercise' => $exercise] )
        @else
        @endif
    </div>
</section>
@endsection

@push('customStyle')
<link rel="stylesheet" href="{{ asset('assets/css/mdb.min.css') }}">
<style>
    .navbar {
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }

    table td:hover,
    table th:hover {
        background-color: rgba(0, 0, 0, .075);
    }
</style>
@endpush

@push('customScript')
<link rel="stylesheet" href="{{ asset('assets/js/mdb.min.js') }}">
@endpush