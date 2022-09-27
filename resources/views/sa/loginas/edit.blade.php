<div>
    <style>
        label{font-weight: bold; margin-bottom: 0.25rem;}
        .input-group{display: flex; flex-direction: column; margin-bottom: 0.5rem;}
    </style>
    <form method="post" action="{{ route('sa.loginas.update') }}" >

        @csrf

        <h2>Log In As</h2>
        <div class="input-group">
            <label>Select Teacher</label>
            <select name="user_id" autofocus>
                <option value="0">Select</option>
                @foreach($teachers AS $teacher)
                    <option value="{{ $teacher[0] }}">
                        {{ $teacher[1].' ('.$teacher[0].')' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="input-group">
            <label></label>
            <input style="width: 12rem;" type="submit" name="submit" value="Submit" />
        </div>
    </form>
</div>
