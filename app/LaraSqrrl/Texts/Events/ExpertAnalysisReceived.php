<?php namespace App\LaraSqrrl\Texts\Events;

use App\LaraSqrrl\Users\User;

class ExpertAnalysisReceived {

    /**
     * @var User
     */
    private $enthusiast;
    /**
     * @var User
     */
    private $expert;
    private $analysis;

    /**
     * @param User $enthusiast
     * @param User $expert
     * @param bool $analysis
     */
    public function __construct(User $enthusiast,
                                User $expert,
                                $analysis)
    {
        $this->enthusiast = $enthusiast;
        $this->expert = $expert;
        $this->analysis = $analysis;
    }

    /**
     * @return User
     */
    public function getEnthusiastUser()
    {
        return $this->enthusiast;
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
