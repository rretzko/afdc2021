<div>
    <div id="navigation">
        <a href="{{ route('home') }}">Home</a>
    </div>
    <style>
        label{font-weight: bold; margin-bottom: 0.25rem;}
        .input-group{display: flex; flex-direction: column; margin-bottom: 0.5rem;}
    </style>
    <h2>EDITING...</h2>
    <form method="post" action="{{ route('sa.loadscores.update') }}" >

        @csrf

        <h2>Load Fake Scores For Testing</h2>
        <div class="input-group">
            <label>Select Eventversion</label>
            <select name="eventversion_id" style="max-width: 20rem;">
                @foreach($eventversions->sortBy('name') AS $target_eventversion)
                    <option value="{{ $target_eventversion->id }}"
                    @if($eventversion && $eventversion->id == $target_eventversion->id) SELECTED @endif
                    >{{ $target_eventversion->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group">
            <label></label>
            <input style="width: 12rem;" type="submit" name="submit" value="Submit" />
        </div>
    </form>

    <div id="eventversion-selection" style="border-top: 1px solid darkgrey; padding-top: 1rem;">

            <style>
                .data-row{display: flex; flex-direction: row; margin-left: 2rem;}
                .data-row label{font-weight: normal; min-width: 8rem;}
                .data-row .data-detail{font-weight: bold;}
                .data-subrow{margin-left: 1rem;}
                .create-scores-btn{ border: 1px solid darkgreen; border-radius: 0.25rem; background-color: lightgreen; color: darkgreen; padding: 0 0.25rem;}
            </style>

            {{-- EVENTVERSION NAME/ID --}}
            <div class="data-row">
                <label>Eventversion</label>
                <div class="data-detail">
                    {{ $eventversion->name }} ({{ $eventversion->id }})
                </div>
            </div>

            {{-- REGISTRANTS COUNT --}}
            <div class="data-row">
                <label>Registrants</label>
                <div class="data-detail">
                    {{ $registrants_count ?? 0 }}
                </div>
            </div>

            {{-- ROOMS COUNT --}}
            <div class="data-row">
                <label>Rooms</label>
                <div class="data-detail">
                    {{ $rooms_count ?? 0 }}
                </div>
            </div>

            {{-- SUCCESS MESSAGE --}}
            @if(Session::has('success'))
                <div style="margin: 1rem 0; margin-left: 2rem; background-color: rgba(0,255,0,0.1); padding: 0 0.5rem; border: 1px solid darkgreen; color: darkgreen; border-radius: 0.25rem; max-width: 24rem;">
                    {{ Session::get('success') }}
                </div>
            @endif

            {{-- ROOMS --}}
            @if(isset($rooms))
            <div id="rooms">
                @foreach($rooms AS $room)
                    <div class="data-subrow">

                        <div class="data-row">
                            <label>
                                <a href="{{ route('sa.loadscores.store',['room' => $room]) }}" title="Create Scores for Room {{ $loop->iteration }}">
                                    <button class="create-scores-btn" style="cursor: pointer">
                                        Room {{ $loop->iteration }}
                                    </button>
                                </a>
                            </label>
                            <div class="data-detail">
                                {{ $room->descr }} ({{ $room->id }})

                                @foreach($room->adjudicators AS $adjudicator)

                                    <div class="data-row">
                                        <label>Adjudicator {{ $loop->iteration }}</label>
                                        <div class="data-detail">
                                            {{ $adjudicator->adjudicatorname }} ({{ $adjudicator->id }})
                                        </div>
                                    </div>
                                @endforeach
                                <div class="data-row">
                                    <label>Registrants</label>
                                    <div class="data-detail">
                                        {{ $room->auditioneesCount() }}
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
            @endif

    </div>

</div>
