<?php namespace App\LaraSqrrl\Texts\Events;

use App\LaraSqrrl\Submissions\Submission;
use App\LaraSqrrl\Users\User;

class ExpertAnalysisReceived {

    /**
     * @var Submission
     */
    private $submission;
    /**
     * @var User
     */
    private $expert;
    private $analysis;

    /**
     * @param Submission $submission
     * @param User $expert
     * @param bool $analysis
     */
    public function __construct(Submission $submission,
                                User $expert,
                                $analysis)
    {
        $this->submission = $submission;
        $this->expert = $expert;
        $this->analysis = $analysis;
    }

    /**
     * @return Submission
     */
    public function getSubmission()
    {
        return $this->submission;
    }

    /**
     * @return User
     */
    public function getExpertUser()
    {
        return $this->expert;
    }

    /**
     * @return bool
     */
    public function wasSquirrel()
    {
        return $this->analysis == 'yes';
    }

}
