<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScoreRequest;
use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\Score;
use App\Models\Scoresummary;
use App\Models\Scoringcomponent;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('eventadministration.scores.create',
        [
            'eventversion' => Eventversion::find(Userconfig::getValue('eventversion', auth()->id())),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScoreRequest $request)
    {
        self::updateScoreAndSummary($request->all());

        session('success','Scores updated.');

        return redirect()->back()->with('success','Scores updated!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function updateScoreAndSummary(array $a)
    {
        $eventversion_id = Userconfig::getValue('eventversion',auth()->id());
        $instrumentation_id = Registrant::find($a['registration_id'])->instrumentations->first()->id;

        foreach($a AS $key => $value){

            if(strpos($key, 'core-') && (! is_null($value))){

                $scoringcomponentid = $this->map($eventversion_id, $key);
                $scoringcomponent = Scoringcomponent::find($scoringcomponentid);
                $multiplier = $scoringcomponent->multiplier;
                $score = ($value * $multiplier);

                Score::updateOrCreate(
                    [
                        'registrant_id' => $a['registration_id'],
                        'eventversion_id' => $eventversion_id,
                        'user_id' => $a['adjudicator_id'],
                        'scoringcomponent_id' => $scoringcomponentid,
                    ],
                    [
                        'score' => $score,
                        'proxy_id' => $a['adjudicator_id'],
                    ]
                );

                Scoresummary::updateOrCreate(
                    [
                        'eventversion_id' => $eventversion_id,
                        'registrant_id' => $a['registration_id'],
                        'instrumentation_id' => $instrumentation_id,
                    ],
                    [
                        'score_total' => Score::where('registrant_id', $a['registration_id'])->sum('score'),
                        'score_count' => Score::where('registrant_id', $a['registration_id'])->count('score'),
                        'result' => 'inc', //default
                    ]
                );

            }
        }
    }

    /**
     * Workaround for better form element labeling
     * @param string $lookup
     * @return void
     */
    private function map(int $eventversion_id, string $lookup)
    {
        $a = [];

        $a[72] = [
            'score-0' => 64, //lms
            'score-1' => 65, //hms
            'score-2' => 66, //cms,
            'score-3' => 67, //arp
            'score-4' => 69, //qry
            'score-5' => 68, //solo
        ];

        return $a[$eventversion_id][$lookup];
    }
}
