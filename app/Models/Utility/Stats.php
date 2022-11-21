<?php

namespace App\Models\Utility;

use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\Registranttype;
use App\Models\Userconfig;
use AWS\CRT\Auth\SignatureType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stats extends Model
{
    use HasFactory;

    public static function applicationCount()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $eventversionid = $eventversion->id;
        $min = (($eventversionid * 10000) - 1);
        $max = (($eventversionid + 1) * 10000);

       return ($eventversion->eventversionconfig->eapplication)

            ? DB::table('eapplications')
                ->where('registrant_id','>',$min)
                ->where('registrant_id','<', $max)
                ->where('signatureguardian','=',1)
                ->where('signaturestudent','=',1)
                ->distinct()
                ->count('registrant_id')

           : DB::table('registrants')
            ->where('eventversion_id', $eventversion->id)
            ->where('registranttype_id', Registranttype::APPLIED)
            ->orWhere(function($query) use($eventversion){
                $query->where('eventversion_id', $eventversion->id)->where('registranttype_id',Registranttype::REGISTERED);
            })
            ->count();

    }

    public static function applicationCountsByInstrumentation()
    {
        $a = [];
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $min = (($eventversion->id * 10000) - 1);
        $max = (($eventversion->id + 1) * 10000);

        foreach($eventversion->instrumentations() AS $instrumentation){

            $a[$instrumentation->id] = DB::table('registrants')
                ->join('instrumentation_registrant', function($join) use($instrumentation){
                    $join->on('registrants.id', '=', 'instrumentation_registrant.registrant_id')
                        ->where('instrumentation_registrant.instrumentation_id','=',$instrumentation->id);
                })
                ->where('registrants.eventversion_id', '=', $eventversion->id)
                ->where('registranttype_id','=',Registranttype::APPLIED)
                ->orWhere(function($query) use($eventversion){
                    $query->where('eventversion_id', $eventversion->id)->where('registranttype_id',Registranttype::REGISTERED);
                })
                ->count('registrants.id');
        }

        return $a;
    }

    /**
     * Count of applicants who have uploaded and have approved the full set of recordings for $eventversion
     */
    public static function fullRecordingsCount(Eventversion $eventversion): int
    {
        $file_content_types_count = $eventversion->filecontenttypes->count();
        $min = (($eventversion->id * 10000) - 1);
        $max = (($eventversion->id + 1) * 10000);

        $count =  DB::table('applications')
            ->join('fileuploads','applications.registrant_id','=','fileuploads.registrant_id')
            ->where('applications.registrant_id','>',$min)
            ->where('applications.registrant_id','<', $max)
            ->where('fileuploads.approved','>',1)
            ->distinct()
            ->count('applications.registrant_id');

        $rows =  DB::table('applications')
            ->join('fileuploads','applications.registrant_id','=','fileuploads.registrant_id')
            ->where('applications.registrant_id','>',$min)
            ->where('applications.registrant_id','<', $max)
            ->whereNotNull('fileuploads.approved')
            ->groupBy('applications.registrant_id','fileuploads.filecontenttype_id')
            ->get('applications.registrant_id','filecontenttype_id');

        //create array of registrant_ids with the requisite number of files
        $a=[];
        foreach($rows AS $row){

            if(self::hasRequisiteFiles($file_content_types_count,$row)){
                 $a[$row->registrant_id] = $file_content_types_count;
             }
        }

        return count($a) ?: 0;
    }

    /**
     * Count of applicants who have uploaded and have approved the full set of recordings for $eventversion
     * by instrumentation
     */
    public static function fullRecordingsCountByInstrumentation(Eventversion $eventversion): array
    {
        $min = (($eventversion->id * 10000) - 1);
        $max = (($eventversion->id + 1) * 10000);

        //registrant_id + filecontenttype_id for all registrants with approved fule uploads
        $rows =  DB::table('applications')
            ->join('fileuploads','applications.registrant_id','=','fileuploads.registrant_id')
            ->where('applications.registrant_id','>',$min)
            ->where('applications.registrant_id','<', $max)
            ->whereNotNull('fileuploads.approved')
            ->groupBy('applications.registrant_id','fileuploads.filecontenttype_id')
            ->get('applications.registrant_id','filecontenttype_id');

        //determine if 1 or more files exist for each $row
        $a=[];
        foreach($rows AS $row){
            array_key_exists($row->registrant_id, $a)
                ? ($a[$row->registrant_id] = ($a[$row->registrant_id] + 1))
                : $a[$row->registrant_id] = 1;
        }

        //filter out any registrant without the requisite number of approved file uploads
        $counts = [];
        foreach($a AS $registrantid => $count) {

            if($count == $eventversion->filecontenttypes()->count()) {

                $r = Registrant::find($registrantid);
                $instrumentationid = $r->instrumentations->first()->id;

                array_key_exists($instrumentationid, $counts)
                    ? $counts[$instrumentationid] = $counts[$instrumentationid] + 1
                    : $counts[$instrumentationid] = 1;
            }

        }

        return $counts;
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

    public static function registeredStudentsCount(Eventversion $eventversion): int
    {
        return Registrant::where('eventversion_id', $eventversion->id)
            ->where('registranttype_id', Registranttype::REGISTERED)
            ->count('id');
    }

    public static function registrantCountsByInstrumentation(Eventversion $eventversion)
    {
        $a = [];
        $min = (($eventversion->id * 10000) - 1);
        $max = (($eventversion->id + 1) * 10000);

        foreach($eventversion->instrumentations() AS $instrumentation){

            $a[$instrumentation->id] = DB::table('registrants')
                ->join('instrumentation_registrant', function($join) use($instrumentation){
                    $join->on('registrants.id', '=', 'instrumentation_registrant.registrant_id')
                        ->where('instrumentation_registrant.instrumentation_id','=',$instrumentation->id);
                })
                ->where('registrants.eventversion_id','=', $eventversion->id)
                ->where('registranttype_id', '=', Registranttype::REGISTERED)
                ->distinct()
                ->count('registrants.id');
            /*$a[$instrumentation->id] = DB::table('registrants')
                ->join('instrumentation_registrant', function($join) use($instrumentation){
                    $join->on('registrants.id', '=', 'instrumentation_registrant.registrant_id')
                        ->where('instrumentation_registrant.instrumentation_id','=',$instrumentation->id);
                })
                ->where('registrants.id','>',$min)
                ->where('registrants.id','<', $max)
                ->where('registranttype_id', '=', Registranttype::REGISTERED)
                ->distinct()
                ->count('registrants.id');*/
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

    private static function hasRequisiteFiles(int $files_needed,\stdClass $row): bool
    {
        $files_available = DB::table('fileuploads')
            ->where('registrant_id', $row->registrant_id)
            ->whereNotNull('approved')
            ->count('registrant_id');

        return ($files_needed === $files_available);
    }
}
