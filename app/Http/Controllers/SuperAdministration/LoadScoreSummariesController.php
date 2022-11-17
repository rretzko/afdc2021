<?php

namespace App\Http\Controllers\SuperAdministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\Score;
use App\Models\Scoresummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadScoreSummariesController extends Controller
{
    public function index()
    {
        return view('sa.loadscoresummaries.index',
        [
            'eventversions' => Eventversion::all(),
            'eventversion' => Eventversion::find(72),
        ]);
    }

    /**
     * THIS IS SPECIFIC TO NJ ALL-SHORE CHORUS FOR 2022-23
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        if($request['eventversion_id'] == 72) {
            $eventversion = Eventversion::find($request['eventversion_id']);

            $registrant_ids = DB::table('scores')
                ->select('registrant_id')
                ->where('eventversion_id', $eventversion->id)
                ->distinct()
                ->pluck('registrant_id');

            foreach($registrant_ids AS $id){

                $instrumentation_id = Registrant::find($id)->instrumentations->first()->id;

                Scoresummary::updateOrCreate(
                    [
                        'eventversion_id' => $request['eventversion_id'],
                        'registrant_id' => $id,
                        'instrumentation_id' => $instrumentation_id,
                    ],
                    [
                        'score_total' => Score::where('registrant_id', $id)->sum('score'),
                        'score_count' => Score::where('registrant_id', $id)->count('score'),
                    ]
                );
            }
        }

        return view('sa.loadscoresummaries.index',
            [
                'eventversions' => Eventversion::all(),
                'eventversion' => Eventversion::find(72),
            ]);
    }
}
