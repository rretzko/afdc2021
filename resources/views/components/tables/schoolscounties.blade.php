@props([
'counties' => false,
'eventversion' => false,
'mycounties' => false,
'myschools' => false,
'registrationactivity' => false,
'schools' => false,
'toggle',
])
<div style="display: none;">{{ set_time_limit(90) }}</div>
<style>
    #rm_table{ border-collapse: collapse;}
    #rm_table td,th{border: 1px solid black; padding:0 .25rem;}
</style>

<livewire:emailreceipt-component :toggle="$toggle" />

<script>
    function receiptEmail()
    {
        console.log('receiptEmail');
    }
</script>


