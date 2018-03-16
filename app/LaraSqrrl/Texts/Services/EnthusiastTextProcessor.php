<?php namespace App\LaraSqrrl\Texts\Services;

use App\LaraSqrrl\Texts\Entities\IncomingTextObject;
use App\LaraSqrrl\Texts\Events\EnthusiastPictureReceived;
use App\LaraSqrrl\Users\User;

class EnthusiastTextProcessor {

    /**
     * Process an enthusiast's incoming text.
     *
     * @param IncomingTextObject $incomingText
     * @param User $user
     * @return string
     */
    public function process(IncomingTextObject $incomingText,
                            User $user)
    {
        // check if a picture was sent in message
        if ($incomingText->getNumMedia() < 1)
        {
            // no picture received
            return "We're not sure what you're trying to do! All you need to do is send us a photo of a potential squirrel to receive an answer from our squirrel identification system.";
        }

        // a picture has been receive, fire event
        event(new EnthusiastPictureReceived($incomingText, $user));

        // let user know their potential squirrel photo is in good hands
        return "We're analyzing your photo now! Just a few moments of suspense, and then you'll have your answer.";
    }

}
