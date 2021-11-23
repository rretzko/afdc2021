<?php

namespace App\Models\Utility;

use App\Models\Timeslot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableTimeslots extends Model
{
    use HasFactory;

    private $csrf;
    private $eventversion;
    private $route;
    private $schools;
    private $style;
    private $tbl;

    public function __construct(array $attributes)
    {
        $this->csrf = $attributes['csrf'];
        $this->eventversion = $attributes['eventversion'];
        $this->route = $attributes['route'];
        $this->schools = $attributes['schools'];
        $this->style = $this->style();

        $this->buildTable();
    }

    public function table()
    {
        return $this->tbl;
    }

/** END OF PUBLIC FUNCTIONS **************************************************/

    private function buildBody()
    {
        /**
         * @foreach($schools AS $school)
        <form method="post" action="{{ route('registrationmanagers.timeslotassignment.update') }}" >
        @csrf
        <input type="hidden" name="eventversion_id" id="eventversion_id" value="{{ $eventversion->id }}" />
        <input type="hidden" name="school_id" id="school_id" value="{{ $school->id }}" />
        <tr>
        <td style="text-align: right;">
        <input type="text" name="timeslot" id="timeslot" value="{{ $school->timeslot($eventversion) }}" style="width: 4rem;" />
        </td>
        <td>{{ $school->shortName }}</td>
        <td style="text-align: center;">{{ $eventversion->registrantsForSchool($school)->count() }}</td>
        @foreach($eventversion->instrumentations() AS $instrumentation)
        <td style="text-align: center;">
        {{ $eventversion->registrantsForSchoolByInstrumentation($school, $instrumentation)->count() }}
        </td>
        @endforeach
        <td>
        <input type="submit" value="Update" />
        </td>
        </tr>
        </form>
        @endforeach
         */
        $str = '';
        foreach($this->schools AS $school) {

            $str .= $this->subtotalRow($school);

            $str .= '<form method="post" action="'.$this->route.'" >';
            $str .= '<input type="hidden" name="_token" value="'.$this->csrf.'" />';
            $str .= '<input type="hidden" name="eventversion_id" id="eventversion_id" value="' . $this->eventversion->id . '" />';
            $str .= '<input type="hidden" name="school_id" id="school_id" value="' . $school->id . '" />';

            $str .= '<tr>';

            $str .= '<td style="text-align: right;">';
            $str .= '<input type="text" name="timeslot" id="timeslot" value="' . $school->timeslot($this->eventversion) . '" style="width: 4rem;" />';
            $str .= '</td>';

            $str .= '<td>' . $school->shortName . '</td>';

            $str .= '<td style="text-align: center;">' . $this->eventversion->registrantsForSchool($school)->count() . '</td>';

            foreach ($this->eventversion->instrumentations() as $instrumentation) {
                $str .= '<td style="text-align: center;">';
                $str .= $this->eventversion->registrantsForSchoolByInstrumentation($school, $instrumentation)->count();
                $str .= '</td>';
            }

            $str .= '<td>';
            $str .= '<input type="submit" value="Update" />';
            $str .= '</td>';

            $str .= '</tr>';

            $str .= '</form>';

        }

        return $str;
    }

    private function buildHeader()
    {
        $str = '<thead>
            <tr>
                <th>Timeslot</th>
                <th>School</th>
                <th>Total</th>';

        foreach ($this->eventversion->instrumentations() as $instrumentation) {
            $str .= '<th>' . strtoupper($instrumentation->abbr) . '</th>';
        }

        $str .= '<th></th>
            </tr>
        </thead>';

        return $str;
    }

    private function buildTable()
    {
        $this->tbl = $this->style;

        $this->tbl .= '<table id="timeslots">';

        $this->tbl .= $this->buildHeader();

        $this->tbl .= $this->buildBody();

        $this->tbl .= '</table>';
    }

    private function insertSubtotalRow($armytime, array $slots)
    {
        $str = '<tr style="background-color: rgba(0,0,0,.1);">';

        $str .= '<td colspan="2"></td>';

        foreach($slots AS $slot){

            $str .= '<td style="text-align: center;">'.$slot.'</td>';
        }

        $str .= '<td></td>
                </tr>';

        return $str;
    }

    private function style()
    {
        return '<style>
                    #timeslots{border-collapse: collapse;margin-top: 1rem;}
                    #timeslots td,th{border:1px solid black; padding: 0 .25rem;}
                </style>';
    }

    private function subtotalRow($school)
    {
        static $armytime = 0;
        static $slots = []; //array to hold subtotals
        $insertrow = '';

        $timeslot = Timeslot::where('school_id', $school->id)
            ->where('eventversion_id', $this->eventversion->id)
            ->first() ?? new Timeslot;

        //initialize/refresh $armytime
        if((! $armytime ) || ($armytime !== $timeslot->armytime)){

            //create the subtotal row
            $insertrow =  $this->insertSubtotalRow($armytime, $slots);

            //reset armytime
            $armytime = $timeslot->armytime;

            //reset slots
            $slots = [];
        }

        //initialize/refresh $slots
        if(empty($slots)){

            for($i=0; $i<=$this->eventversion->instrumentations()->count(); $i++){

                $slots[] = 0;
            }
        }

        //total for school
        $slots[0] +=  $this->eventversion->registrantsForSchool($school)->count();

        foreach($this->eventversion->instrumentations() AS $key => $instrumentation){
            $slots[($key + 1)] += $this->eventversion->registrantsForSchoolByInstrumentation($school, $instrumentation)->count();
        }

        return $insertrow;
    }
}
