<style>
    table{width: 100%; border-collapse: collapse; padding: .25rem;}
    td,th{text-align: center; height: 1.33rem;}
    .page_break{page-break-before: always;}

</style>
{{-- @foreach($registrants AS $registrant) --}}
@for($i=1; $i<11; $i++)
    <div>

        <table>
            <tr>
                <td>
                    {{-- LEFT SIDE --}}
                    <table style="border: 1px solid black; ">
                <tr>
                    <td colspan="2" style="border: 1px solid black; font-weight: bold;">School Name</td>
                </tr>
                <tr>
                    <td colspan="2" style="border: 1px solid black; background-color: rgba(0,0,0,0.1); font-weight: bold;">Event Name</td>
                </tr>
                <tr>
                    <td colspan="2">Student Name, Alpha</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div style="margin: 0 2rem; border: 1px solid black;">Audition Number: ######</div>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; text-align: left; padding-left: 1rem;">Scales/Quartet</td>
                    <td style="font-weight: bold; text-align: right; padding-right: 1rem;">Soprano I</td>
                </tr>
                <tr>
                    <td colspan="2">Total Score ____________________</td>
                </tr>
                <tr>
                    <td>Accepted (  )</td>
                    <td>Not Accepted (  )</td>
                </tr>
            </table>
                </td>
                <td>
                    {{-- RIGHT SIDE --}}
                    <table style="border: 1px solid black; ">
            <tr>
                <td colspan="2" style="border: 1px solid black; font-weight: bold;">School Name</td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid black; background-color: rgba(0,0,0,0.1); font-weight: bold;">Event Name</td>
            </tr>
            <tr>
                <td colspan="2">Student Name, Alpha</td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="margin: 0 2rem; border: 1px solid black;">Audition Number: ######</div>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; text-align: left; padding-left: 1rem;">Solo</td>
                <td style="font-weight: bold; text-align: right; padding-right: 1rem;">Soprano I</td>
            </tr>
            <tr>
                <td colspan="2">Total Score ____________________</td>
            </tr>
            <tr>
                <td>Accepted (  )</td>
                <td>Not Accepted (  )</td>
            </tr>
        </table>
                </td>
            </tr>
        </table>

    </div>

    @if(! ($i % 5) )
        <div class="page_break"></div>
    @endif

 @endfor
