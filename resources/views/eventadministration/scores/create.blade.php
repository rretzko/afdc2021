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

                        <form method="post" action="">
<style>
    .input-group{display: flex; flex-direction: column;  max-width: 80%; padding: 0 1rem; margin-bottom: 0.5rem;}
    label{}
</style>
                            @csrf

                            <div class="input-group">
                                <label for="registration_id">Registration ID</label>
                                <input type="text" name="registration_id" value="{{ old('registration_id') }}" autofocus/>
                            </div>

                            <div class="input-group">
                                <label for="judge_id">Judge ID</label>
                                <input type="text" name="adjudicator_id" value="{{ old('adjudicator_id') }}" autofocus/>
                            </div>

                            <div class="input-group">
                                <label>Scores</label>
                                <div style="display: flex; flex-direction: column;">
                                    @foreach($eventversion->scoringcomponents AS $key=>$sc)
                                        <div style="display: flex; flex-direction: row; margin-bottom: 0.25rem;">
                                            <label style="width: 4rem;" for="{{ $sc->abbr }}">
                                                {{ $sc->abbr }}
                                            </label>
                                            <input type="text" name="score-{{ $key }}" value=""  style="max-width: 4rem; text-align: center;" onblur="checkValue('{{ $key }}');"/>
                                            @if($sc->abbr === 'Qrt')<span style="margin-left: 1rem;">(* 4)</span>@endif
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

