@props([
'payerschool',
'emailbody',
])
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
