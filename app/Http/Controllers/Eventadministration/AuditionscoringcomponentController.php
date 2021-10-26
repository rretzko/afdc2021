<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Filecontenttype;
use App\Models\Scoringcomponent;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class AuditionscoringcomponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        return view('eventadministration.scoring.components.index',
            [
                'currentfilecontenttypes' => $eventversion->filecontenttypes,
                'eventversion' => $eventversion,
                'scoringcomponent' => null,
                'scoringcomponents' => Scoringcomponent::where('eventversion_id', $eventversion->id)
                    ->orderBy('order_by')
                    ->get(),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Eventversion $eventversion)
    {
        $inputs = $request->validate([
            'filecontenttype_id' => ['required','numeric'],
            'descr' => ['required','string'],
            'abbr' => ['required','string'],
            'order_by' => ['required','numeric'],
            'bestscore' => ['required','numeric'],
            'worstscore' => ['required','numeric'],
            'interval' => ['required','numeric'],
            'tolerance' => ['required','numeric'],
        ]);

        Scoringcomponent::create([
            'eventversion_id' => $eventversion->id,
            'filecontenttype_id' => $inputs['filecontenttype_id'],
            'descr' => $inputs['descr'],
            'abbr' => $inputs['abbr'],
            'order_by' => $inputs['order_by'],
            'bestscore' => $inputs['bestscore'],
            'worstscore' => $inputs['worstscore'],
            'interval' => $inputs['interval'],
            'tolerance' => $inputs['tolerance']
        ]);
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
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('eventadministration.scoring.components.index',
            [
                'currentfilecontenttypes' => $eventversion->filecontenttypes,
                'eventversion' => $eventversion,
                'scoringcomponent' => Scoringcomponent::find($id),
                'scoringcomponents' => Scoringcomponent::where('eventversion_id', $eventversion->id)
                    ->orderBy('filecontenttype_id')
                    ->orderBy('order_by')
                    ->get(),
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Eventversion $eventversion
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Eventversion $eventversion, $id )
    {
        if($id) {
            $inputs = $request->validate([
                'filecontenttype_id' => ['required', 'numeric'],
                'descr' => ['required', 'string'],
                'abbr' => ['required', 'string'],
                'order_by' => ['required', 'numeric'],
                'bestscore' => ['required', 'numeric'],
                'worstscore' => ['required', 'numeric'],
                'interval' => ['required', 'numeric'],
                'tolerance' => ['required', 'numeric'],
            ]);

            $scoringcomponent = Scoringcomponent::find($id);

            $scoringcomponent->update([
                'eventversion_id' => $eventversion->id,
                'filecontenttype_id' => $inputs['filecontenttype_id'],
                'descr' => $inputs['descr'],
                'abbr' => $inputs['abbr'],
                'order_by' => $inputs['order_by'],
                'bestscore' => $inputs['bestscore'],
                'worstscore' => $inputs['worstscore'],
                'interval' => $inputs['interval'],
                'tolerance' => $inputs['tolerance']
            ]);

        }else{ //new component

            $this->store($request, $eventversion);
        }

        return $this->index($eventversion);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $eventversion = Eventversion::find(Scoringcomponent::find($id)->eventversion_id);

        Scoringcomponent::destroy($id);

        return $this->index($eventversion);
    }
}
