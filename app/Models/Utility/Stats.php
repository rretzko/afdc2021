<?php

namespace App\Models\Utility;

use App\Models\Eventversion;
use App\Models\Userconfig;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stats extends Model
{
    use HasFactory;

    public static function applicationCount()
    {
        $eventversionid = Userconfig::getValue('eventversion', auth()->id());
        $min = (($eventversionid * 10000) - 1);
        $max = (($eventversionid + 1) * 10000);

        return DB::table('applications')
            ->where('registrant_id','>',$min)
            ->where('registrant_id','<', $max)
            ->distinct()
            ->count('registrant_id');
    }

    public static function applicationCountsByInstrumentation()
    {
        $a = [];
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $min = (($eventversion->id * 10000) - 1);
        $max = (($eventversion->id + 1) * 10000);

        foreach($eventversion->instrumentations() AS $instrumentation){

            $a[$instrumentation->id] = DB::table('applications')
                ->join('instrumentation_registrant', function($join) use($instrumentation){
                    $join->on('applications.registrant_id', '=', 'instrumentation_registrant.registrant_id')
                        ->where('instrumentation_registrant.instrumentation_id','=',$instrumentation->id);
                })
                ->where('applications.registrant_id','>',$min)
                ->where('applications.registrant_id','<', $max)
                ->distinct()
                ->count('applications.registrant_id');
        }

        return $a;
    }

    /**
     * At least one recording uploaded
     * @return int
     */
    public static function minRecordingCount()
    {
        $eventversionid = Userconfig::getValue('eventversion', auth()->id());
        $min = (($eventversionid * 10000) - 1);
        $max = (($eventversionid + 1) * 10000);

        return DB::table('applications')
            ->join('fileuploads','applications.registrant_id','=','fileuploads.registrant_id')
            ->where('applications.registrant_id','>',$min)
            ->where('applications.registrant_id','<', $max)
            ->distinct()
            ->count('applications.registrant_id');
    }

    public static function minRecordingCountByInstrumentation()
    {
        $a = [];
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $min = (($eventversion->id * 10000) - 1);
        $max = (($eventversion->id + 1) * 10000);

        foreach($eventversion->instrumentations() AS $instrumentation) {

            $a[$instrumentation->id] = DB::table('applications')
                ->join('fileuploads', 'applications.registrant_id', '=', 'fileuploads.registrant_id')
                ->join('instrumentation_registrant', function($join) use($instrumentation){
                    $join->on('applications.registrant_id', '=', 'instrumentation_registrant.registrant_id')
                        ->where('instrumentation_registrant.instrumentation_id', '=', $instrumentation->id);
                })
                ->where('applications.registrant_id', '>', $min)
                ->where('applications.registrant_id', '<', $max)
                ->distinct()
                ->count('applications.registrant_id');
        }

        return $a;
    }

    public static function schoolsWithApplicantsCount()
    {
        $eventversionid = Userconfig::getValue('eventversion', auth()->id());
        $min = (($eventversionid * 10000) - 1);
        $max = (($eventversionid + 1) * 10000);

        return DB::table('registrants')
            ->join('applications', 'registrants.id', '=', 'applications.registrant_id')
            ->where('registrants.id','>',$min)
            ->where('registrants.id','<', $max)
            ->distinct()
            ->count('registrants.school_id');
    }

}
