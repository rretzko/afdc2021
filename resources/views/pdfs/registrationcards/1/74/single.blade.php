<style>
    table{ border-collapse: collapse; padding: .25rem;}
    td,th{border-left: 1px solid black; border-right: 1px solid black;}
    .page_break{page-break-before: always;}
</style>
{{-- @foreach($registrants AS $registrant) --}}

@for($i = 0; $i<$registrants->count(); $i++)

    @if(($i + 1) % 2)
<table>
    <tr>
        <td style="width: 20rem; min-width: 20rem; max-width: 20rem;">
            <x-registrationcards.pdfs.1.card_b
                :registrant="$registrants[$i]"
                :eventversion="$eventversion"
                :room="$rooms[0]"
                :instrumentation="$instrumentation"
            />
        </td>

        {{-- spacer --}}
        <td style="width=3rem; min-width: 3rem; max-width: 3rem;"></td>

    @else
<td style="width: 20rem; min-width: 20rem; max-width: 20rem;">
        <x-registrationcards.pdfs.1.card_b
            :registrant="$registrants[$i]"
            :eventversion="$eventversion"
            :room="$rooms[0]"
            :instrumentation="$instrumentation"
        />
</td>
    </tr>
</table>
    @endif

    @if(! (($i + 1) % 10) )
        <div class="page_break"></div>
    @endif

@endfor
