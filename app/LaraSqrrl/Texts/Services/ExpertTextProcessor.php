<?php namespace App\LaraSqrrl\Texts\Services;

use App\LaraSqrrl\Texts\Entities\IncomingTextObject;
use App\LaraSqrrl\Texts\Events\ExpertAnalysisReceived;
use App\LaraSqrrl\Users\User;
use Validator;

class ExpertTextProcessor {

    /**
     * @var User
     */
    private $userModel;

    /**
     * @param User $userModel
     */
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Process an expert's incoming text.
     *
     * @param IncomingTextObject $incomingText
     * @param User $user
     * @return null
     */
    public function process(IncomingTextObject $incomingText,
                            User $user)
    {
        // validate if expert's text has required information
        $validation = $this->validateText($incomingText);

        if (!$validation['valid'])
        {
            // validation failed, return validation message
            return $validation['message'];
        }

        // expert analysis received, fire event
        event(new ExpertAnalysisReceived($validation['user'], $user, $validation['answer']));

        // return NULL and handle response to expert and enthusiast on event
        return NULL;
    }

    /**
     * Validate that an expert's text is a response to an enthusiast photo.
     *
     * @param IncomingTextObject $incomingText
     * @return array
     */
    private function validateText(IncomingTextObject $incomingText)
    {
        // break text into parts
        $text = explode(' ', $incomingText->getBody());

        // check if first string is an integer and an enthusiast user id
        if (!is_numeric($text[0]))
        {
            // not an integer
            return [
                'valid' => FALSE,
                'message' => "Please respond in the format: \"12345 Yes\", where 12345 is the number you received with the photo."
            ];
        }
        elseif (!$enthusiast = $this->userModel->find($text[0]))
        {
            // enthusiast user not found from integer provided
            return [
                'valid' => FALSE,
                'message' => "We couldn't find that reference number!"
            ];
        }

        // validate second part of text
        if (strtolower($text[1]) !== 'yes' AND strtolower($text[1]) !== 'no')
        {
            return [
                'valid' => FALSE,
                'message' => "Please respond in the format: \"12345 Yes\" or \"12345 No\", where 12345 is the number you received with the photo."
            ];
        }

        return [
            'valid' => TRUE,
            'user' => $enthusiast,
            'answer' => strtolower($text[1])
        ];
    }

}
