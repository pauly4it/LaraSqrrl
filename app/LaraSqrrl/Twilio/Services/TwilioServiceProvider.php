<?php namespace App\LaraSqrrl\Twilio\Services;

use Services_Twilio;

class TwilioServiceProvider {

    protected $phoneNumber;
    protected $accountSID;
    protected $authToken;
    /**
     * @var Services_Twilio
     */
    protected $client;

    public function __construct()
    {
        $this->accountSID = config('services.twilio.account_sid');
        $this->authToken = config('services.twilio.auth_token');
        $this->phoneNumber = config('services.twilio.phone_number');

        $this->client = new Services_Twilio($this->accountSID, $this->authToken);
    }

    /**
     * Send an SMS.
     *
     * @param int $userPhoneNumber
     * @param string $message
     * @return mixed
     */
    public function sendSMS($userPhoneNumber, $message)
    {
        return $this->client->account->messages->sendMessage(
            $this->phoneNumber,
            $userPhoneNumber,
            $message
        );
    }

    /**
     * Send an MMS.
     *
     * @param int $userPhoneNumber
     * @param string $message
     * @param string $imageURL
     * @return mixed
     */
    public function sendMMS($userPhoneNumber, $message, $imageURL)
    {
        return $this->client->account->messages->sendMessage(
            $this->phoneNumber,
            $userPhoneNumber,
            $message,
            $imageURL
        );
    }

}
