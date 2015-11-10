<?php namespace App\LaraSqrrl\Twilio\Services;

use Services_Twilio_Twiml;

class TwimlFormatter {

    /**
     * @var Services_Twilio_Twiml
     */
    private $twilml;
    private $fromNumber;

    public function __construct()
    {
        $this->twilml = new Services_Twilio_Twiml();
        $this->fromNumber = config('services.twilio.phone_number');
    }

    /**
     * Format a text response into the Twiml format.
     *
     * @param $message
     * @param $toPhone
     * @return Services_Twilio_Twiml
     */
    public function format($message, $toPhone)
    {
        $this->twilml->message(
            $message,
            [
                'to' => $toPhone,
                'from' => $this->fromNumber
            ]
        );

        return $this->twilml;
    }

}
