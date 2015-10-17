<?php namespace App\LaraSqrrl\Twilio\Services;

class TwilioServiceBuilder {

    protected $phoneNumber;
    protected $accountSID;
    protected $authToken;

    public function __construct()
    {
        $this->accountSID = config('services.twilio.account_sid');
        $this->authToken = config('services.twilio.auth_token');
        $this->phoneNumber = config('services.twilio.phone_number');
    }

    /**
     * Build the Twilio Service.
     *
     * @return \Services_Twilio
     */
    public function build()
    {
        return new \Services_Twilio($this->accountSID, $this->authToken);
    }

    /**
     * Get the Twilio account phone number.
     *
     * @return mixed
     */
    public function getNumber()
    {
        return $this->phoneNumber;
    }

}
