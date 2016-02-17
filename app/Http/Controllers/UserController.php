<?php namespace App\Http\Controllers;

use App\LaraSqrrl\Responses\Response;
use App\LaraSqrrl\Submissions\Submission;
use App\LaraSqrrl\Users\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    /**
     * @param Request $request
     * @return array
     */
    public function getUser(Request $request)
    {
        $return = [];
        try
        {
            $userModel = new User();
            $user = $userModel->findByPhone("+1" . $request->route('phone'));

            // format data based on user role
            switch ($user->role)
            {
                case 'expert':
                    // fetch expert's responses and associated submissions
                    $responseModel = new Response;
                    $responses = $responseModel->where('user_id', $user->id)->with('submission.user')->get();
                    $data = $user->toArray();
                    $data['photos'] = $responses;
                    $return = $this->formatExpertUser($data);
                    break;

                case 'enthusiast':
                    // fetch enthusiast's submissions and associated responses
                    $submissionModel = new Submission;
                    $submissions = $submissionModel->where('user_id', $user->id)->with('response.user')->get();
                    $data = $user->toArray();
                    $data['photos'] = $submissions;
                    $return = $this->formatEnthusiastUser($data);
                    break;
            }
        }
        catch (\Exception $e)
        {
            // return some dummy data
            $return = $this->dummyResponse();
        }

        return $this->respondOK($return);
    }

    /**
     * Format enthusiast's data for API response.
     *
     * @param array $data
     * @return array
     */
    private function formatEnthusiastUser($data)
    {
        $json = [
            "name" => $data['name'],
            "phone" => $data['phone'],
            "role" => $data['role'],
            "photos" => []
        ];

        foreach ($data['photos'] as $photo)
        {
            $return = [
                "id" => $photo['id'],
                "url" => $photo['photo_url'],
                "responses" => []
            ];

            foreach ($photo['response'] as $response)
            {
                $return['responses'][] = [
                    "user" => $response['user']['name'],
                    "isSquirrel" => (boolean) $response['is_squirrel']
                ];
            }

            $json['photos'][] = $return;
        }

        return $json;
    }

    /**
     * Format expert's data for API response.
     *
     * @param array $data
     * @return array
     */
    private function formatExpertUser($data)
    {
        $json = [
            "name" => $data['name'],
            "phone" => $data['phone'],
            "role" => $data['role'],
            "photos" => []
        ];

        foreach ($data['photos'] as $response)
        {
            $return = [
                "id" => $response['submission']['id'],
                "url" => $response['submission']['photo_url'],
                "responses" => [
                    [
                        "user" => "Me",
                        "isSquirrel" => (boolean) $response['is_squirrel']
                    ]
                ]
            ];

            $json['photos'][] = $return;
        }

        return $json;
    }

    /**
     * Generate dummy API response.
     *
     * @return array
     */
    private function dummyResponse()
    {
        return [
            "name" => "Paul",
            "phone" => "15014636798",
            "role" => "enthusiast",
            "nuts" => 0,
            'photos' => [
                [
                    "id" => 1,
                    "url" => env('APP_URL') . "/images/acorns.png",
                    "responses" => [
                        [
                            "user" => "Steve",
                            "isSquirrel" => TRUE
                        ],
                        [
                            "user" => "Dave",
                            "isSquirrel" => FALSE
                        ],
                    ]
                ],
                [
                    "id" => 2,
                    "url" => env('APP_URL') . "/images/acorns.png",
                    "responses" => [
                        [
                            "user" => "Steve",
                            "isSquirrel" => TRUE
                        ],
                        [
                            "user" => "Dave",
                            "isSquirrel" => FALSE
                        ],
                    ]
                ],
                [
                    "id" => 3,
                    "url" => env('APP_URL') . "/images/acorns.png",
                    "responses" => [
                        [
                            "user" => "Steve",
                            "isSquirrel" => TRUE
                        ],
                        [
                            "user" => "Dave",
                            "isSquirrel" => FALSE
                        ],
                    ]
                ]
            ]
        ];
    }

}
