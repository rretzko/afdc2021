<?php

namespace App\Models\Utility;

use App\Models\Timeslot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableTimeslots extends Model
{
    use HasFactory;

    private $csrf;
    private $datarows;
    private $eventversion;
    private $instrumentations;
    private $route;
    private $schools;
    private $style;
    private $tbl;

    public function __construct(array $attributes)
    {
        $this->csrf = $attributes['csrf'];
        $this->eventversion = $attributes['eventversion'];
        $this->instrumentations = $this->eventversion->instrumentations();
        $this->route = $attributes['route'];
        $this->schools = $attributes['schools'];
        $this->style = $this->style();

        $this->dataRows($attributes['schools']);

        $this->buildTable();
    }

    public function table()
    {
        return $this->tbl;
    }

/** END OF PUBLIC FUNCTIONS **************************************************/

    private function buildBody()
    {
        $str = '';

        //foreach($this->schools AS $school) {
        foreach($this->datarows AS $row){

            $str .= $this->subtotalRow($row);

            $str .= '<form method="post" action="'.$this->route.'" >';
            $str .= '<input type="hidden" name="_token" value="'.$this->csrf.'" />';
            $str .= '<input type="hidden" name="eventversion_id" id="eventversion_id" value="' . $this->eventversion->id . '" />';
            $str .= '<input type="hidden" name="school_id" id="school_id" value="' . $row['school_id'] . '" />';

            $str .= '<tr>';

            $str .= '<td style="text-align: right;">';
            $str .= '<input type="text" name="timeslot" id="timeslot" value="' . $row['timeslot'] . '" style="width: 4rem;" />';
            $str .= '</td>';

            $str .= '<td>' . $row['shortname'] . '</td>';

            $str .= '<td style="text-align: center;">' . $row['rowtotal'] . '</td>';

            foreach ($this->instrumentations as $instrumentation) {
                $str .= '<td style="text-align: center;">';
                $str .= $row[$instrumentation->id];//$this->eventversion->registrantsForSchoolByInstrumentation($school, $instrumentation)->count();
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

        foreach ($this->instrumentations as $instrumentation) {
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

    private function dataRows($schools)
    {
        $this->datarows = [];
        $rowtotal = 0;

        foreach($schools AS $key => $school){

            $timeslot = Timeslot::where('school_id', $school->id)
                ->where('eventversion_id', $this->eventversion->id)
                ->first() ?? new Timeslot;

            $this->datarows[$key]['school_id'] = $school->id;
            $this->datarows[$key]['shortname'] = $school->shortName;
            $this->datarows[$key]['timeslot'] = $timeslot ? $timeslot->timeslot : 0;
            $this->datarows[$key]['armytime'] = $timeslot ? $timeslot->armytime : 0;

            foreach($this->instrumentations AS $instrumentation){
                $this->datarows[$key][$instrumentation->id] = $this->eventversion->registrantsForSchoolByInstrumentation($school, $instrumentation)->count();
                $rowtotal += $this->datarows[$key][$instrumentation->id];
            }
            $this->datarows[$key]['rowtotal'] = $rowtotal;
        }
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

    private function subtotalRow($row)
    {
        static $armytime = 0;
        static $slots = []; //array to hold subtotals
        $insertrow = '';

        //initialize/refresh $armytime
        if((! $armytime ) || ($armytime !== $row['armytime'])){

            //create the subtotal row
            $insertrow =  $this->insertSubtotalRow($armytime, $slots);

            //reset armytime
            $armytime = $row['armytime'];

            //reset slots
            $slots = [];
        }

        //initialize/refresh $slots
        if(empty($slots)){

            for($i=0; $i<=$this->instrumentations->count(); $i++){

                $slots[] = 0;
            }
        }

        //total for school
        $slots[0] +=  $row['rowtotal'];

        foreach($this->instrumentations AS $key => $instrumentation){
            $slots[($key + 1)] += $row[$instrumentation->id];
        }

        return $insertrow;
    }
}
