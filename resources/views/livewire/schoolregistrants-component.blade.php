<div style="margin: auto;">
    <style>
        th{text-align: center;}
        td,th{border: 1px solid black; padding: 0 .25rem;}
    </style>
    <h3 style="text-align: center;">
        {{ $school->name }}
    </h3>

    <div id="changeform" style="width: 100%;">
        @if($registrant)
            @if(config('app.url') === 'http://afdc2021.test')
                <form method="POST" action="{{ route('registrationmanager.registrant.update',['registrant' => $registrant]) }}" style="display: flex; flex-direction: column; width: 50%; margin-left: 25%; margin-bottom: 1rem; border:1px solid gray; padding: .25rem;">
            @else
                <form method="POST" action="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanager/registrant/update/{{ $registrant->id }}" style="display: flex; flex-direction: column; width: 50%; margin-left: 25%; margin-bottom: 1rem; border:1px solid gray; padding: .25rem;">
            @endif
                @csrf
                <header>Click the student's name to populate this form...</header>
                <div style="display: flex; flex-direction: row; justify-content: space-around;">
                    <div>
                        <label>Name</label>
                        <div>
                            {{ $registrant->student->person->fullName() }}
                        </div>
                    </div>
                    <div>
                        <label>Voice Part</label></lable>
                        <div>
                            {!! $voiceparts !!}
                        </div>
                    </div>
                    <div>
                        <label></label>
                        <div>
                            <input type="submit" name="submit" id="submit" value="Update" />
                        </div>
                    </div>

                </div>
            </form>
                        <div style="border: 1px solid black; padding: 0.5rem 1rem; background-color: rgba(0,0,0,0.1); margin-bottom: 1rem;">
                            <header style="font-weight: bold;">EMERGENCY CONTACT INFORMATION</header>
                            <form method="post" action="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanager/registrant/updateEmergencyContact/{{ $registrant->id }}"> --}} -->
                            <!-- {{-- <form method="post" action="/registrationmanager/registrant/updateEmergencyContact/{{ $registrant->id }}"> --}} -->
                                @csrf
                                <div style="display: flex; flex-direction: column; font-weight: bold;">
                                    <label for="email_student_personal">Student Email</label>
                                    <input style="margin-bottom: 0.25rem;" type="email" name="email_student_personal" id="email_student_personal"
                                           value=" @if($registrant->student->emailPersonal->id) {{ $registrant->student->emailPersonal->email }} @endif "
                                           placeholder="Personal email"
                                    >
                                    <input type="email" name="email_student_school" id="email_student_school"
                                           value=" @if($registrant->student->emailSchool->id) {{ $registrant->student->emailSchool->email }} @endif"
                                           placeholder="School email"
                                    >
                                </div>
                                <div>
                                    <input type="hidden" name="guardian_id" id="guardian_id" value="{{ $registrant->student->guardians->first()->user_id ?? 0 }}"
                                    <label for="parent-first" style="font-weight: bold;">Parent/Guardian</label>
                                    <div style="display: flex; flex-direction: row; font-weight: bold;">
                                        <input type="text" name="first" id="first"
                                               value="{{ $registrant->student->guardians->first()->person->first }}"
                                               placeholder="First name"
                                                required
                                        />
                                        <input type="text" name="last" id="last"
                                               value="{{ $registrant->student->guardians->first()->person->last }}"
                                               placeholder="Last name"
                                                required
                                        />
                                    </div>
                                </div>
                                <div style="display: flex; flex-direction: column; font-weight: bold;">
                                    <label for="parent-email">Parent/Guardian Email</label>
                                    <input style="margin-bottom: 0.25rem;" type="email" name="email_guardian_primary" id="email_guardian_primary"
                                           value="@if($registrant->student->guardians->first()->emailPrimary->id) {{ $registrant->student->guardians->first()->emailPrimary->email }} @endif "
                                           placeholder="Primary email">
                                    <input type="email" name="email_guardian_alternate" id="email_guardian_alternate"
                                           value="@if($registrant->student->guardians->first()->emailAlternate->id) {{ $registrant->student->guardians->first()->emailAlternate->email }} @endif"
                                           placeholder="Alternate email">
                                </div>
                                <div style="display: flex; flex-direction: column; font-weight: bold;">
                                    <label for="parent-email">Parent/Guardian Phone</label>
                                    <input style="margin-bottom: 0.25rem;" type="text" name="phone_guardian_mobile" id="phone_guardian_mobile" placeholder="Cell Phone"
                                           value="@if($registrant->student->guardians->first()->phoneMobile->id && strlen($registrant->student->guardians->first()->phoneMobile->phone)) {{ $registrant->student->guardians->first()->phoneMobile->phone }} @endif"
                                    >
                                    <input style="margin-bottom: 0.25rem;" type="text" name="phone_guardian_work" id="phone_guardian_work" placeholder="Work Phone"
                                           value="@if($registrant->student->guardians->first()->phoneWork->id && strlen($registrant->student->guardians->first()->phoneWork->phone)) {{ $registrant->student->guardians->first()->phoneWork->phone }} @endif"
                                    >
                                    <input style="margin-bottom: 0.25rem;" type="text" name="phone_guardian_home" id="phone_guardian_home" placeholder="Home Phone"
                                           value="@if($registrant->student->guardians->first()->phoneHome->id && strlen($registrant->student->guardians->first()->phoneHome->phone)) {{ $registrant->student->guardians->first()->phoneHome->phone }} @endif"
                                    >
                                    <input style="font-weight: bold; width: 6rem; border-radius: 1rem;" type="submit" name="submit" id="submit" value="Update" >

                                </div>
                            </form>
                        </div>
        @endif
    </div>

    <table style="margin:auto; border-collapse: collapse;">
        <thead>
        <tr>
            <th>###</th>
            <th>Student</th>
            <th>Voice</th>
            @foreach($eventversion->filecontenttypes AS $filecontenttype)
                <th>{{ ucwords($filecontenttype->descr) }}</th>
            @endforeach
            <th>Rev'd</th>
        </tr>
        </thead>
        <tbody>

        @foreach($registrants AS $registrant)
               <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
               <td>
                    <a href="{{ route('registrants.school.edit',
                        [
                            'eventversion' => $eventversion,
                            'school' => $school,
                            'registrant' => $registrant,
                        ]) }}"
                    >
                        {{ $registrant->student->person->fullNameAlpha() }}
                    </a>
                </td>
                <td style="text-align: center;">
                    @foreach($registrant->instrumentations AS $instrumentation)
                        {{ strtoupper($instrumentation->abbr) }}
                    @endforeach
                </td>

                @foreach($eventversion->filecontenttypes AS $filecontenttype)
                    <td class="text-center" >{!! $registrant->fileviewport($filecontenttype) !!}</td>
                @endforeach

                <td style="text-align: center;">
                    <input wire:click="updateReviewed({{ $registrant->id }})"
                           type="checkbox"
                           name="reviewed"
                           id="reviewed"
                           value="1"
                           key="{{ $registrant->id }}"
                           {{ (count($reviews) &&  in_array($registrant->id, $reviews)) ? 'CHECKED' : '' }}
                    />
                </td>

            </tr>

        @endforeach

        </tbody>
    </table>
</div>
