<div style="display: flex; flex-direction: column; padding-left: .5rem">
    <x-eventadministration.style />
    @if(config('app.url') === 'http://afdc2021.test')
        <form method="post" action="{{ route('eventadministrator.rooms.update', ['room' => $room->id]) }}">
    @else
        <form method="post" action="https://afdc-2021-l38q8.ondigitalocean.app/eventadministrator/rooms/update/{{ $room->id ?: 0 }}">
    @endif

        @csrf

        <div class="input-group">
            <label for="">Sys.Id.</label>
            <span>{{ $room->id ?: 0 }}</span>
        </div>

        <div class="input-group">
            <label for="descr">Name</label>
            <div style="display:flex; flex-direction: column;">
                <input type="text" name="descr" id="descr"
                       value="{{ $room->id ? $room->descr : '' }}" style="width: 20rem;"/>
                @error('descr')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="input-group">
            <label for="order_by">Room Order</label>
            <select name="order_by">
                @for($i=1; $i<101; $i++)
                    <option value="{{ $i }}"
                    @if($room->id)
                        {{ $room->order_by == $i ? 'SELECTED' : '' }}
                        @else
                        {{ $i === 1 ? 'SELECTED' : '' }}
                        @endif
                    >
                        {{ $i }}
                    </option>
                @endfor
            </select>

        </div>

        <div class="input-group">
            <label for="descr">Voice Part(s)</label>
            <div style="display:flex flex-direction: row">
                <select name="instrumentations[]" multiple style="width: 10rem;">
                    @foreach($instrumentations AS $instrumentation)
                        <option value="{{ $instrumentation->id }}"
                            @if($room->instrumentations->contains($instrumentation)) SELECTED @endif
                        >
                            {{ $instrumentation->descr }}
                        </option>
                    @endforeach
                </select>
                @error('instrumentations')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="input-group">
            <label for="descr">Segments</label>
            <div style="display: flex; flex-direction: column;">
                <select name="filecontenttypes[]" multiple style="width: 10rem;">
                    @foreach($filecontenttypes AS $filecontenttype)
                        <option value="{{ $filecontenttype->id }}"
                            @if($room->filecontenttypes->contains($filecontenttype)) SELECTED @endif
                        >
                            {{ $filecontenttype->descr }}
                        </option>
                    @endforeach
                </select>
                @error('filecontenttypes')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="input-group">
            <label></label>
            <input type="submit" name="submit" id="submit" value="Update Room" />
            @if($room->id)
                <input type="submit" name="cancel" id="cancel" value="Cancel" style="margin-left: .5rem;"/>
            @endif
        </div>

    </form>
</div>
