<style>
    table{width: 100%; border-collapse: collapse; padding: .25rem;}
    td,th{border-left: 1px solid black; border-right: 1px solid black; color: darkgray;}
    .page_break{page-break-before: always;}
</style>
@for($i=1; $i<11;$i++)
    <div style="display: flex; flex-direction: row;">
        <table>
            <tr>
                <td  colspan="2" style="width: 200px; min-width: 200px; max-width: 200px; font-weight: bold; border-top: 1px solid black; color:black;">
                    {{ $eventversion->name }}
                </td>
                <td style="color: white;">-</td>
                <td colspan="2" style="width: 200px; min-width: 200px; max-width: 200px; font-weight: bold; border-top: 1px solid black; color: black;">
                    {{ $eventversion->name }}
                </td>
            </tr>
            <tr>
                <td colspan="2">Room:</td>
                <td style="color: white;">-</td>
                <td colspan="2">Room:</td>
            </tr>
            <tr style="margin: 0.5rem 0; border: 1px solid black;">
                <td>
                    Aud #
                </td>
                <td style="border-left: 0;">
                    Voice Part:
                </td>
                <td style="color: white; border-top: 1px solid white; border-bottom: 1px solid white;">-</td>
                <td>
                    Aud #
                </td>
                <td style="border-left: 0;">
                    Voice Part
                </td>
            <tr>
                <td colspan="2" >
                    Name:
                </td>
                <td style="color: white; border-top: 1px solid white; border-bottom: 1px solid white;">-</td>
                <td colspan="2">
                    Name:
                </td>
            </tr>
            <tr style="border-top: 1px solid darkgray;">
                <td colspan="2">
                    Email:
                </td>
                <td style="color: white; border-top: 1px solid white; border-bottom: 1px solid white;">-</td>
                <td colspan="2">
                    Email:
                </td>
            </tr>
            <tr style="border-top: 1px solid darkgray;">
                <td colspan="2">
                    Check-in time:
                </td>
                <td style="color: white; border-top: 1px solid white; border-bottom: 1px solid white;">-</td>
                <td colspan="2">
                    Check-in time:
                </td>
            </tr>
            <tr style="border-top: 1px solid darkgray;">
                <td colspan=2" style="border-bottom: 1px solid black;">
                    School Name
                </td>
                <td style="color: white; border-top: 1px solid white; border-bottom: 1px solid white;">-</td>
                <td colspan="2" style="border-left: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
                    School Name
                </td>
            </tr>

            </tr>
        </table>
    </div>

    @if(! ($i % 5) )
        <div class="page_break"></div>
    @endif

@endfor
