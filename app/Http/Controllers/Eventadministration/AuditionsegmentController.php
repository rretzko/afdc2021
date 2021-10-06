<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Filecontenttype;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class AuditionsegmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('eventadministration.auditionsegments.index',
        [
            'filecontenttypes' => Filecontenttype::orderBy('descr')->get(),
            'currentfilecontenttypes' => $eventversion->filecontenttypes,
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $segments = $request->validate([
            'filecontenttypes' => ['required','array'],
            'filecontenttypes.*' => ['required','numeric'],
        ]);

        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $eventversion->filecontenttypes()->sync($segments['filecontenttypes']);

        return redirect(route('eventadministrator.index'));
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
}
