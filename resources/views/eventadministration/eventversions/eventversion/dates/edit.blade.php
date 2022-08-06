@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout  :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Eventversion Date Administration: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem; padding-bottom: 0;">

                        <h3>{{ $eventversion->name }} Dates</h3>

                        {{-- ERRORS --}}
                        <div style="display: flex; flex-direction: column;">
                            @foreach($errors->all() AS $error)
                                <div style="color: red;">{{ $error }}</div>
                            @endforeach
                        </div>

                        {{-- SUCCESS --}}
                        @if(Session::has('status'))
                            <div style="display: flex; flex-direction: column; background-color: rgba(0,255,0,0.1); padding: 0.25rem;">
                               {!! \Session::get('status') !!}
                            </div>
                        @endif

                        <form method="post" action="{{ route('eventadministration.eventversion.dates.update') }}" >

                            @csrf

                            <style>
                                .datetype-label{font-weight: bold;}
                                .date-block{display:flex; flex-direction: column;}
                                .dateblock-label{font-size: smaller; min-width: 3rem;}
                                .hint{font-size: xx-small;}
                                .input-block{display: flex; flex-direction: column;}
                            </style>

                            {{-- ADMINISTRATION --}}
                            <div class="input-block"">
                                <label for="datetype_ids[1]" class="datetype-label">Administration</label>
                                <div style="display:flex; flex-direction: column; ">
                                    <div>
                                        <label for="datetype_ids[1]" class="dateblock-label">Open</label>
                                        <input type="date" name="datetype_ids[1]"
                                               value="{{ $dates->where('datetype_id',1)->first() ? $dates->where('datetype_id',1)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                           >
                                        <span class="hint">(1)</span>
                                    </div>
                                    <div>
                                        <label for="datetype_ids[2]" class="dateblock-label">Close</label>
                                        <input type="date" name="datetype_ids[2]"
                                               value="{{ $dates->where('datetype_id',2)->first() ? $dates->where('datetype_id',2)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                        >
                                        <span class="hint">(2)</span>
                                    </div>

                                </div>
                            </div>

                            {{-- MEMBERSHIP --}}
                            <div class="input-block">
                                <label for="datetype_ids[3]" class="datetype-label">Membership</label>
                                <div class="date-block">
                                    <div>
                                        <label for="datetype_ids[3]" class="dateblock-label" >Open</label>
                                        <input type="date" name="datetype_ids[3]"
                                               value="{{ $dates->where('datetype_id',3)->first() ? $dates->where('datetype_id',3)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                        >
                                        <span class="hint">(3)</span>
                                    </div>
                                    <div>
                                        <label for="datetype_ids[4]" class="dateblock-label">Close</label>
                                        <input type="date" name="datetype_ids[4]"
                                               value="{{ $dates->where('datetype_id',4)->first() ? $dates->where('datetype_id',4)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                        >
                                        <span class="hint">(4)</span>
                                    </div>

                                </div>
                            </div>

                    {{-- STUDENT --}}
                    <div class="input-block">
                        <label for="datetype_ids[3]" class="datetype-label">Student</label>
                        <div class="date-block">
                            <div>
                                <label for="datetype_ids[5]" class="dateblock-label" >Open</label>
                                <input type="date" name="datetype_ids[5]"
                                       value="{{ $dates->where('datetype_id',5)->first() ? $dates->where('datetype_id',5)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(5)</span>
                            </div>
                            <div>
                                <label for="datetype_ids[6]" class="dateblock-label">Close</label>
                                <input type="date" name="datetype_ids[6]"
                                       value="{{ $dates->where('datetype_id',6)->first() ? $dates->where('datetype_id',6)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                               >
                                <span class="hint">(6)</span>
                            </div>

                        </div>
                    </div>

                    {{-- VOICE CHANGE --}}
                    <div class="input-block">
                        <label for="datetype_ids[7]" class="datetype-label">Voice Change</label>
                        <div class="date-block">
                            <div>
                                <label for="datetype_ids[7]" class="dateblock-label" >Open</label>
                                <input type="date" name="datetype_ids[7]"
                                       value="{{ $dates->where('datetype_id',7)->first() ? $dates->where('datetype_id',7)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(7)</span>
                            </div>
                            <div>
                                <label for="datetype_ids[8]" class="dateblock-label">Close</label>
                                <input type="date" name="datetype_ids[8]"
                                       value="{{ $dates->where('datetype_id',8)->first() ? $dates->where('datetype_id',8)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(8)</span>
                            </div>

                        </div>
                    </div>

                    {{-- SIGNATURE --}}
                    <div class="input-block">
                        <label for="datetype_ids[9]" class="datetype-label">Signature</label>
                        <div class="date-block">
                            <div>
                                <label for="datetype_ids[9]" class="dateblock-label" >Open</label>
                                <input type="date" name="datetype_ids[9]"
                                       value="{{ $dates->where('datetype_id',9)->first() ? $dates->where('datetype_id',9)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(9)</span>
                            </div>
                            <div>
                                <label for="datetype_ids[10]" class="dateblock-label">Close</label>
                                <input type="date" name="datetype_ids[10]"
                                       value="{{ $dates->where('datetype_id',10)->first() ? $dates->where('datetype_id',10)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(10)</span>
                            </div>

                        </div>
                    </div>

                    {{-- SCORING --}}
                    <div class="input-block">
                        <label for="datetype_ids[11]" class="datetype-label">Scoring</label>
                        <div class="date-block">
                            <div>
                                <label for="datetype_ids[11]" class="dateblock-label" >Open</label>
                                <input type="date" name="datetype_ids[11]" value="{{ $dates->where('datetype_id',11)->first() ? $dates->where('datetype_id',11)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(11)</span>
                            </div>
                            <div>
                                <label for="datetype_ids[12]" class="dateblock-label">Close</label>
                                <input type="date" name="datetype_ids[12]" value="{{ $dates->where('datetype_id',12)->first() ? $dates->where('datetype_id',12)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(12)</span>
                            </div>

                        </div>
                    </div>

                    {{-- TAB ROOM CLOSED --}}
                    <div class="input-block">
                        <label for="datetype_ids[13]" class="datetype-label">Tab Room Close</label>
                        <div class="date-block">
                            <div>
                                <label for="datetype_ids[13]" class="dateblock-label">Close</label>
                                <input type="date" name="datetype_ids[13]"
                                       value="{{ $dates->where('datetype_id',13)->first() ? $dates->where('datetype_id',13)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(13)</span>
                            </div>

                        </div>
                    </div>

                    {{-- RELEASE RESULTS --}}
                    <div class="input-block">
                        <label for="datetype_ids[14]" class="datetype-label">Release Results</label>
                        <div class="date-block">
                            <div>
                                <label for="datetype_ids[14]" class="dateblock-label">Release</label>
                                <input type="date" name="datetype_ids[14]"
                                       value="{{ $dates->where('datetype_id',14)->first() ? $dates->where('datetype_id',14)->first()->dtYMD : '' }}"
                                >
                                <span class="hint">(14)</span>
                            </div>

                        </div>
                    </div>

                    {{-- APPLICATIONS OPEN --}}
                    <div class="input-block">
                        <label for="datetype_ids[16]" class="datetype-label">Applications</label>
                        <div class="date-block">
                            <div>
                                <label for="datetype_ids[16]" class="dateblock-label" >Open</label>
                                <input type="date" name="datetype_ids[16]"
                                       value="{{ $dates->where('datetype_id',16)->first() ? $dates->where('datetype_id',16)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(16)</span>
                            </div>
                            <div>
                                <label for="datetype_ids[15]" class="dateblock-label">Close</label>
                                <input type="date" name="datetype_ids[15]"
                                       value="{{ $dates->where('datetype_id',15)->first() ? $dates->where('datetype_id',15)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(15)</span>
                            </div>

                        </div>
                    </div>

                    {{-- MEDIA FILES MEMBERSHIP --}}
                    <div class="input-block">
                        <label for="datetype_ids[17]" class="datetype-label">Media File Upload: Membership</label>
                        <div class="date-block">
                            <div>
                                <label for="datetype_ids[17]" class="dateblock-label" >Open</label>
                                <input type="date" name="datetype_ids[17]"
                                       value="{{ $dates->where('datetype_id',17)->first() ? $dates->where('datetype_id',17)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(17)</span>
                            </div>
                            <div>
                                <label for="datetype_ids[18]" class="dateblock-label">Close</label>
                                <input type="date" name="datetype_ids[18]"
                                       value="{{ $dates->where('datetype_id',18)->first() ? $dates->where('datetype_id',18)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(18)</span>
                            </div>

                        </div>
                    </div>

                    {{-- MEDIA FILES STUDENT --}}
                    <div class="input-block">
                        <label for="datetype_ids[19]" class="datetype-label">Media File Upload: Student</label>
                        <div class="date-block">
                            <div>
                                <label for="datetype_ids[19]" class="dateblock-label" >Open</label>
                                <input type="date" name="datetype_ids[19]"
                                       value="{{ $dates->where('datetype_id',19)->first() ? $dates->where('datetype_id',19)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(19)</span>
                            </div>
                            <div>
                                <label for="datetype_ids[20]" class="dateblock-label">Close</label>
                                <input type="date" name="datetype_ids[20]"
                                       value="{{ $dates->where('datetype_id',20)->first() ? $dates->where('datetype_id',20)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                >
                                <span class="hint">(20)</span>
                            </div>

                        </div>
                    </div>

                    {{-- MEMBERSHIP VALID THROUGH --}}
                    <div class="input-block">
                        <label for="datetype_ids[21]" class="datetype-label">Membership Valid Through</label>
                        <div class="date-block">
                            <div>
                                <label for="datetype_ids[21]" class="dateblock-label">Valid Through</label>
                                <input type="date" name="datetype_ids[21]"
                                       value="{{ $dates->where('datetype_id',21)->first() ? $dates->where('datetype_id',21)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                   >
                                <span class="hint">(21)</span>
                            </div>

                        </div>
                    </div>

                    {{-- SUBMIT --}}
                            <div style="display: flex; flex-direction: column; max-width: 8rem; margin-left: 3.25rem; margin-bottom: 1rem;">
                                <label></label>
                                <input type="submit" name="submit" value="Update" />
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
@if(auth()->id() === 368) {{phpinfo()}} @endif
@endsection
