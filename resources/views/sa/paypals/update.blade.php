<div>
    <style>
        label{font-weight: bold; margin-bottom: 0.25rem;}
        .input-group{display: flex; flex-direction: column; margin-bottom: 0.5rem;}
    </style>
    <form method="post" action="{{ route('sa.paypals.update') }}" >

        @csrf

        <h2>Log In As</h2>
        <div class="input-group">
            <label>Select Teacher</label>
            {{ $registrant }}
        </div>
        <div class="input-group">
            <label></label>
            <input style="width: 12rem;" type="submit" name="submit" value="Submit" />
        </div>
    </form>
</div>
