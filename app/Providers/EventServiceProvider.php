<?php

namespace App\Providers;

use App\Events\SendLiveMassmailingEvent;
use App\Events\SendTestMassmailingEvent;
use App\Events\UpdateScoresummaryAlternatingCutoffEvent;
use App\Events\UpdateScoresummaryCutoffEvent;
use App\Listeners\SendLiveMassmailingListener;
use App\Listeners\SendTestMassmailingListener;
use App\Listeners\UpdateScoresummaryAlternatingCutoffListener;
use App\Listeners\UpdateScoresummaryCutoffListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SendLiveMassmailingEvent::class =>[
            SendLiveMassmailingListener::class,
        ],
        SendTestMassmailingEvent::class =>[
            SendTestMassmailingListener::class,
        ],
        UpdateScoresummaryCutoffEvent::class => [
            UpdateScoresummaryCutoffListener::class,
        ],
        UpdateScoresummaryAlternatingCutoffEvent::class => [
            UpdateScoresummaryAlternatingCutoffListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
