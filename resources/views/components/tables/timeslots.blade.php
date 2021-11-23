@props([
'csrf',
'eventversion',
'route',
])

<style>
    #rm_table{ border-collapse: collapse;}
    #rm_table td,th{border: 1px solid black; padding:0 .25rem;}
</style>

<livewire:timeslots-component csrf={{$csrf}} route={{$route}} :eventversion="$eventversion"/>



