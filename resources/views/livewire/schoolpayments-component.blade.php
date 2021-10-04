<div style="margin-left: 1rem; margin-bottom: 1rem;">

    <style>
        label{font-weight: bold;margin-top: .5rem;}
        .input_group{ display:flex; flex-direction: column;}
    </style>

    <h4>Payment Form</h4>
    <form wire:submit.prevent="updateSchoolpayment".  method="post" action="">

        @csrf

        <div class="input_group">
            <label for="school_id">School</label>
            <select wire:model="targetschool" name="school_id" id="school_id" style="max-width:50%;">
                <option value="0">- Select School -</option>
                @foreach($schools AS $school)
                    <option value="{{ $school->id }}">{{ $school->shortName }}</option>
                @endforeach
            </select>
        </div>

        <div class="input_group">
                <label for="user_id">Teacher</label>
                @if(! is_null($teachers) && $teachers->count())
                    <div style="display: flex; flex-direction: row;" >
                        <select wire:model="userid" name="user_id" id="user_id" style="max-width:50%;">
                            @foreach($teachers AS $teacher)
                                <option value="{{ $teacher->user_id }}">{{ $teacher['person']->fullNameAlpha() }}</option>
                            @endforeach
                        </select>
                         <span>
                             &nbsp;@ {{ $targetschool->name }}
                         </span>
                    </div>
                @endif
        </div>

        <div class="input_group">
            <label for="amount">Amount</label>
            <input wire:model.lazy="amount" type="text" name="amount" id="amount" value="0" style="max-width: 6rem;"/>
        </div>

        <div class="input_group">
            <label for="comments">Comments, Check #, etc.</label>
            <textarea wire:model.lazy="comments"
                      cols="20" rows="3"
                      name="comments" id="comments"
                      style="max-width: 50%;"></textarea>
        </div>

        <div class="input_group">
            <label></label>
            <input type="submit" name="submit" id="submit" value="Submit" style="max-width: 50%;">
        </div>

    </form>
</div>
