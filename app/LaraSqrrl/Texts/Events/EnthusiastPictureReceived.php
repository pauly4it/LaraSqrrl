<?php namespace App\LaraSqrrl\Texts\Events;

use App\LaraSqrrl\Texts\Entities\IncomingTextObject;
use App\LaraSqrrl\Users\User;

class EnthusiastPictureReceived {

    /**
     * @var IncomingTextObject
     */
    private $incomingText;
    /**
     * @var User
     */
    private $user;

    /**
     * @param IncomingTextObject $incomingText
     * @param User $user
     */
    public function __construct(IncomingTextObject $incomingText,
                                User $user)
    {
        $this->incomingText = $incomingText;
        $this->user = $user;
    }

    /**
     * @return IncomingTextObject
     */
    public function getIncomingText()
    {
        return $this->incomingText;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

}
