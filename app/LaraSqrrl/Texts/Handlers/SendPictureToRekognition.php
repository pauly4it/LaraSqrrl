<?php namespace App\LaraSqrrl\Texts\Handlers;

use App\LaraSqrrl\Submissions\Services\SubmissionCreationService;
use App\LaraSqrrl\Texts\Events\EnthusiastPictureReceived;
use App\LaraSqrrl\Twilio\Services\TwilioServiceProvider;
use App\LaraSqrrl\Users\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPictureToRekognition implements ShouldQueue {

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

        // create submission
        $submission = $this->submissionCreationService->saveSubmission($incomingText, $enthusiast);

        // send to Rekognition
        $rekognitionRawResult = \Rekognition::detectLabels([
            'Image' => [
                'S3Object' => [
                    'Bucket' => 'larasqrrl',
                    'Name' => $submission
                ]
            ]
        ]);

        // look through results for Squirrel label
        $message = "We have your results: it doesn't look like a squirrel to us.";
        foreach ($rekognitionRawResult['Labels'] as $label) {
            if ($label['Name'] == 'Squirrel') {
                if ($label['Confidence'] > 90) {
                    $results = "that is most definitely a squirrel!";
                } elseif ($label['Confidence'] > 75) {
                    $results = "we're pretty sure that's a squirrel.";
                } elseif ($label['Confidence'] > 50) {
                    $results = "that may be a squirrel.";
                } else {
                    $results = "we're not sure, but there's a chance there's a squirrel in there.";
                }
                $message = "The results are in: " . $results;
                break;
            }
        }

        // send text to experts with the picture
        $this->twilio->sendSMS(
            $enthusiast->phone,
            $message
        );
    }

}
