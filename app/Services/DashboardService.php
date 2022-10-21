<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Eapplication;
use App\Models\Eventversion;
use App\Models\Fileupload;
use App\Models\Obligation;
use App\Models\Registrant;
use App\Models\Signature;
use App\Models\Userconfig;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private $eventversion;
    private $eventversion_id;
    private $filecontenttypes_count;
    private $min_registrant_id;
    private $max_registrant_id;

    public function __construct()
    {
        $this->eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $this->eventversion_id = $this->eventversion->id;
        $this->filecontenttypes_count = $this->eventversion->filecontenttypes->count();
        $this->min_registrant_id = ($this->eventversion_id * 10000);
        $this->max_registrant_id = ($this->min_registrant_id + 9999);
    }

    /**
     * Return count of unique students with all required files uploaded and all files approved
     * @return int
     */
    public function allFilesApproved(): int
    {
        $raw = "SELECT a.registrant_id
        FROM fileuploads a
            JOIN fileuploads b ON a.registrant_id=b.registrant_id AND b.filecontenttype_id=5 AND b.approved IS NOT NULL
        WHERE a.registrant_id >= 730000
        AND a.registrant_id <= 739999
        AND a.filecontenttype_id=1
        AND a.approved IS NOT NULL
        GROUP BY a.registrant_id";

        $records = DB::select($raw);

        return count($records);
    }

    /**
     * Return count of unique students with all required files uploaded
     * @return int
     */
    public function allUploadedFiles(): int
    {
        $raw = "SELECT COUNT(a.registrant_id)
        FROM fileuploads a
            JOIN fileuploads b ON a.registrant_id=b.registrant_id AND b.filecontenttype_id=5
        WHERE a.registrant_id >= 730000
        AND a.registrant_id <= 739999
        AND a.filecontenttype_id=1
        GROUP BY a.registrant_id";

        $records = DB::select($raw);

        return count($records);
    }

    /**
     * Return count of unique students with at least on file uploaded
     * @return int
     */
    public function atLeastOneUploadedFile(): int
    {
        return Fileupload::where('registrant_id', '>=', $this->min_registrant_id)
            ->where('registrant_id', '<=', $this->max_registrant_id)
            ->distinct('registrant_id')
            ->count() ?? 0;
    }

    /**
     * Return the count of membership for $this->>eventversion organization
     * @return int
     */
    public function invitedDirectors(): int
    {
        return $this->eventversion
            ->event
            ->organization
            ->memberships
            ->count() ?? 0;
    }

    /**
     * Return count of directors that have clicked through the Obligations page
     * @return int
     */
    public function obligatedDirectors(): int
    {
        return Obligation::where('eventversion_id', $this->eventversion_id)
            ->where('acknowledgment',1)
            ->distinct('user_id')
            ->count() ?? 0;
    }

    /**
     * Return count of students with signed application/eapplication
     * @return int
     */
    public function signedApplications(): int
    {
        if($this->eventversion->eventversionconfig->eapplication) {
            return Eapplication::where('eventversion_id',$this->eventversion_id)
                ->where('signatureguardian',1)
                ->where('signaturestudent',1)
                ->count() ?? 0;
        }else{

            //determine min/max of registrant ids
            $min = (($this->eventversion_id * 10000) - 1); //ex. 739999
            $max = (($this->eventversion_id + 1) * 10000); //ex. 750000

            //get array of registrant_ids with a downloaded application
            $registrant_ids =  Application::where('registrant_id', '>', $min)
                ->where('registrant_id', '<', $max)
                ->distinct('registrant_id')
                ->pluck('registrant_id');

            //count how many applications have the approved four signatures
            $signature = new Signature;
            $count = 0;
            foreach($registrant_ids AS $registrant_id){

                //@todo replace constant '4' with configuration determined number of signatures
                if($signature->signatureCount(Registrant::find($registrant_id)) === 4){

                    $count++;
                }
            }

            return $count;

        }
    }
}
