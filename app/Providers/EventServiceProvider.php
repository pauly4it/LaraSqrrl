<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\LaraSqrrl\Texts\Events\EnthusiastPictureReceived' => [
            'App\LaraSqrrl\Texts\Handlers\SendPictureToExperts',
        ],

        'App\LaraSqrrl\Texts\Events\ExpertAnalysisReceived' => [
            'App\LaraSqrrl\Texts\Handlers\SendExpertAnalysisToEnthusiast',
            'App\LaraSqrrl\Texts\Handlers\SendNutsToExpert',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
    }

}
