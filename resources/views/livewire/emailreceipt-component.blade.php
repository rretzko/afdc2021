<div>

    <div id="emailReceiptModal" style="border: 1px solid black; margin-bottom: 1rem; padding: .5rem;">
        <style>
            label{width: 4rem;}
        </style>
        <form wire:submit.prevent="sendEmail" style="" method="post" action="">

            @csrf

            <div class="inputgroup">
                <label for="">School</label>
                <span style="font-weight: bold; margin-left: .5rem;">{{ $this->payerschool->name }}</span>
            </div>

            <div class="inputgroup">
                <label for="payeruserid">Teachers</label>
                <select wire:model="payeruserid" name="payeruserid" id="payeruserid" >
                    @foreach($payerschool->teachers AS $teacher){
                        <option value="{{ $teacher->user_id }}">{{ $teacher->person->fullnameAlpha() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="inputgroup">
                <label for="emailbody">Email text</label>
                <textarea wire:model="emailbody" cols="60" rows="3" >{!! $emailbody !!}</textarea>
            </div>

            <div class="inputgroup">
                <label for=""></label>
                <input type="submit" name="submit" id="submit" value="Send Email" />
            </div>
        </form>
    </div>

    <table id="rm_table">
        <thead>
        <tr>
            <th>###</th>
            <th>School Name</th>
            <th>Receipt</th>
            <th>Eligible</th>
            <th>Applied</th>
            <th>Regist'd</th>
            @foreach($eventversion->instrumentations() AS $instrumentation)
                <th>{{ strtoupper($instrumentation->abbr) }}</th>
            @endforeach
            <th>Total</th>
            <th>Due</th>
            <th>Paid</th>
        </tr>
        </thead>
        <tbody>

        {{-- SUMMARY ROW --}}
        <tr style="background-color: lightgray;">
            <th colspan="2" class="text-right">Totals</th>
            <td></td>
            <th class="text-center">{{ $registrationactivity->eligibleTotal()->count() }}</th>
            <th class="text-center">{{ $registrationactivity->appliedTotal()->count() }}</th>
            <th class="text-center">{{ $registrationactivity->registeredTotal()->count() }}</th>
            @foreach($eventversion->instrumentations() AS $instrumentation)
                <th style="text-align: center;">
                    {!! $registrationactivity->registeredInstrumentationTotal($instrumentation)->count() !!}
                </th>
            @endforeach
            <th style="text-align: center;">
                {!! $registrationactivity->registeredTotal()->count() !!}
            </th>

            <th style="text-align: right; padding: 0 .25rem;">
                {{ number_format($registrationactivity->registrationfeeDueTotal(), 0) }}
            </th>

            <th style="text-align: right; padding: 0 .25rem;">
                {{ number_format($registrationactivity->registrationfeePaidTotal(), 0) }}
            </th>
        </tr>

        {{-- DETAIL ROWS --}}
        @foreach($schools AS $school)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td><span title="{{ $school->shortName }}">
                        {{ substr($school->shortName, 0, 20) }} ({{ substr($school->county->name, 0, 3) }})
                    </span>
                </td>
                <td style="text-align: center; color: blue; cursor: pointer;">
                    <span wire:click="setSchool({{ $school }})">
                        email
                    </span>
                </td>
                <td style="text-align: center;">{!! $registrationactivity->eligibleCount($school) !!}</td>
                <td style="text-align: center;">{!! $registrationactivity->appliedCount($school) !!}</td>
                <td style="text-align: center;">{!! $registrationactivity->registeredCount($school) !!}</td>
                @foreach($eventversion->instrumentations() AS $instrumentation)
                    <td style="text-align: center;">
                        {!! $registrationactivity->registeredInstrumentationCount($school, $instrumentation) !!}
                    </td>
                @endforeach
                <td style="text-align: center;">{!! $registrationactivity->registeredCount($school) !!}</td>
                <td style="text-align: right; padding: 0 .25rem;">
                    {{ number_format($registrationactivity->registrationFeeDue($school), 0 ) }}
                </td>
                <th style="text-align: right; padding: 0 .25rem;">
                    {{ number_format($registrationactivity->registrationfeePaid($school), 0) }}
                </th>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
