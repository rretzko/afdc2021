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
                            <form>
                                <div id="buttons" >
                                    <div style="display: flex; flex-direction: column; text-align: center; margin: .5rem 0;">
                                        <div style="margin-bottom: .5rem;">
                                            <a href="">
                                                <input type="submit"; style="border-radius: .5rem; background-color: blanchedalmond;">
                                                    Send Test Email
                                                </input>
                                            </a>
                                        </div>
                                        <div>
                                            <a href="">
                                                <button type="submit" style="border-radius: .5rem; background-color: darkseagreen; color: white;">
                                                    Send LIVE Email
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <x-rehearsalmanagers.checklists.teachers
                                    :eventversion="$eventversion"
                                    :teachers="$teachers"
                                />
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
