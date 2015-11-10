<?php namespace App\Http\Controllers;

use App\LaraSqrrl\Twilio\Services\TwimlFormatter;
use Illuminate\Http\Request;
use App\LaraSqrrl\Texts\Jobs\RespondToIncomingText;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;

class TwilioController extends Controller {

    use DispatchesJobs;

    /**
     * @var TwimlFormatter
     */
    private $twimlFormatter;

    /**
     * @param TwimlFormatter $twimlFormatter
     */
    public function __construct(TwimlFormatter $twimlFormatter)
    {
        $this->twilmlFormatter = $twimlFormatter;
    }

    /**
     * Receive and process an incoming Twilio request.
     *
     * @param Request $request
     * @return Response|null
     */
    public function receive(Request $request)
    {
        // dispatch job
        $response = $this->dispatch(
            new RespondToIncomingText(
                $request->all()
            )
        );

        // if there was a response, return a Twmiml object
        if (!is_null($response))
        {
            $response = $this->twimlFormatter->format($response['message'], $response['toPhone']);

            $response = new Response($response, 200);
            $response->header('Content-Type', 'text/xml');
            return $response;
        }

        // if NULL response, return NULL to Twilio request
        return NULL;
    }

}
