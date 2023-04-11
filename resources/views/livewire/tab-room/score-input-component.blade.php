<div>
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

        {{-- ROOMS --}}
        <div class="input-group">
            <label for="room_id">Select Room</label>
            <select wire:model="roomid" style="max-width: 20rem;" onchange="updateAdjudicators();">
                <option value="0" disabled>- Select Room -</option>
                @forelse($rooms->sortBy('order_by') AS $room)
                    <option value="{{ $room->id }}">
                        {{ $room->descr }}
                    </option>
                @empty
                    //nothing more
                @endif
            </select>
        </div>

        {{-- ADJUDICATORS --}}
        <div class="input-group">
            <label for="room_id">Select Adjudicator</label>
            <select wire:model="adjudicatorid" style="max-width: 20rem;" onchange="updateAdjudicators();">
                <option value="0" disabled>- Select Adjudicator -</option>
                @forelse($adjudicators AS $adjudicator)
                    <option value="{{ $adjudicator->user_id }}">
                        {{ $adjudicator->adjudicatorName }}
                    </option>
                @empty
                    {{-- do nothing --}}
                @endif
            </select>
        </div>

        {{-- REGISTRANT ID --}}
        <div class="input-group" style="width: 20rem;">
            <label for="registration_id">Registrant ID</label>
            <input type="text" wire:model="registrantid" value="{{ old('registration_id') }}" autofocus required />
            <div style="color: red;">
                {{ $registrantiderror }}
            </div>
            <div style="font-size: small; font-style: italic;min-height: 2rem;">
                {{ $registrantdetail }}
            </div>

        </div>

        {{-- SCORING COMPONENTS --}}
        <div class="input-group" style="width: 20rem;">
            <label for="scoringcomponentid">Scoring Components</label>

                <div style="display: flex; flex-direction: column;">
                    <div style="display: flex; flex-direction: row;">

                        @if(strlen($registrantdetail))
                            @foreach($scoringcomponents AS $scoringcomponent)

                                <div style="display: flex; flex-direction: column;">

                                    <div style="width: 3rem; text-align: center; border: 1px solid black;">
                                        {{ is_array($scoringcomponent) ? $scoringcomponent['abbr'] : $scoringcomponent->abbr }}
                                    </div>

                                    <div>
                                        <input wire:model="scores.{{$scoringcomponent['id']}}"
                                               value="{{ array_key_exists($scoringcomponent['id'], $scores) ? $scores[$scoringcomponent['id']] : 0 }}" style="width: 3rem; max-width: 3rem;" />
                                    </div>

                                </div>

                            @endforeach

                            {{-- SCORE TOTAL --}}
                            <div style="display: flex; flex-direction: column; background-color: #edf2f7;">

                                <div style="width: 3rem; text-align: center; border: 1px solid black;">
                                    Total
                                </div>

                                <div style="width: 3rem; max-width: 3rem; text-align: center; height: 1.9rem; border: 1px solid black;">
                                    {{ $scoretotal }}
                                </div>

                            </div>

                        @endif {{-- $registrantdetail --}}

                    </div>
                    {{-- SCORING ERRORS --}}
                    <div style="color: red;">
                        {{ $scoringerrors }}
                    </div>
                </div>
            {{-- SCORES UPDATED --}}
            @if(strlen($scoresupdated))
                <div style="margin-top: 1rem; color: darkgreen; background-color: rgba(0,255,0,0.1); border: 1px solid darkgreen; height: 1.66rem; padding: 0 0.5rem;">
                    {{ $scoresupdated }}
                </div>
            @endif

        </div>

    </form>
</div>
