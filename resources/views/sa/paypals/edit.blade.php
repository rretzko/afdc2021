<div>
    <style>
        label{font-weight: bold; margin-bottom: 0.25rem;}
        .input-group{display: flex; flex-direction: column; margin-bottom: 0.5rem;}
    </style>
    <h2>EDITING...</h2>
    <form method="post" action="{{ route('sa.paypals.update') }}" >

        @csrf

        <h2>PayPal Manual Entry</h2>
        <div class="input-group">
            <label>Add Paypal * String</label>
            <input name="paypal-string" value="" style="max-width: 20rem;"/>
        </div>
        <div class="input-group">
            <label></label>
            <input style="width: 12rem;" type="submit" name="submit" value="Submit" />
        </div>
    </form>
</div>
