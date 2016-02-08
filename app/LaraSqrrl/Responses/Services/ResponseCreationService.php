<?php namespace App\LaraSqrrl\Responses\Services;

use app\LaraSqrrl\Responses\Response;
use App\LaraSqrrl\Submissions\Submission;
use App\LaraSqrrl\Users\User;

class ResponseCreationService {

    /**
     * @var Response
     */
    private $responseModel;

    /**
     * @param Response $responseModel
     */
    public function __construct(Response $responseModel)
    {
        $this->responseModel = $responseModel;
    }

    /**
     * @param Submission $submission
     * @param User $expert
     * @param boolean $wasSquirrel
     * @return static
     */
    public function saveResponse(Submission $submission, User $expert, $wasSquirrel)
    {
        $response = $this->responseModel->create([
            'submission_id' => $submission->id,
            'user_id' => $expert->id,
            'is_squirrel' => $wasSquirrel
        ]);

        return $response;
    }

}
