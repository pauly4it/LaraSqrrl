<?php namespace App\LaraSqrrl\Texts\Handlers;

use App\LaraSqrrl\Texts\Events\ExpertAnalysisReceived;
use App\LaraSqrrl\Twilio\Services\TwilioServiceProvider;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNutsToExpert implements ShouldQueue {

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
     * Send an MMS with nuts to an expert user.
     *
     * @param ExpertAnalysisReceived $event
     */
    public function handle(ExpertAnalysisReceived $event)
    {
        // grab the expert and enthusiast user info
        $expert = $event->getExpertUser();
        $enthusiast = $event->getEnthusiastUser();

        // set up message and acorns picture URL
        $message = "Your expert analysis is off to " . $enthusiast->name . "! You just earned 2 nuts!";
        $acornsURL = env('APP_URL') . "/images/acorns.png";

        // give the expert 2 nuts for the analysis
        $expert->addNuts(2);

        // send MMS to expert
        $this->twilio->sendMMS(
            $expert->phone,
            $message,
            $acornsURL
        );
    }

}
