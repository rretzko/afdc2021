@props([
    'eventversion',
    'massmailing',
])
<style>
    label{width:10rem; text-align: right; margin-right: 0.5rem;}
    .input-group{ margin-bottom: 0.5rem;}
</style>

@if(config('app.url') === 'http://afdc2021.test')
    <form method="post" action="{{ route('rehearsalmanager.massmailings.concert.update', ['eventversion' => $eventversion]) }}">
@else
    <form method="post" action="https://afdc-2021-l38q8.ondigitalocean.app/rehearsalmanager/massmailings/concert/update/{{ $eventversion->id }} ">
@endif
    @csrf
    <h2>Update the following information to complete the email template:</h2>

    <div class="input-group">
            <label for="concert_date">Concert Date</label>
            <div style="display: flex; flex-direction: column;">
                <input type="text" style="@error('concert_date') border: 1px solid red; @enderror" name="concert_date" id="concert_date" value="{{ $massmailing->findVar('concert_date') }}" />
                @error('concert_date')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
    </div>

    <div class="input-group">
        <label for="concert_time">Concert Time</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('concert_time') border: 1px solid red; @enderror" name="concert_time" id="concert_time" value="{{ $massmailing->findVar('concert_time') }}" />
            @error('concert_time')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="arrival_time">Arrival Time</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('arrival_time') border: 1px solid red; @enderror" name="arrival_time" id="arrival_time" value="{{ $massmailing->findVar('arrival_time') }}" />
            @error('arrival_time')
            <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="venue_name">Venue Name</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('venue_name') border: 1px solid red; @enderror"  name="venue_name" id="venue_name" value="{{ $massmailing->findVar('venue_name') }}" />
            @error('venue_name')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="venue_shortname">Venue Short Name</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('venue_shortname') border: 1px solid red; @enderror" name="venue_shortname" id="venue_shortname" value="{{ $massmailing->findVar('venue_shortname') }}" />
            @error('venue_shortname')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="venue_address">Venue Address</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('venue_address') border: 1px solid red; @enderror" name="venue_address" id="venue_address" value="{{ $massmailing->findVar('venue_address') }}" />
            @error('venue_address')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="google_link">Google Link</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('google_link') border: 1px solid red; @enderror" name="google_link" id="google_link" value="{{ $massmailing->findVar('google_link') }}" />
            @error('google_link')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="sender_name">Sender Name</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('sender_name') border: 1px solid red; @enderror" name="sender_name" id="sender_name" value="{{ $massmailing->findVar('sender_name') ?: auth()->user()->person->fullname()}}" />
            @error('sender_name')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="sender_title">Sender Title</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('sender_title') border: 1px solid red; @enderror" name="sender_title" id="sender_title" value="{{ $massmailing->findVar('sender_title') ?: 'Rehearsal Manager' }}" />
            @error('sender_title')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="school_address">School Address</label>
        <div style="display: flex; flex-direction: column;">
            <textarea cols="40" rows="3" style="@error('school_address') border: 1px solid red; @enderror" name="school_address" id="school_address" placeholder="School Name&#013;Address&#013;City,State Zip">{{ $massmailing->findVar('school_address') }}</textarea>
            @error('school_address')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="sender_email">Sender Email</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('sender_email') border: 1px solid red; @enderror" name="sender_email" id="sender_email" value="{{ $massmailing->findVar('sender_email') ?: auth()->user()->person->subscriberemailWork }}" />
            @error('sender_email')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="sender_phone">Sender Phone</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('sender_phone') border: 1px solid red; @enderror" name="sender_phone" id="sender_phone" value="{{ $massmailing->findVar('sender_phone') ?: auth()->user()->person->phonemobile }}" />
            @error('sender_phone')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="postscript">Postscript</label>
        <div style="display: flex; flex-direction: column;">
            <textarea cols="40" rows="5" style="@error('postscript') border: 1px solid red; @enderror" name="postscript" id="postscript" placeholder="Add an optional postscript to go with the email.">{{ $massmailing->findVar('postscript') }}</textarea>
            @error('postscript')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for=""></label>
        <input type="submit" name="submit" id="submit" value="Submit" />
    </div>
</form>
