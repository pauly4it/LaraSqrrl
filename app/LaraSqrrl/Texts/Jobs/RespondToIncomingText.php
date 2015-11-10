<?php namespace App\LaraSqrrl\Texts\Jobs;

use App\Jobs\Job;
use App\LaraSqrrl\Texts\Services\IncomingTextProcessor;
use App\LaraSqrrl\Twilio\Services\RequestParameterExtractor;
use Illuminate\Contracts\Bus\SelfHandling;

class RespondToIncomingText extends Job implements SelfHandling {

    /**
     * @var array
     */
    private $request;

    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * Handle an incoming text.
     *
     * @param RequestParameterExtractor $requestParameterExtractor
     * @param IncomingTextProcessor $incomingTextProcessor
     * @return array|null
     */
    public function handle(RequestParameterExtractor $requestParameterExtractor,
                           IncomingTextProcessor $incomingTextProcessor)
    {
        // pull out necessary request paramaters
        $incomingText = $requestParameterExtractor->extract($this->request);

        // process incoming message to determine what the message is and the response
        return $incomingTextProcessor->process($incomingText);
    }

}
