@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout  :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Eventversion Configuration: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem; padding-bottom: 0;">

                        <h3>{{ $eventversion->name }} Configurations</h3>

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

                        <form method="post" action="{{ route('eventadministration.eventversion.eventconfigurations.update') }}" >

                            @csrf

                            <style>
                                .datetype-label{font-weight: bold;}
                                .date-block{display:flex; flex-direction: column;}
                                .dateblock-label{font-size: smaller; min-width: 3rem;}
                                .hint{font-size: xx-small;}
                                .input-block{display: flex; flex-direction: column;}
                            </style>

                            {{-- GRADES --}}
                            <div class="input-block">
                                <label for="registrationfee" class="datetype-label">Grades</label>
                                <div style="display:flex; flex-direction: row; margin-left: 1rem; ">
                                    @for($i=1; $i<13; $i++)
                                        <div>
                                            <input type="checkbox" name="grades[{{$i}}]"
                                                   value="{{ $i }}"
                                                   @if($configurations && $configurations->grades && in_array($i, explode(',',$configurations->grades))) checked @endif
                                            >
                                            <label for="grades" class="dateblock-label">{{ $i }}</label>
                                        </div>
                                    @endfor

                                </div>
                            </div>

                            {{-- EAPPLICATION --}}
                            <div class="input-block">
                                <label for="eapplication" class="datetype-label">Students will use an eApplication</label>
                                <div style="display:flex; flex-direction: column; margin-left: 1rem; ">
                                    <div>
                                        <input type="checkbox" name="eapplication"
                                               value="1"
                                               @if($configurations && $configurations->eapplication) checked @endif
                                        >
                                        <label for="eapplication" class="dateblock-label">Yes</label>
                                    </div>
                                </div>
                            </div>

                            {{-- MAXIMUMS --}}
                            <div class="input-block">
                                <label for="max_count" class="datetype-label">Maximums </label>
                                <div style="display:flex; flex-direction: column; margin-left: 1rem; ">
                                    <div>
                                        <label for="max_count" class="dateblock-label" style="min-width: 14rem;">Maximum # Students/School</label>
                                        <select name="max_count">
                                            @for($i=0; $i<50; $i++)
                                                <option value="{{ $i }}"
                                                    @if($configurations && $configurations->max_count && ($configurations->max_count == $i)) selected @endif
                                                >
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        <span class="hint">(0 = no maximum)</span>
                                    </div>

                                    <div>
                                        <label for="max_uppervoice_count" class="dateblock-label" style="min-width: 14rem;">Maximum # Students/Upper Voices</label>
                                        <select name="max_uppervoice_count">
                                            @for($i=0; $i<50; $i++)
                                                <option value="{{ $i }}"
                                                    @if($configurations && $configurations->max_uppervoice_count && ($configurations->max_uppervoice_count == $i)) selected @endif
                                                >
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        <span class="hint">(0 = no maximum)</span>
                                    </div>

                                    <div>
                                        <label for="instrumentation_count" class="dateblock-label" style="min-width: 14rem;">Maximum # Voice Parts/Student</label>
                                        <select name="instrumentation_count">
                                            @for($i=1; $i<5; $i++)
                                                <option value="{{ $i }}"
                                                    @if($configurations && $configurations->instrumentation_count && ($configurations->instrumentation_count == $i)) selected @endif
                                                >
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- REGISTRATION FEES --}}
                            <div class="input-block">
                                <label for="registrationfee" class="datetype-label">Registration Fees</label>
                                <div style="display:flex; flex-direction: column; margin-left: 1rem; ">
                                    <div>
                                        <label for="registrationfee" class="dateblock-label" style="min-width: 14rem;">Student Registration Fee</label>
                                        <input type="text" name="registrationfee"
                                               value="@if($configurations && $configurations->registrationfee) {{ $configurations->registrationfee }} @else 0.00 @endif"
                                               style="width: 8rem;"
                                        >
                                    </div>
                                    <div>
                                        <label for="onsiteregistrationfee" class="dateblock-label" style="min-width: 14rem;">On-Site Student Registration Fee</label>
                                        <input type="text" name="onsiteregistrationfee"
                                               value="@if($configurations && $configurations->onsiteregistrationfee) {{ $configurations->onsiteregistrationfee }} @else 0.00 @endif"
                                               style="width: 8rem;"
                                        >
                                    </div>

                                </div>
                            </div>

                            {{-- PAYPAL --}}
                            <div class="input-block">
                                <label for="paypalteacher" class="datetype-label">PayPal Payments</label>
                                <div style="display:flex; flex-direction: column; margin-left: 1rem; ">
                                    <div>
                                        <input type="checkbox" name="paypalteacher"
                                               value="1"
                                               @if($configurations && $configurations->paypalteacher) checked @endif
                                        >
                                        <label for="paypalteacher" class="dateblock-label">Allow Teacher to pay via PayPal</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="paypalstudent"
                                               value="1"
                                               @if($configurations && $configurations->paypalstudent) checked @endif
                                        >
                                        <label for="paypalstudent" class="dateblock-label">Allow Student to pay via PayPal</label>
                                    </div>
                                    <div>
                                        <label for="epaymentsurcharge" class="dateblock-label" style="min-width: 14rem;">Surcharge for PayPal Payments</label>
                                        <input type="text" name="epaymentsurcharge"
                                               value="@if($configurations && $configurations->epaymentsurcharge) {{ $configurations->epaymentsurcharge }} @else 0.00 @endif"
                                               style="width: 8rem;"
                                        >
                                    </div>

                                </div>
                            </div>

                            {{-- VIRTUAL AUDITION --}}
                            <div class="input-block">
                                <label for="judge_count" class="datetype-label">Virtual Audition</label>
                                <div style="margin-left: 1rem;">
                                    <div>
                                        <input type="checkbox" name="virtualaudition"
                                              value="1"
                                              @if($configurations && $configurations->virtualaudition) checked @endif
                                        >
                                        <label for="virtualaudition" class="dateblock-label">Yes</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="audiofiles"
                                               value="1"
                                               @if($configurations && $configurations->audiofiles) checked @endif
                                        >
                                        <label for="audiofiles" class="dateblock-label">Audio (mp3) files</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" name="videofiles"
                                               value="1"
                                               @if($configurations && $configurations->videofiles) checked @endif
                                        >
                                        <label for="videofiles" class="dateblock-label">Video (mp4) files</label>
                                    </div>
                                </div>

                            </div>

                            {{-- JUDGES PER ROOM --}}
                            <div class="input-block">
                                <label for="judge_count" class="datetype-label">Judges Per Room</label>
                                <div style="display:flex; flex-direction: column; margin-left: 1rem; ">
                                    <div>
                                        <label for="judge_count" class="dateblock-label" style="min-width: 14rem;">Count of Judges/Room</label>
                                        <select name="judge_count">
                                            @for($i=1; $i<11; $i++)
                                                <option value="{{ $i }}"
                                                    @if($configurations && $configurations->judge_count && ($configurations->judge_count == $i)) selected @endif
                                                >
                                                    {{ $i }}

                                                </option>
                                            @endfor
                                        </select>

                                    </div>
                                </div>
                            </div>

                            {{-- MEMBERSHIP CARD --}}
                            <div class="input-block">
                                <label for="eapplication" class="datetype-label">Membership Card is required</label>
                                <div style="display:flex; flex-direction: column; margin-left: 1rem; ">
                                    <div>
                                        <input type="checkbox" name="membershipcard"
                                               value="1"
                                               @if($configurations && $configurations->membershipcard) checked @endif
                                        >
                                        <label for="membershipcard" class="dateblock-label">Yes</label>
                                    </div>

                                    <div>
                                        <label for="expiration" class="membership_date" >Last Accepted Expiration Date</label>
                                        <input type="date" name="expiration"
                                               value="{{ isset($dates) && $dates->where('datetype_id',3)->first() ? $dates->where('datetype_id',3)->first()->dtYMD : date('Y-M-d',strtotime('NOW')) }}"
                                        >
                                    </div>

                                </div>
                            </div>

                            {{-- BEST SCORE --}}
                            <div class="input-block">
                                <label for="bestscore" class="datetype-label">Score Order</label>
                                <div style="display:flex; flex-direction: column; margin-left: 1rem; ">
                                    <div>
                                        <input type="radio" name="bestscore"
                                               value="asc"
                                               @if(
    (! $configurations) ||
    (! $configurations->bestscore) ||
    ($configurations && $configurations->bestscore && ($configurations->bestscore === 'asc'))) checked @endif
                                        >
                                        <label for="bestscore" class="dateblock-label">
                                        Sort scores in ascending order (best score is lowest score)
                                        </label>
                                    </div>

                                    <div>
                                        <input type="radio" name="bestscore"
                                               value="desc"
                                               @if(
    $configurations && $configurations->bestscore && ($configurations->bestscore === 'desc')) checked @endif
                                        >
                                        <label for="bestscore" class="dateblock-label">
                                            Sort scores in descending order (best score is highest score)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- ALTERNATING SCORES --}}
                            <div class="input-block">
                                <label for="eapplication" class="datetype-label">Alternating Scores</label>
                                <div style="display:flex; flex-direction: column; margin-left: 1rem; ">
                                    <div>
                                        <input type="checkbox" name="alternating_scores"
                                               value="1"
                                               @if($configurations && $configurations->alternating_scores) checked @endif
                                        >
                                        <label for="alternating_scores" class="dateblock-label">Divide accepted students by alternating scores</label>
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

@endsection
