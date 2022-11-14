<?php

namespace App\Services;


use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\Registranttype;
use Illuminate\Support\Facades\DB;

class TableAdjudicatorScoretrackingService
{
    private $eventversion;
    private $registrants;
    private $registrants_count;
    private $registrants_scored_count;
    private $registrants_scored_pct;
    private $table;

    public function __construct(Eventversion $eventversion)
    {
        $this->eventversion = $eventversion;
        $this->table = 'insert table here';

        $this->init();

        $this->buildTable();
    }

    public function table()
    {
        return $this->table;
    }

/** END OF PUBLIC FUNCTIONS  =========================================================================================*/

    private function buildTable()
    {
        $this->table = $this->style();

        $this->table .= '<table>';

        $this->table .= '<thead>';

        $this->table .= $this->headers();

        $this->table .= '</thead>';

        $this->table .= '<body>';

        $this->table .= $this->rows();

        $this->table .= '</body>';

        $this->table .= '</table>';
    }

    private function headers(): string
    {
        $str = '<tr>';
        $str .= '<th style="padding-left: 1rem;">Name</th>';
        $str .= '<th>Registrants</th>';
        $str .= '<th>Scored</th>';
        $str .= '<th>Pct</th>';
        $str .= '</tr>';

        return $str;
    }

    private function init()
    {
        $this->registrants = Registrant::where('eventversion_id', $this->eventversion->id)
            ->where('registranttype_id', Registranttype::REGISTERED)
            ->get();

        $this->registrants_count = $this->registrants->count() ?: 0;

        $this->registrants_scored_count = $this->registrantsScoredCount($this->registrants->first()->eventversion_id);

        $this->registrants_scored_pct = $this->registrants_count
            ? number_format((($this->registrants_scored_count / $this->registrants_count) * 100), 0)
            : 0;

        $this->scored = 'something';
    }

    private function registrantsScoredCount(int $eventversion_id): int
    {

        return DB::table('scores')
            ->select('registrant_id')
            ->where('eventversion_id', $eventversion_id)
            ->distinct()
            ->count('registrant_id');
    }

    private function rows(): string
    {
        $str = '';

        $str .= '<tr style="background-color: black; color: white;">';
        $str .= '<td style="border: 1px solid lightgrey;">Event-wide</td>';
        $str .= '<td style="border: 1px solid lightgrey;" class="text-center">'.$this->registrants_count.'</td>';
        $str .= '<td style="border: 1px solid lightgrey;" class="text-center">'.$this->registrants_scored_count.'</td>';
        $str .= '<td style="border: 1px solid lightgrey;" class="text-center">'.$this->registrants_scored_pct.'%</td>';
        $str .= '</tr>';

        foreach ($this->eventversion->rooms as $room){

            $str .= '<tr style="background-color: rgba(0,0,0,0.1);">';

            $str .= '<td colspan=4"><b>'.$room->descr.'</b></td>';

            $str .= '</tr>';

            foreach ($room->adjudicators->sortBy('adjudicatorname') as $adjudicator) {

                $email = $adjudicator->person->subscriberemailpersonal ?? $adjudicator->person->subscriberemailwork;
                $count = $adjudicator->room->auditioneesCount();
                $scored = $adjudicator->room->auditioneesScoredCountByAdjudicator($adjudicator);
                $pct = ($scored) ? (number_format((($scored / $count) * 100), 1).'%') : '0%';

                //background-color
                if(! $scored) {
                    $bg = 'rgba(255,0,0,0.1)'; //red
                    $acolor = 'darkred';
                }elseif( $count > $scored){
                    $bg = 'rgba(245,243,39,0.1)'; //yellow
                    $acolor = 'blue';
                }elseif( $count === $scored){
                    $bg ='rgba(0,255,0,0.1);'; //green
                    $acolor = 'darkgreen';
                }else {
                    $bg = 'red'; //bright red
                    $acolor = 'black';
                }

                $str .= '<tr style="background-color: '.$bg.'">';

                $str .= '<td style="padding-left: 1rem;">'
                    . '<a href="mailto:'.$email.'?subject=All-State Auditions&body=Hi, '.$adjudicator->person->first.'"'
                    . 'style="color: '.$acolor.'">'
                    . $adjudicator->adjudicatorname
                    . '</a></td>';
                $str .= '<td style="text-align: center;">'.$count . '</td>';
                $str .= '<td style="text-align: center;">'.$scored.'</td>';
                $str .= '<td style="text-align: center;">'.$pct.'</td>';

                $str .= '</tr>';
            }
        }

        return $str;
    }

    private function style(): string
    {
        $str = '<style>';

        $str .= 'table{margin-bottom: 1rem;}';

        $str .= 'td,th{border: 1px solid black; padding: 0 .25rem;';

        $str .= '.indent{padding-left: 1rem;}';

        $str .= '</style>';

        return $str;
    }
}
