<?php

namespace App\Services;


use App\Models\Eventversion;
use App\Models\Registranttype;
use App\Models\School;
use App\Models\Userconfig;
use FontLib\TrueType\Collection;
use Illuminate\Support\Facades\DB;

class ParticipatingDirectorsTable
{
    private $eventversion;
    private $instrumentations;
    private $instrumentationHeaderLabels;
    private $registrationFee;
    private $rowsArray;
    private $schoolIds;
    private $table;

    public function __construct()
    {
        $this->init();
    }

    public function table(): string
    {
        return $this->table;
    }

/** END OF PUBLIC FUNCTIONS =================================================*/

    private function calcAmountDue($instrumentationCount): float
    {
        return ($this->registrationFee * $instrumentationCount);
    }

    private function countInstrumentationBySchool($instrumentationId, $schoolId): int
    {
        return DB::table('registrants')
            ->join('instrumentation_registrant', 'registrants.id', '=', 'instrumentation_registrant.registrant_id')
            ->where('eventversion_id', $this->eventversion->id)
            ->where('school_id', $schoolId)
            ->where('instrumentation_registrant.instrumentation_id', $instrumentationId)
            ->where('registranttype_id', Registranttype::REGISTERED)
            ->count('registrants.id');
    }

    private function getInstrumentations(): \Illuminate\Support\Collection
    {
        $this->eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        return $this->eventversion->instrumentations();
    }

    private function getSchoolIds(): array
    {
        return  DB::table('registrants')
            ->join('schools', 'registrants.school_id','=','schools.id')
            ->where('registrants.eventversion_id', $this->eventversion->id)
            ->where('registrants.registranttype_id', Registranttype::REGISTERED)
            ->select(['registrants.school_id','schools.name'])
            ->distinct()
            ->orderBy('schools.name')
            ->get()
            ->toArray();
    }

    private function init(): void
    {
        $this->instrumentations = $this->getInstrumentations();
        $this->instrumentationHeaderLabels = $this->instrumentationHeaderLabels();
        $this->schoolIds = $this->getSchoolIds();
        $this->registrationFee = $this->eventversion->eventversionconfig->registrationfee;
        $this->rowsArray = $this->rowsArray();
        $this->table = $this->makeTable();
    }

    private function instrumentationCountCells($schoolId): string
    {
        $str = '';
        $sumRegistrants = 0;

        foreach($this->instrumentations AS $instrumentation) {

            $instrumentationCount = $this->countInstrumentationBySchool($instrumentation->id, $schoolId);

            $str .= '<td style="text-align: center;">'
                . $instrumentationCount
                . '</td>';

            $sumRegistrants += $instrumentationCount;
        }

        $str .= '<td style="text-align: center;">' . $sumRegistrants .'</td>';

        $str .= '<td style=text-align: left;">'. $this->calcAmountDue($sumRegistrants) . '</td>';

        return $str;
    }

    private function instrumentationHeaderLabels(): string
    {
        $str = '';

        foreach($this->instrumentations->pluck('abbr') AS $abbr){

            $str .= '<th>' . strtoupper($abbr) . '</th>';
        }

        return $str;
    }

    private function makeTable(): string
    {
        $str = $this->tableInit();
        $str .= $this->tableHeaders();
        $str .= $this->tableRows();
        $str .= $this->tableFinish();

        return $str;
    }

    /**
     * @return array[
     * 'school_id' => ####
     * 'schoolName' => 'School Name'
     */
    private function rowsArray(): array
    {
        $a = [];
        foreach($this->schoolIds AS $row) {

            $schoolId = $row->school_id;
            $school = School::find($schoolId);
            $currentTeacher = $school->currentTeacher($this->eventversion)->person->fullNameAlpha();

            $a[] = [
                'schoolId' => $schoolId,
                'schoolName' => $school->shortName,
                'teacherName' => $currentTeacher,
            ];
        }

        return $a;
    }

    private function tableFinish(): string
    {
        return '</table>';
    }

    private function tableHeaders(): string
    {
        $str = '<tr>'
            . '<td colspan="13" style="border-left: 0; border-top: 0; border-right: 0;"></td>'
            . '<td style="font-size: 0.8rem; text-align: center;">';
        $str .= '<a href="" >csv</a>';
        $str .= '</a>'
            . '</td>';
        $str .= '</tr>';

        $str .= '<tr>';
        $str .= '<th>###</th>';
        $str .= '<th>School Name/Director</th>';
        $str .= '<th>Receipt</th>';
        $str .= $this->instrumentationHeaderLabels;
        $str .= '<th>Total</th>';
        $str .= '<th>Due</th>';
        $str .= '<th>Paid</th>';
        $str .= '</tr>';

        return $str;
    }

    private function tableInit(): string
    {
        $str = $this->tableStyle();
        $str .= '<table>';

        return $str;
    }

    private function tableRows(): string
    {
        $str = '';

        foreach($this->rowsArray AS $key => $row){
            $str .= '<tr>';
            $str .= '<td>' . ($key + 1) . '</td>';
            $str .= '<td>' . $row['schoolName']
                . '<br />'
                . $row['teacherName']
                . '</td>';
            $str .= '<td style="text-align: center; color: blue; cursor: pointer;">'
                . '<span wire:click="setSchool(' . $row['schoolId'] .')">email</span>'
                . '</td>';

            //individual instrumentation cells + total instrumentation cell + amount_due cell
            $str .= $this->instrumentationCountCells($row['schoolId']);

            $str .= '<td>Paid</td>';

            $str .= '</tr>';
        }

        return $str;
    }

    private function tableStyle(): string
    {
        $str = '<style>';

        $str .= 'table{border-collapse: collapse;}';
        $str .= 'td,th{border: 1px solid black; padding: 0 0.25rem;}';

        $str .= '</style>';

        return $str;
    }
}
