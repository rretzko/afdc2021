<div>
    <x-eventadministration.style />

    <!-- {{-- @if(config('app.url') === 'http://afdc2021.test') --}} -->
        <form method="post" action="{{ route('eventadministrator.adjudicators.update',
            [

            ]) }}"
        >
    <!-- {{-- @else
        <form method="post" action="https://afdc-2021-l38q8.ondigitalocean.app/eventadministrator/adjudicators/update" >
    @endif --}} -->

        @csrf

        <div class="input-group">
            <label for="room_id">Room</label>
            <select name="room_id" id="room_id">
                @foreach($rooms AS $room)
                    <option value="{{ $room->id }}"
                        @if($adjudicator && $adjudicator->room_id === $room->id) SELECTED @endif
                    >{{ $room->descr }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group">
            <label for="user_id">Member</label>
            <select name="user_id" id="user_id">
                @foreach($members AS $mbr)
                    <option value="{{ $mbr->user_id }}"
                        @if($adjudicator && $adjudicator->user_id === $mbr->user_id) SELECTED @endif
                    >{{ $mbr->user->person->fullnameAlpha() }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group">
            <label for="rank">Rank</label>
            <select name="rank" id="rank">
                @foreach($ranks AS $rank)
                    <option value="{{ $rank['id'] }}"
                            @if($adjudicator && $adjudicator->rank === $rank['id']) SELECTED @endif
                    >{{ $rank['descr'] }}</option>
                @endforeach
            </select>
        </div>

            <div class="input-group">
                <label for="" style="color: white;">Spacer</label>
                <div style="width: 20rem; font-size: small;">
                    Note: <b>ALL</b> organization members are included in this drop-down selector so that non-participating
                    Directors may be assigned as Adjudicators.
                </div>
            </div>

        <div class="input-group">
            <label></label>
            <input type="submit" name="submit" id="submit" value="Update Room" />
            @if($member->id || ($adjudicator && $adjudicator->id))
                <input type="submit" name="cancel" id="cancel" value="Cancel" style="margin-left: .5rem;"/>
            @endif
        </div>
        <div class="input-group">
            @if($adjudicator)
                <label></label>
                <div>
                    <a href="{{ route('eventadministrator.adjudicators.delete', ['id' => $adjudicator->id]) }}"
                        style="color: red;"
                    >
                        Delete Assignment
                    </a>
                </div>
            @endif
        </div>

    </form>

</div>
