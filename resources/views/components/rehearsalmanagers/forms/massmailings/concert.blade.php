@props([
    'eventversion',
])
<style>
    label{width:10rem; text-align: right; margin-right: 0.5rem;}
    .input-group{ margin-bottom: 0.5rem;}
</style>
<form method="post" action="{{ route('rehearsalmanager.massmailings.concert.update', ['eventversion' => $eventversion]) }}">
    @csrf
    <h2>Update the following information to complete the email template:</h2>

    <div class="input-group">
            <label for="concert_date">Concert Date</label>
            <div style="display: flex; flex-direction: column;">
                <input type="date" style="@error('concert_date') border: 1px solid red; @enderror" name="concert_date" id="concert_date" value="2021-12-03" />
                @error('concert_date')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
    </div>

    <div class="input-group">
        <label for="concert_time">Concert Time</label>
        <div style="display: flex; flex-direction: column;">
            <input type="time" style="@error('concert_time') border: 1px solid red; @enderror" name="concert_time" id="concert_time" value="20:00:00" />
            @error('concert_time')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="arrival_time">Arrival Time</label>
        <div style="display: flex; flex-direction: column;">
            <input type="time" style="@error('arrival_time') border: 1px solid red; @enderror" name="arrival_time" id="arrival_time" value="15:44:00" />
            @error('arrival_time')
            <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="venue_name">Venue Name</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('venue_name') border: 1px solid red; @enderror"  name="venue_name" id="venue_name" value="St. Elizabeth's Roman Catholic High School" />
            @error('venue_name')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="venue_shortname">Venue Short Name</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('venue_shortname') border: 1px solid red; @enderror" name="venue_shortname" id="venue_shortname" value="Saint E's" />
            @error('venue_shortname')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="venue_address">Venue Address</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('venue_address') border: 1px solid red; @enderror" name="venue_address" id="venue_address" value="300 Stelton Road, Piscataway, NJ 07123" />
            @error('venue_address')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="google_link">Google Link</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('google_link') border: 1px solid red; @enderror" name="google_link" id="google_link" value="https://goo.gl/maps/62jtXveR9H9atXKr7" />
            @error('google_link')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="sender_name">Sender Name</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('sender_name') border: 1px solid red; @enderror" name="sender_name" id="sender_name" value="Rick Retzko" />
            @error('sender_name')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="sender_title">Sender Title</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('sender_title') border: 1px solid red; @enderror" name="sender_title" id="sender_title" value="Domain Owner" />
            @error('sender_title')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="school_address">School Address</label>
        <div style="display: flex; flex-direction: column;">
            <textarea cols="40" rows="3" style="@error('school_address') border: 1px solid red; @enderror" name="school_address" id="school_address" placeholder="School Name&#013;Address&#013;City,State Zip">FJR School of Musick&#013;45 Dayton Crescent&#013;Bernardsville, NJ 07924</textarea>
            @error('school_address')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="sender_email">Sender Email</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('sender_email') border: 1px solid red; @enderror" name="sender_email" id="sender_email" value="rick@mfrholdings.com" />
            @error('sender_email')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="sender_phone">Sender Phone</label>
        <div style="display: flex; flex-direction: column;">
            <input type="text" style="@error('sender_phone') border: 1px solid red; @enderror" name="sender_phone" id="sender_phone" value="201-755-4083" />
            @error('sender_phone')
                <div class="error" style="color: red;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="postscript">Postscript</label>
        <div style="display: flex; flex-direction: column;">
            <textarea cols="40" rows="5" style="@error('postscript') border: 1px solid red; @enderror" name="postscript" id="postscript" placeholder="Add an optional postscript to go with the email."></textarea>
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
