<div>
    <x-eventadministration.style />

    @if(config('app.url' === 'http://afdc2021.test'))
        <form method="post" action="{{ route('eventadministrator.adjudicators.update') }}" >
    @else
        <form method="post" action="https://afdc-2021-l38q8.ondigitalocean.app/eventadminstrator/adjudicators/update" >
    @endif

        @csrf

        <div class="input-group">
            <label for="room_id">Room</label>
            <select name="room_id" id="room_id">
                @foreach($rooms AS $room)
                    <option value="{{ $room->id }}">{{ $room->descr }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group">
            <label for="user_id">Member</label>
            <select name="user_id" id="user_id">
                @foreach($members AS $mbr)
                    <option value="{{ $mbr->user_id }}">{{ $mbr->user->person->fullnameAlpha() }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group">
            <label></label>
            <input type="submit" name="submit" id="submit" value="Update Room" />
            @if($member->id)
                <input type="submit" name="cancel" id="cancel" value="Cancel" style="margin-left: .5rem;"/>
            @endif
        </div>

    </form>

</div>
