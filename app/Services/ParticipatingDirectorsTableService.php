<?php

namespace App\Services;


use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\Registranttype;
use App\Models\School;
use App\Models\Schoolpayment;
use App\Models\SchoolVerified;
use App\Models\Userconfig;
use FontLib\TrueType\Collection;
use Illuminate\Support\Facades\DB;

class ParticipatingDirectorsTableService
{
    private $checkmark;
    private $eventversion;
    private $instrumentations;
    private $instrumentationHeaderLabels;
    private $receiptEmailBody;
    private $registrationFee;
    private $rowsArray;
    private $schoolIds;
    private $table;

    public function __construct(array $myCounties=[])
    {
        $this->init($myCounties);
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

    private function firstEmail(array $row): string
    {
        return strlen($row['emailWork'])
            ? $row['emailWork']
            : (
                strlen($row['emailPersonal'])
                ? $row['emailPersonal']
                : $row['emailOther']
            );
    }

    private function getInstrumentations(): \Illuminate\Support\Collection
    {
        $this->eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        return $this->eventversion->instrumentations();
    }

    private function getSchoolIds(array $myCounties): array
    {
        return  DB::table('registrants')
            ->join('schools', 'registrants.school_id','=','schools.id')
            ->where('registrants.eventversion_id', $this->eventversion->id)
            ->where('registrants.registranttype_id', Registranttype::REGISTERED)
            ->whereIn('schools.county_id', $myCounties)
            ->select(['registrants.school_id','schools.name'])
            ->distinct()
            ->orderBy('schools.name')
            ->get()
            ->toArray();
    }

    private function getSchoolStudentDetails(int $schoolId): string
    {
        $crlf = '&#10;';
        $divider = ' | ';
        $eventversionId = Userconfig::getValue('eventversion', auth()->id());
        $raw = collect();
        $str = '';

        $ids = DB::table('registrants')
            ->where('school_id', $schoolId)
            ->where('eventversion_id', $eventversionId)
            ->where('registranttype_id', Registranttype::REGISTERED)
            ->select('id')
            ->pluck('id');

        $registrants = Registrant::find($ids)->sortBy('fullNameAlpha');

        foreach($registrants AS $registrant){

            $str .= $registrant->student->person->fullNameAlpha() . $divider;
            $str .= strtoupper($registrant->instrumentations->first()->abbr) . $divider;
            $str .= $registrant->student->guardians->first()->phonesCsv . $crlf;
        }

        return $str;
    }

    private function init(array $myCounties): void
    {
        $this->checkmark = '&#x2714;';
        $this->instrumentations = $this->getInstrumentations();
        $this->instrumentationHeaderLabels = $this->instrumentationHeaderLabels();
        $this->schoolIds = $this->getSchoolIds($myCounties);
        $this->registrationFee = $this->eventversion->eventversionconfig->registrationfee;
        $this->rowsArray = $this->rowsArray();
        $this->receiptEmailBody = $this->receiptEmailBody();

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

        $str .= '<td style="text-align: right;">'. $this->calcAmountDue($sumRegistrants) . '</td>';

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

    private function receiptEmailBody(): string
    {
        $crlf = "%0D%0A";

        return 'This notice is to advise you that we have received your '.$this->eventversion->name." packet." . $crlf
        . "The packet has not yet been opened, but you will be notified if there are any questions or expected items missing." . $crlf;
    }

    /**
     * @return array
     */
    private function rowsArray(): array
    {
        $a = [];
        foreach($this->schoolIds AS $row) {

            $schoolId = $row->school_id;
            $school = School::find($schoolId);
            $currentTeacher = $school->currentTeacher($this->eventversion);
            $currentPerson = $currentTeacher->person;

            $a[] = [
                'schoolId' => $schoolId,
                'schoolName' => $school->shortName,
                'schoolCounty' => $school->countyName,
                'teacherName' => $currentPerson->fullNameAlpha(),
                'emailOther' => $currentPerson->subscriberEmailOther,
                'emailPersonal' => $currentPerson->subscriberEmailPersonal,
                'emailWork' => $currentPerson->subscriberEmailWork,
            ];
        }

        return $a;
    }

    private function schoolPayments($schoolId): float
    {
        return Schoolpayment::where('school_id', $schoolId)
            ->where('eventversion_id', $this->eventversion->id)
            ->sum('amount');
    }

    private function tableFinish(): string
    {
        return '</table>';
    }

    private function tableHeaders(): string
    {
        $csvColSpan = ($this->instrumentations->count() + 4);
        $str = '';

    /*FUTURE DEVELOPMENT IF REQUESTED
        $str .= '<tr>'
            . '<td colspan="' . $csvColSpan . '" style="border-left: 0; border-top: 0; border-right: 0;"></td>'
            . '<td style="font-size: 0.8rem; text-align: center; border:0; border-bottom: 1px solid black;">';
        $str .= '<a href="" >csv</a>';
        $str .= '</a>'
            . '</td>';
        $str .= '</tr>';
    */
        $str .= '<tr>';
        $str .= '<th>###</th>';
        $str .= '<th>School Name/Director</th>';
        $str .= '<th style="text-align: center;" title="Audition Package Complete">' . $this->checkmark . '</th>';
        $str .= $this->instrumentationHeaderLabels;
        $str .= '<th>Total</th>';
        $str .= '<th>Due</th>';
        $str .= '<th>Paid</th>';
        $str .= '<th>Payment</th>';
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

            $shading = ($key % 2) ? 'background-color: rgba(0,255,0,0.08);' : '';

            $str .= '<tr style="' . $shading . '">';
            $str .= '<td>' . ($key + 1) . '</td>';
            $str .= '<td>'
                . '<span style="color: darkviolet;" title="' . $this->getSchoolStudentDetails($row['schoolId']) . '">' . $row['schoolName'] . '</span>'
                . ' (' . $row['schoolCounty'] . ')'
                . '<br />'
                . '<a href="mailto:' . $this->firstEmail($row) .'?subject=Receipt of NJ All-State Package&body='. $this->receiptEmailBody . '" title="' . $this->teacherEmailsTitle($row) . '">'
                . $row['teacherName']
                . '</a>'
                . '</td>';

            //verification checkmark
            $str .= '<td style="text-align: center;">' . $this->verificationCheckmark($row['schoolId']) . '</td>';

            //individual instrumentation cells + total instrumentation cell + amount_due cell
            $str .= $this->instrumentationCountCells($row['schoolId']);

            $str .= '<td style="text-align: right;">' . $this->schoolPayments($row['schoolId']) . '</td>';

            $str .= '<td style="text-align: center;">
                        <a href="/registrationmanager/payments/' . $this->eventversion->id . '">
                            <button style="font-size: 0.8rem; border-radius: 0.5rem; background-color: lightyellow;">
                                Payment
                            </button>
                        </a>
                    </td>';

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

    private function teacherEmailsTitle(array $row): string
    {
        $a = [];

        if(strlen($row['emailWork'])){ $a[] = $row['emailWork'];}
        if(strlen($row['emailPersonal'])){ $a[] = $row['emailPersonal'];}
        if(strlen($row['emailOther'])){ $a[] = $row['emailOther'];}

        return implode(', ',$a);
    }

    /**
     * If audition package is complete, display checkmark, else 'done' button
     * @param $row
     * @return string
     */
    private function verificationCheckmark(int $schoolId): string
    {
        $verified = SchoolVerified::where('school_id', $schoolId)
            ->where('eventversion_id', Userconfig::getValue('eventversion', auth()->id()))
            ->first();

        if($verified && $verified->verified){

            $button = '<a href="\registrationmanager\undone\\' . $schoolId . '"
                title="Click to UNverify the audition package."
            >';
            $button .= $this->checkmark;
            $button .= '</a>';

        }else {

            $button = '<a href="\registrationmanager\done\\' . $schoolId . '"
                title="Click to verify that the audition package is complete."
            >';
            $button .= '<button style="font-size: 0.66rem; border-radius: 0.5rem; background-color: mediumseagreen; color: white;">'
                . 'Done'
                . '</button>';
            $button .= '</a>';
        }

        return $button;
    }
}
