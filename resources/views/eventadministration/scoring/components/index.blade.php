@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __("Event Administration: $eventversion->name Scoring Components") }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem;">
                        <div style="display: flex; flex-direction: row; ">
                            {{-- FORM --}}
                            @if(config('app.url') === 'http://afdc2021.test')
                                <form method="post" action="@if($scoringcomponent)
                                    {{ route('eventadministrator.scoring.components.update',['eventversion' => $eventversion, 'scoringcomponent' => $scoringcomponent->id]) }}
                                @else
                                    {{ route('eventadministrator.scoring.components.new',['eventversion' => $eventversion]) }}
                                @endif
                                ">
                            @else
                                @if($scoringcomponent)
                                    <form method="post" action="https://afdc-2021-l38q8.ondigitalocean.app/eventadministrator/scoring/components/update/{{ $eventversion->id }}/{{ $scoringcomponent->id }} ">
                                @else
                                    <form method="post" action="https://afdc-2021-l38q8.ondigitalocean.app/eventadministrator/scoring/components/new/{{ $eventversion->id }} @endif ">
                                @endif
                            @endif
                                @csrf

                                    <style>
                                        label{width: 6rem;}
                                        .input-group{display:flex; flex-direction: row; margin-bottom: .5rem;}
                                    </style>

                                {{-- SEGMENT SELECT  --}}
                                <div class="input-group">
                                    <label for="filecontenttype_id">Segment</label>
                                    <select name="filecontenttype_id" style="width: 10rem;">
                                        @foreach($currentfilecontenttypes AS $filecontenttype)
                                            <option value="{{ $filecontenttype->id }}">{{ $filecontenttype->descr }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- COMPONENT NAME --}}
                                <div class="input-group">
                                    <label for="descr">Name</label>
                                    <input type="text" name="descr" id="descr" value="{{ ($scoringcomponent) ? $scoringcomponent->descr : '' }}" />
                                </div>

                                <div class="input-group">
                                    <label for="descr">Abbreviation</label>
                                    <input type="text" name="abbr" id="abbr" value="{{ ($scoringcomponent) ? $scoringcomponent->abbr : '' }}" />
                                </div>

                                {{-- ORDER BY --}}
                                <div class="input-group">
                                    <label for="order_by">Order</label>
                                    <select name="order_by">
                                        @for($i=1;$i<25;$i++)
                                            <option value="{{ $i }}"
                                            @if($scoringcomponent && ($scoringcomponent->order_by === $i)) SELECTED @endif
                                            >
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                {{-- COMPONENT VALUES --}}
                                <h5>Scoring Range</h5>
                                <div class="input-group">
                                    <label for="bestscore">Best Score</label>
                                    <select name="bestscore">
                                        @for($i=0;$i<21;$i++)
                                            <option value="{{ $i }}"
                                                @if($scoringcomponent && ($scoringcomponent->bestscore === $i)) SELECTED @endif
                                            >{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label for="worstscore">Worst Score</label>
                                    <select name="worstscore">
                                        @for($i=0;$i<21;$i++)
                                            <option value="{{ $i }}"
                                                    @if($scoringcomponent && ($scoringcomponent->worstscore === $i)) SELECTED @endif
                                            >{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label for="interval">Interval</label>
                                    <select name="interval">
                                        @for($i=1;$i<21;$i++)
                                            <option value="{{ $i }}"
                                                    @if($scoringcomponent && ($scoringcomponent->interval === $i)) SELECTED @endif
                                            >{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label for="tolerance">Tolerance</label>
                                    <select name="tolerance">
                                        @for($i=0;$i<21;$i++)
                                            <option value="{{ $i }}"
                                                @if($scoringcomponent && ($scoringcomponent->tolerance === $i)) SELECTED @endif
                                            >{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="input-group flex flex-column">
                                    <input type="submit" name="submit" id="submit" value="Update Component" />
                                    @if($scoringcomponent)
                                        @if(config('app.url') === 'http://afdc2021.test')
                                            <a href="{{ route('eventadministrator.scoring.components.delete', ['scoringcomponent' => $scoringcomponent->id]) }}" style="color: red;" class="text-red-500">- Delete {{ $scoringcomponent->descr }} -</a>
                                        @else
                                            <a href="https://afdc-2021-l38q8.ondigitalocean.app/eventadminstrator/scoring/components/delete/{{ $scoringcomponent->id }}" style="color: red;" class="text-red-500">- Delete {{ $scoringcomponent->descr }} -</a>
                                        @endif

                                    @endif
                                </div>
                            </form>

                            {{-- TABLE --}}
                            <div style="margin-left: 1rem;">
                                <x-eventadministration.scorings.tablecomponents
                                    :scoringcomponents="$scoringcomponents"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
