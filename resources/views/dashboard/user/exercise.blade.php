@extends('dashboard.user.layouts.app')
@section('content')
    @php
        $questionNumber = (int) request()->has('question') ? request()->query('question') : '1';
        $percentage = (($questionNumber - 1) / $totalExercise) * 100;
    @endphp

    <section class="section">
        <div class="section-header">
            <h1>{{ $schedule->name }}</h1>
            <div class="section-header-breadcrumb">
                <div class="bredcrumb-item">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            Soal {{ $questionNumber }} dari {{ $totalExercise }}
                        </div>
                        <div class="progress" data-height="15" data-width="150">
                            <div role="progressbar" style="width: {{ $percentage }}%" aria-valuemax="{{ $totalExercise }}"
                                class="progress-bar bg-primary">
                                {{ $percentage }}% complete
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body">
            @foreach ($exercises as $exercise)
                @switch($schedule->package->topic_id)
                    @case(1)
                        <livewire:erd.entity-attribute :exercise="$exercise" :totalexercise="$totalExercise" :nextQuestionUrl="$exercises->nextPageUrl()"
                        :questionNumber="$questionNumber" :schedule="$schedule"  :nextQuestionUrl="$exercises->nextPageUrl()">
                        @break
                    @case(2)
                        <livewire:erd.relationship.relationship :exercise="$exercise" :totalexercise="$totalExercise"
                        :questionNumber="$questionNumber" :schedule="$schedule"  :nextQuestionUrl="$exercises->nextPageUrl()">
                        @break
                    @case(3)
                        <livewire:select.select :exercise="$exercise" :currentPage="$exercises->currentPage()" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()">
                        @break
                    @case(4)
                        <livewire:aggregation.aggregation :exercise="$exercise" :currentPage="$exercises->currentPage()" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()">
                        @break
                    @case(5)
                        <livewire:inner-outer-join.inner-outer-join :exercise="$exercise" :currentPage="$exercises->currentPage()" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()">
                        @break
                    @case(6)
                        <livewire:union-cross-join.union-cross-join :exercise="$exercise" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()">
                        @break
                    @case(7)
                        <livewire:subquery.subquery :exercise="$exercise" :currentPage="$exercises->currentPage()" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()" :nextQuestionUrl="$exercises->nextPageUrl()">
                        @break
                    @case(8)
                        @if($exercise->ddl_type=='create table')
                            <livewire:d-d-l.create-table :exercise="$exercise" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()" :totalExercise="$totalExercise" :scheduleId="$schedule->id">
                        @elseif($exercise->ddl_type=='drop table')
                            <livewire:d-d-l.drop-table :exercise="$exercise" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()" :totalExercise="$totalExercise" :scheduleId="$schedule->id">
                        @elseif($exercise->ddl_type=='alter add column')
                            <livewire:d-d-l.alter-add-column :exercise="$exercise" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()" :totalExercise="$totalExercise" :scheduleId="$schedule->id">
                        @elseif($exercise->ddl_type=='alter rename column')
                            <livewire:d-d-l.alter-rename-column :exercise="$exercise" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()" :totalExercise="$totalExercise" :scheduleId="$schedule->id">
                        @elseif($exercise->ddl_type=='alter modify column')
                            <livewire:d-d-l.alter-modify-data-type-column :exercise="$exercise" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()" :totalExercise="$totalExercise" :scheduleId="$schedule->id">
                        @elseif($exercise->ddl_type=='alter drop column')
                            <livewire:d-d-l.alter-drop-column :exercise="$exercise" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()" :totalExercise="$totalExercise" :scheduleId="$schedule->id">
                        @else
                        @endif
                        @break
                    @case(9)
                          <livewire:subquery.subquery :exercise="$exercise" :currentPage="$exercises->currentPage()" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()" :nextQuestionUrl="$exercises->nextPageUrl()">
                        @break 
                    @case(10)
                        <livewire:parsons-problem.two-dimensions :exercise="$exercise" :currentPage="$exercises->currentPage()" :schedule="$schedule" :nextQuestionUrl="$exercises->nextPageUrl()">
                        @break 
                    @default
                    <p align="center">{{$schedule->package->topic_id}}</p>
                    @break
                @endswitch
            @endforeach
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

        .btn-link {
            color: black;
        }

        .table:not(.table-sm) thead th:hover {
            background-color: rgba(0, 0, 0, .08);
        }

        table td,
        th {
            font-size: 12px !important;
        }

        th {
            font-weight: bold !important;
        }

        .table-sticky th:first-child,
        .table-sticky td:first-child {
            position: sticky;
            left: 0;
            z-index: 999 !important;
        }

        .table-sticky td:first-child {
            background-color: white !important;
        }

        .table-sticky th:first-child {
            background-color: #F5F5F5 !important;
        }
    </style>
@endpush

@push('customScript')
    <script src="{{ asset('assets/js/mdb.min.js') }}"></script>
    {{-- <script src="/assets/js/page/components-table.js"></script> --}}
    <script>
        $(document).ready(function() {
            $(".question p:contains('SELECT'), .question p:contains('DROP'), .question p:contains('ALTER'), .question p:contains('CREATE')")
                .css({
                    "font-weight": "600",
                    "font-size": "16px"
                });
        });
        let questionNumber = parseInt('{{$questionNumber}}');
        let totalExercise = parseInt('{{$totalExercise}}');
        if(questionNumber > totalExercise)
        {
             var url = '<?php echo url('exercise')?>'+'/'+'{{$schedule->id}}'+'?question=1';
             window.location.href = url;
        }
    </script>
@endpush
