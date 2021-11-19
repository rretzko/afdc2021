@props([
'eventversion',
])

<style>
    #rm_table{ border-collapse: collapse;}
    #rm_table td,th{border: 1px solid black; padding:0 .25rem;}
</style>

<livewire:timeslots-component :eventversion="$eventversion"/>



