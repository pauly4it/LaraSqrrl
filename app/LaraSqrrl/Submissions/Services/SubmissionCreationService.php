<?php namespace App\LaraSqrrl\Submissions\Services;

use App\LaraSqrrl\Submissions\Submission;
use App\LaraSqrrl\Texts\Entities\IncomingTextObject;
use App\LaraSqrrl\Users\User;
use Carbon\Carbon;
use Storage;

class SubmissionCreationService {

    /**
     * @var Submission
     */
    private $submission;

    /**
     * @param Submission $submission
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    /**
     * @param IncomingTextObject $incomingText
     * @param User $enthusiast
     * @return Submission
     */
    public function saveSubmission(IncomingTextObject $incomingText, User $enthusiast)
    {
        // extract media type of photo
        $mediaTypeArray = explode("/", $incomingText->getMediaType(0));
        $mediaType = $mediaTypeArray[count($mediaTypeArray) - 1];

        // build path for photo
        $date = Carbon::now();
        $path = '/user_submissions/' . $enthusiast->id . "/" . ($date->toDateString()) . '/' . ($date->format('His')) . '.' . $mediaType;

        // send photo to s3
        $s3 = Storage::disk('s3');
        $s3->put($path, file_get_contents($incomingText->getMediaUrl(0)));

        // build photo url
        $photo_url = 'https://s3.amazonaws.com/' . config('filesystems.disks.s3.bucket') . $path;

        // save submission
        $this->submission->create([
            'user_id' => $enthusiast->id,
            'photo_url' => $photo_url
        ]);

        // return saved photo name
        return substr($path, 1);
    }

}
