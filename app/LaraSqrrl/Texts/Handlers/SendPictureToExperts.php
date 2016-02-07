<?php namespace App\LaraSqrrl\Texts\Handlers;

use App\LaraSqrrl\Texts\Events\EnthusiastPictureReceived;
use App\LaraSqrrl\Twilio\Services\TwilioServiceProvider;
use App\LaraSqrrl\Users\User;
use Storage;
use Carbon\Carbon;

class SendPictureToExperts {

    /**
     * @var User
     */
    private $userModel;
    /**
     * @var TwilioServiceProvider
     */
    private $twilio;

    /**
     * @param User $userModel
     * @param TwilioServiceProvider $twilio
     */
    public function __construct(User $userModel,
                                TwilioServiceProvider $twilio)
    {
        $this->userModel = $userModel;
        $this->twilio = $twilio;
    }

    /**
     * Send a potential squirrel photo to an expert.
     *
     * @param EnthusiastPictureReceived $event
     */
    public function handle(EnthusiastPictureReceived $event)
    {
        // extract data from event
        $incomingText = $event->getIncomingText();
        $enthusiast = $event->getUser();

        // retrieve all expert users
        $experts = $this->userModel->getAllExperts();

        // build message
        $message = "#" . $enthusiast->id . " Is this a squirrel? Respond: \"" . $enthusiast->id . " Yes\" or \"" . $enthusiast->id . " No\"";

        $mediaTypeArray = explode("/", $incomingText->getMediaType(0));
        $mediaType = $mediaTypeArray[count($mediaTypeArray) - 1];
        $date = Carbon::now();
        $path = '/user_submissions/' . $enthusiast->id . "/" . ($date->toDateString()) . '/' . ($date->format('His')) . $mediaType;
        $s3 = Storage::disk('s3');
        $s3->put($path, file_get_contents($incomingText->getMediaUrl(0)));
        $photo_url = 'https://s3.amazonaws.com/' . config('filesystems.disks.s3.bucket') . $path;

        // send text to experts with the picture
        foreach ($experts as $expert)
        {
            $this->twilio->sendMMS(
                $expert->phone,
                $message,
                $photo_url
            );
        }
    }

}
