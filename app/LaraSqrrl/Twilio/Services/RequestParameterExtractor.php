<?php namespace App\LaraSqrrl\Twilio\Services;

use App\LaraSqrrl\Texts\Entities\IncomingTextObject;

class RequestParameterExtractor {

    /**
     * Extract data from incoming Twilio request.
     *
     * @param $request
     * @return IncomingTextObject
     */
    public function extract($request)
    {
        // if there's media in the text, pull it out
        $mediaUrl = [];
        $mediaType = [];
        if ($request['NumMedia'] > 0)
        {
            foreach (range(0, $request['NumMedia'] - 1) as $i)
            {
                $mediaUrl[] = $request['MediaUrl' . $i];
                $mediaType[] = $request['MediaContentType' . $i];
            }
        }

        // convert the request into an IncomingTextObject
        return new IncomingTextObject(
            $request['MessageSid'],
            $request['From'],
            $request['Body'],
            $request['NumMedia'],
            $mediaUrl,
            $mediaType
        );
    }

}
