<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;

    private $event;
    private $members;
    private $nonefound;
    private $organization;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->init();
    }

    /**
     * @return mixed integer count of invitations extended or default string
     */
    public function countinvitations()
    {
        return $this->organization->currentEvent()->currentEventversion()->countInvitations() ?: $this->nonefound;
    }

    /**
     * @return mixed integer count of memberships or default string
     */
    public function countmemberships()
    {
        return $this->organization->memberships->count() ?: $this->nonefound;
    }

    /**
     * @return mixed Event object or default string
     */
    public function event()
    {
        return $this->organization->currentEvent() ?: $this->nonefound;
    }

    /**
     * @return mixed Event object or default string
     */
    public function eventversion()
    {
        return $this->organization->currentEvent()->currentEventversion() ?: $this->nonefound;
    }

    /**
     * @return mixed Organization object or default string
     */
    public function organization()
    {
        return $this->organization ?: $this->nonefound;
    }

/** END OF PUBLIC FUNCTIONS **************************************************/

    private function init()
    {
        $this->nonefound = 'None found';
        $this->organization = Organization::with('memberships')
            ->where('id', Userconfig::getValue('organization', auth()->id()))
            ->first();
    }
}
