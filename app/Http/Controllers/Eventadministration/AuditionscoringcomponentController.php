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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            'eventversion_id' => Userconfig::getValue('eventversion', auth()->id()),
            'filecontenttype_id' => $inputs['filecontenttype_id'],
            'descr' => $inputs['descr'],
            'abbr' => $inputs['abbr'],
            'order_by' => $inputs['order_by'],
            'bestscore' => $inputs['bestscore'],
            'worstscore' => $inputs['worstscore'],
            'interval' => $inputs['interval'],
            'tolerance' => $inputs['tolerance']
        ]);

        return $this->index();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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

        $scoringcomponent = Scoringcomponent::find($id);

        $scoringcomponent->update([
            'eventversion_id' => Userconfig::getValue('eventversion', auth()->id()),
            'filecontenttype_id' => $inputs['filecontenttype_id'],
            'descr' => $inputs['descr'],
            'abbr' => $inputs['abbr'],
            'order_by' => $inputs['order_by'],
            'bestscore' => $inputs['bestscore'],
            'worstscore' => $inputs['worstscore'],
            'interval' => $inputs['interval'],
            'tolerance' => $inputs['tolerance']
        ]);

        return $this->index();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Scoringcomponent::destroy($id);

        return $this->index();
    }
}
