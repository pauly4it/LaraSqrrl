<?php namespace App\LaraSqrrl\Texts\Handlers;

use App\LaraSqrrl\Texts\Events\ExpertAnalysisReceived;
use App\LaraSqrrl\Twilio\Services\TwilioServiceProvider;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendExpertAnalysisToEnthusiast implements ShouldQueue {

    /**
     * @var TwilioServiceProvider
     */
    private $twilio;

    /**
     * @param TwilioServiceProvider $twilio
     */
    public function __construct(TwilioServiceProvider $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * Send an SMS to an enthusiast with an expert's analysis.
     *
     * @param ExpertAnalysisReceived $event
     */
    public function handle(ExpertAnalysisReceived $event)
    {
        // grab the expert and enthusiast user info
        $expert = $event->getExpertUser();
        $enthusiast = $event->getEnthusiastUser();

        // set message
        if ($event->wasSquirrel())
        {
            $message = $expert->name . " says that was a squirrel in your photo! You just made " . $expert->name . " nuts!";
        }
        else
        {
            $message = $expert->name . " says it wasn't a squirrel in your photo. Better luck next time.";
        }

        // send SMS to enthusiast
        $this->twilio->sendSMS(
            $enthusiast->phone,
            $message
        );
    }

}
