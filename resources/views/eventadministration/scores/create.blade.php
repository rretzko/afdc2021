@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">

                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div id="data-entry">

                        <h3 style="margin: 1rem;">Score Entry for {{ $eventversion->name }}</h3>

                        <form method="post" action="{{ route('eventadministrator.scores.store') }}">
                            <style>
                                .err{
                                    background-color: rgba(255,0,0,0.1);
                                    border: 1px solid darkred;
                                    border-radius: 0.25rem;
                                    color: darkred; padding: 0 0.25rem;
                                    font-size: 0.8rem;
                                    margin:0.25rem 0;
                                    max-width: 20rem;
                                    }
                                .input-group{display: flex; flex-direction: column;  max-width: 80%; padding: 0 1rem; margin-bottom: 0.5rem;}
                            </style>
                            @csrf

                            <div class="input-group">
                                <label for="registration_id">Registration ID</label>
                                <input type="text" name="registration_id" value="{{ old('registration_id') }}" autofocus required />
                                @error('registration_id')
                                    <div class="err">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="input-group">
                                <label for="judge_id">Judge ID</label>
                                <input type="text" name="adjudicator_id" value="{{ old('adjudicator_id') }}"  required />
                                @error('adjudicator_id')
                                    <div class="err">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="input-group">
                                <label style="font-weight: bold; text-decoration: underline;">Scores</label>
                                <div style="display: flex; flex-direction: column;">
                                    @foreach($eventversion->scoringcomponents AS $key=>$sc)
                                        <div style="display: flex; flex-direction: row; margin-bottom: 0.25rem;">
                                            <label style="width: 4rem;" for="{{ $sc->abbr }}">
                                                {{ $sc->abbr }}
                                            </label>
                                            <input type="text" name="score-{{ $key }}" value="{{ old('score-'.$key) }}"  style="max-width: 4rem; text-align: center;" onblur="checkValue('{{ $key }}');"  required />
                                            @if($sc->abbr === 'Qrt')<span style="margin-left: 1rem;">(* 4)</span>@endif
                                            @error('score-'.$key)
                                                <div class="err" style="margin-left: 0.5rem;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                    <div style="display: flex; flex-direction: row;">
                                        <label style="width: 4rem;">Total</label>
                                        <div id="score-total">0</div>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group">
                                <input type="submit" name="submit" value="Submit" style="max-width: 8rem;" />
                            </div>

                            <div style="margin-bottom: 1rem;">
                                {{-- ERRORS --}}
                                @if($errors->any())
                                    <div class="err" style="margin-left: 1rem;">
                                        Errors are displayed.  These scores were NOT saved!
                                    </div>
                                @endif

                                {{-- SUCCESS --}}
                                @if(session()->has('success'))
                                    <div style="margin-left: 1rem; background-color: rgba(0,255,0,0.1); padding: 0 0.25rem; max-width: 20rem; border: 1px solid darkgreen; border-radius: 0.25rem;">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif

                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        function checkValue($key){

            var total = 0;
console.log('document: '+(typeof document.getElementsByName('score-6')[0] === 'undefined'));
            var scores = [
                ((typeof document.getElementsByName('score-0')[0] === 'undefined') || isNaN(document.getElementsByName('score-0')[0].value) || (document.getElementsByName('score-0')[0].value.length === 0)) ? 0 : parseInt(document.getElementsByName('score-0')[0].value),
                ((typeof document.getElementsByName('score-1')[0] === 'undefined') || isNaN(document.getElementsByName('score-1')[0].value) || (document.getElementsByName('score-1')[0].value.length === 0)) ? 0 : parseInt(document.getElementsByName('score-1')[0].value),
                ((typeof document.getElementsByName('score-2')[0] === 'undefined') || isNaN(document.getElementsByName('score-2')[0].value) || (document.getElementsByName('score-2')[0].value.length === 0)) ? 0 : parseInt(document.getElementsByName('score-2')[0].value),
                ((typeof document.getElementsByName('score-3')[0] === 'undefined') || isNaN(document.getElementsByName('score-3')[0].value) || (document.getElementsByName('score-3')[0].value.length === 0)) ? 0 : parseInt(document.getElementsByName('score-3')[0].value),
                ((typeof document.getElementsByName('score-4')[0] === 'undefined') || isNaN(document.getElementsByName('score-4')[0].value) || (document.getElementsByName('score-4')[0].value.length === 0)) ? 0 : (parseInt(document.getElementsByName('score-4')[0].value) * 4),
                ((typeof document.getElementsByName('score-5')[0] === 'undefined') || isNaN(document.getElementsByName('score-5')[0].value) || (document.getElementsByName('score-5')[0].value.length === 0)) ? 0 : parseInt(document.getElementsByName('score-5')[0].value),
                ((typeof document.getElementsByName('score-6')[0] === 'undefined') || isNaN(document.getElementsByName('score-6')[0].value) || (document.getElementsByName('score-6')[0].value.length === 0)) ? 0 : parseInt(document.getElementsByName('score-6')[0].value),
                ((typeof document.getElementsByName('score-7')[0] === 'undefined') || isNaN(document.getElementsByName('score-7')[0].value) || (document.getElementsByName('score-7')[0].value.length === 0)) ? 0 : parseInt(document.getElementsByName('score-7')[0].value),
                ((typeof document.getElementsByName('score-8')[0] === 'undefined') || isNaN(document.getElementsByName('score-8')[0].value) || (document.getElementsByName('score-8')[0].value.length === 0)) ? 0 : parseInt(document.getElementsByName('score-8')[0].value),
                ((typeof document.getElementsByName('score-9')[0] === 'undefined') || isNaN(document.getElementsByName('score-9')[0].value) || (document.getElementsByName('score-9')[0].value.length === 0)) ? 0 : parseInt(document.getElementsByName('score-9')[0].value),
            ];

            for(var i = 0; i < scores.length; i++){
                console.log('scores['+i+'] = '+scores[i]);
                total += scores[i];
            }
            console.log(scores);

            console.log('Total = '+total);
            document.getElementById('score-total').innerHTML =  total;
            $curvalue = document.getElementsByName('score-'+$key)[0].value;

            console.log('score-'+$key+' has value: '+$curvalue+'.');

        }
    </script>

@endsection

