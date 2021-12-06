@extends('layouts.app')

<style>
    section{width: available; border-bottom: 1px solid darkblue; padding-bottom: .5rem; margin-bottom: 1rem;}
</style>
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout  :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Rehearsal Manager: Mass Mailings: Concert: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: row; justify-content: space-between; padding: 1rem .5rem;">
                        <div style="display: flex; flex-direction: column; width: 66%;">

                            <section id="variables">
                                <x-rehearsalmanagers.forms.massmailings.concert
                                    :eventversion="$eventversion"
                                    :massmailing="$massmailing"
                                />

                            </section>

                            <section id="paragraphs" style="background-color: aliceblue; padding: .5rem;">
                                <div>{!! $emailbody !!}</div>
                            </section>
                        </div>

                        <section id="participants" style="border: 1px solid darkblue;padding: .5rem .25rem; background-color: aliceblue;">
                            <div style="text-align: center;font-weight: bold; background-color: rgba(0,0,0,.1);">Teacher checklist</div>
                            @if(config('app.url') === 'http://afdc2021.test')
                                <form method="post" action="{{ route('rehearsalmanager.massmailings.concert.store', ['eventversion' => $eventversion]) }}">
                            @else
                                <form method="post" action="https://afdc-2021-l38q8.digitalocean.app/rehearsalmanager/massmailings/concert/store/{{ $eventversion->id }}"
                            @endif
                                    @csrf
                                <div id="buttons" >
                                    <div style="display: flex; flex-direction: column; text-align: center; margin: .5rem 0;">
                                        <div style="margin-bottom: .5rem;">
                                            @if(strlen($massmailing->findVar('sender_email')))
                                                <input type="submit";
                                                     style="border-radius: .5rem; background-color: blanchedalmond; "
                                                     name="test"
                                                     id="test"
                                                     value="Send Test Email to: {{ $massmailing->findVar('sender_email') }}"
                                                />
                                            @else
                                                <input type="submit";
                                                       style="border-radius: .5rem; background-color: blanchedalmond;"
                                                       value="Sender email is missing"
                                                       DISABLED
                                                />
                                            @endif
                                        </div>
                                        <div>
                                            <input type="submit"
                                                   style="border-radius: .5rem; background-color: darkseagreen; color: white;"
                                                   name="massmailing"
                                                   id="massmailing"
                                                   value="Send LIVE Email"
                                            />
                                        </div>
                                    </div>
                                </div>

                                {{-- SENT MESSAGE --}}
                                @isset($message)
                                    <div style="background-color: rgba(0,255,0,.1);border: 1px solid darkgreen; border-radius: 1rem; text-align: center; font-size: 0.8rem; margin-bottom: .5rem;">
                                        {{ $message }}
                                    </div>
                                @endisset

                                <x-rehearsalmanagers.checklists.teachers
                                    :eventversion="$eventversion"
                                    :teachers="$teachers"
                                />

                                <div style="display: flex; flex-direction: column; text-align: center; margin: .5rem 0;">
                                    <input type="submit"
                                           style="border-radius: .5rem; background-color: darkseagreen; color: white;"
                                           value="Send LIVE Email"
                                    />
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
