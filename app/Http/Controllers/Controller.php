<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var int
     */
    private $statusCode = 200;

    /**
     * @return mixed
     */
    private function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $data
     * @param array $headers
     * @param null $options
     * @return mixed
     */
    private function respond($data, array $headers = [], $options = null)
    {
        return response()->json($data, $this->getStatusCode(), $headers, $options);
    }

    /**
     * @param mixed $payload
     * @param null $options
     * @param array $headers
     * @return array
     */
    public function respondSuccess($payload = null, $options = null, $headers = [])
    {
        return $this->respond(
            $payload,
            $headers,
            $options
        );
    }

    /**
     * @param null $payload
     * @param mixed $options
     * @return array
     */
    public function respondOK($payload = null, $options = JSON_UNESCAPED_SLASHES)
    {
        return $this->respondSuccess($payload, $options);
    }

}
