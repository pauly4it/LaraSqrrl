<?php namespace App\LaraSqrrl\Texts\Handlers;

use App\LaraSqrrl\Submissions\Services\SubmissionCreationService;
use App\LaraSqrrl\Texts\Events\EnthusiastPictureReceived;
use App\LaraSqrrl\Twilio\Services\TwilioServiceProvider;
use App\LaraSqrrl\Users\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Storage;
use Carbon\Carbon;

class SendPictureToExperts implements ShouldQueue {

    /**
     * @var User
     */
    private $userModel;
    /**
     * @var TwilioServiceProvider
     */
    private $twilio;
    /**
     * @var SubmissionCreationService
     */
    private $submissionCreationService;

    /**
     * @param User $userModel
     * @param TwilioServiceProvider $twilio
     * @param SubmissionCreationService $submissionCreationService
     */
    public function __construct(User $userModel,
                                TwilioServiceProvider $twilio,
                                SubmissionCreationService $submissionCreationService)
    {
        $this->userModel = $userModel;
        $this->twilio = $twilio;
        $this->submissionCreationService = $submissionCreationService;
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

        // create submission
        $submission = $this->submissionCreationService->saveSubmission($incomingText, $enthusiast);

        // build message
        $message = "#" . $submission->id . " Is this a squirrel? Respond: \"" . $submission->id . " Yes\" or \"" . $submission->id . " No\"";

        // send text to experts with the picture
        foreach ($experts as $expert)
        {
            $this->twilio->sendMMS(
                $expert->phone,
                $message,
                $submission->photo_url
            );
        }
    }

}
