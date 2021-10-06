@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Audition Segments') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div>
                        @foreach($filecontenttypes AS $filecontenttype)
                            {{ $filecontenttype->descr }}
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
