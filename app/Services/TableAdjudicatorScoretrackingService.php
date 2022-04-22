<?php

namespace App\Services;


use App\Models\Eventversion;

class TableAdjudicatorScoretrackingService
{
    private $eventversion;
    private $table;

    public function __construct(Eventversion $eventversion)
    {
        $this->eventversion = $eventversion;
        $this->table = 'insert table here';

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

    private function rows(): string
    {
        $str = '';

        foreach ($this->eventversion->rooms as $room){

            $str .= '<tr style="background-color: rgba(0,0,0,0.1);">';

            $str .= '<td colspan=4"><b>'.$room->descr.'</b></td>';

            $str .= '</tr>';

            foreach ($room->adjudicators->sortBy('adjudicatorname') as $adjudicator) {

                $count = $adjudicator->room->auditioneesCount();
                $scored = $adjudicator->room->auditioneesScoredCountByAdjudicator($adjudicator);
                $pct = ($scored) ? (number_format((($scored / $count) * 100), 1).'%') : '0%';

                //background-color
                if(! $scored) {
                    $bg = 'rgba(255,0,0,0.1)'; //red
                }elseif( $count > $scored){
                    $bg = 'rgba(245,243,39,0.1)'; //yellow
                }elseif( $count === $scored){
                    $bg ='rgba(0,255,0,0.1);'; //green
                }else {
                    $bg = 'red'; //bright red
                }

                $str .= '<tr style="background-color: '.$bg.'">';

                $str .= '<td style="padding-left: 1rem;">' . $adjudicator->adjudicatorname . '</td>';
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
