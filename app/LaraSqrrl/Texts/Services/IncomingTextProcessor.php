<?php namespace App\LaraSqrrl\Texts\Services;

use App\LaraSqrrl\Texts\Entities\IncomingTextObject;
use App\LaraSqrrl\Users\Services\UserRegistrationService;

class IncomingTextProcessor {

    /**
     * @var UserRegistrationService
     */
    private $registrationService;
    /**
     * @var EnthusiastTextProcessor
     */
    private $enthusiastTextProcessor;
    /**
     * @var ExpertTextProcessor
     */
    private $expertTextProcessor;

    /**
     * @param UserRegistrationService $registrationService
     * @param EnthusiastTextProcessor $enthusiastTextProcessor
     * @param ExpertTextProcessor $expertTextProcessor
     */
    public function __construct(UserRegistrationService $registrationService,
                                EnthusiastTextProcessor $enthusiastTextProcessor,
                                ExpertTextProcessor $expertTextProcessor)
    {
        $this->registrationService = $registrationService;
        $this->enthusiastTextProcessor = $enthusiastTextProcessor;
        $this->expertTextProcessor = $expertTextProcessor;
    }

    /**
     * Process an incoming text.
     *
     * @param IncomingTextObject $incomingText
     * @return array|null
     */
    public function process(IncomingTextObject $incomingText)
    {
        // complete a registration check
        $registrationCheck = $this->registrationService->checkRegistration($incomingText);
        if (is_string($registrationCheck))
        {
            // user is unregistered or is in the process of registering
            return $this->respondWithText($registrationCheck, $incomingText->getFrom());
        }
        // user is registered, grab the user's info
        $user = $registrationCheck;

        // determine user's role
        switch ($user->getRole())
        {
            case 'enthusiast':
                // text is from squirrel enthusiast
                $enthusiastResponse = $this->enthusiastTextProcessor->process($incomingText, $user);

                return $this->respondWithText($enthusiastResponse, $incomingText->getFrom());

            case 'expert':
                // text is from squirrel expert
                $expertResponse = $this->expertTextProcessor->process($incomingText, $user);

                if (!is_null($expertResponse))
                {
                    return $this->respondWithText($expertResponse, $incomingText->getFrom());
                }

                return NULL;
        }

        return $this->respondWithText("Whoops! Something went wrong. Please contact support.", $incomingText->getFrom());
    }

    /**
     * Formats a successful text message response.
     *
     * @param $message
     * @return array
     */
    private function respondWithText($message, $toPhone)
    {
        return [
            'type' => 'text',
            'toPhone' => $toPhone,
            'message' => $message
        ];
    }

}
